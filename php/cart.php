<?php
session_start();
include './components/header.php';
include './config/db.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: /accessory_store/php/auth.php");
    exit();
}

$id_user = $_SESSION['id_user'];

$totalPrice = 0;

$query = "SELECT ci.id_cart_item, ci.quantity,  p.image, p.id_product, p.name_product, p.price, p.quantity AS max_quantity
          FROM cart_items ci
          INNER JOIN products p ON ci.id_product = p.id_product
          INNER JOIN carts c ON ci.id_cart = c.id_cart
          WHERE c.id_user = $id_user";
$result = mysqli_query($link, $query);

$id_user = $_SESSION['id_user'];

$user_query = "SELECT * FROM users WHERE id_user='$id_user'";
$user_result = mysqli_query($link, $user_query);
$user_data = mysqli_fetch_assoc($user_result);

if ($result && mysqli_num_rows($result) > 0) {
?>
    <div class="cart">
        <div class="container">
            <h1 class="title">Корзина</h1>
            <div class="cart-content">
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $itemTotal = $row['price'] * $row['quantity'];
                    $totalPrice += $itemTotal;
                ?>
                    <div class="cart-item">
                        <table class="cart-table">
                            <tbody>
                                <tr>
                                    <td><a href="product.php?id=<?= $row['id_product']; ?>"><img class="cart-picture" src="<?= $row['image']; ?>" alt=""></a></td>
                                    <td class="name-product"><a href="product.php?id=<?= $row['id_product']; ?>"><?= $row['name_product']; ?></a></td>
                                    <td class="price"><?= $row['price']; ?> ₽</td>
                                    <td>
                                        <div class="counter">
                                            <span class="item-quantity-decrement">–</span>
                                            <input class="item-quantity" type="text" value="<?= $row['quantity']; ?>" min="1" max="<?= $row['max_quantity']; ?>" data-cart-item-id="<?= $row['id_cart_item']; ?>">
                                            <span class="item-quantity-increment">+</span>
                                        </div>
                                    </td>
                                    <td class="item-total"><?= $itemTotal; ?> ₽
                                    </td>
                                    <td><button class="remove-button" data-cart-item-id="<?= $row['id_cart_item']; ?>"></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php
                }
                ?>
                <h1 class="title">Оформление заказа</h1>
                <div class="placing-order">
                    <div class="contact-info">
                        <p><?= $user_data['name']; ?> <?= $user_data['surname']; ?> </p> <br>
                        <p><?= $user_data['telephone']; ?></p> <br>
                        <p><?= $user_data['email']; ?></p> <br>
                        <p>Самовывоз: <span class="light"> г. Дзержинский, пл.Дмитрия Донского 1А</span></p>
                        <p>Часы работы: <span class="light">Ежедневно с 10:00-19:00</span></p>
                    </div>
                    <div class="cart-result">
                        <h2 class="total-price">Cтоимость заказа: <?= $totalPrice; ?> ₽</h2>
                        <form action="/accessory_store/php/config/order_action.php" method="POST">
                            <input type="hidden" name="submit" value="true">
                            <input type="hidden" name="order_price" value="<?= $totalPrice; ?>">
                            <button type="submit">ОФОРМИТЬ ЗАКАЗ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
?>
    <div class="cart">
        <div class="container">
            <h1 class="title">Корзина</h1>
            <p>Корзина пуста</p>
        </div>
    </div>
<?php
}
include './components/footer.php';
?>
<script src="/accessory_store/js/script.js"></script>
</body>

</html>