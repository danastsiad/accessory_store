<?php
include './config/db.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: /accessory_store/php/auth.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {

    $surname = mysqli_real_escape_string($link, $_POST['surname']);
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $telephone = mysqli_real_escape_string($link, $_POST['telephone']);
    $email = mysqli_real_escape_string($link, $_POST['email']);

    $id_user = $_SESSION['id_user'];

    $update_query = "UPDATE users SET surname='$surname', name='$name', telephone='$telephone', email='$email' WHERE id_user='$id_user'";

    if (mysqli_query($link, $update_query)) {
    } else {
        echo "Ошибка при обновлении данных пользователя";
    }
}

$id_user = $_SESSION['id_user'];
$user_query = "SELECT * FROM users WHERE id_user='$id_user'";
$user_result = mysqli_query($link, $user_query);
$user_data = mysqli_fetch_assoc($user_result);

$orders_query = "SELECT o.*, os.name_order_status 
                 FROM orders o
                 INNER JOIN order_statuses os ON o.id_order_status = os.id_order_status
                 WHERE o.id_user='$id_user'
                  ORDER BY o.id_order DESC";
$orders_result = mysqli_query($link, $orders_query);
?>
<div class="user">
    <div class="container">
        <div class="lk-title">
            <h1>Профиль</h1>
            <h2><a href="./config/exit.php" class="exit">Выйти</a></h2>
        </div>

        <div class="user-content">
            <h2>Добро пожаловать, <?= $user_data['name']; ?></h2>
            <form method="post">
                <div class="horizontal-inputs">
                    <input type="text" id="name" name="name" value="<?= $user_data['name']; ?>" placeholder="Имя">
                    <input type="text" id="surname" name="surname" value="<?= $user_data['surname']; ?>" placeholder="Фамилия">
                </div>
                <input type="text" id="telephone" name="telephone" value="<?= $user_data['telephone']; ?>" placeholder="Телефон">
                <input type="email" id="email" name="email" value="<?= $user_data['email']; ?>" placeholder="E-mail">
                <button type="submit" name="update">ИЗМЕНИТЬ ДАННЫЕ</button>
            </form>
        </div>

        <div class="user-orders">
            <h1 class="title">Заказы</h1>
            <?php
            if (mysqli_num_rows($orders_result) > 0) {
                while ($order = mysqli_fetch_assoc($orders_result)) {
            ?>
                    <div class="placing-order">
                        <div class="order">
                            <p class="order-number"> <u>Заказ №<?= $order['id_order']; ?> </u></p>
                            <p class="order-date"><?= $order['order_date']; ?></p>
                            <p class="order-price"><?= $order['order_price']; ?> ₽</p>
                            <p class="order-status"><?= $order['name_order_status']; ?></p>
                            <form method="post" action="../php/config/order_action.php">
                                <input type="hidden" name="order_id" value="<?= $order['id_order']; ?>">
                                <?php if ($order['id_order_status'] != 2) { ?>
                                    <?php if ($order['id_order_status'] != 5) { ?>
                                        <button type="submit" name="cancel">
                                            <p class="order-action">Отменить</p>
                                        </button>
                                    <?php } else { ?>
                                        <p class="order-action">Заказ получен</p>
                                    <?php } ?>
                                <?php } else { ?>
                                    <p class="order-action">Заказ отменен</p>
                                <?php } ?>
                            </form>
                        </div>
                        <?php
                        $order_items_query = "SELECT * FROM order_items WHERE id_order='{$order['id_order']}'";
                        $order_items_result = mysqli_query($link, $order_items_query);
                        ?>
                        <div class="spoileropen" role="tab" tabindex="1">
                            <div class="spoilerdesc">
                                <?php
                                while ($order_item_data = mysqli_fetch_assoc($order_items_result)) {

                                    $product_query = "SELECT * FROM products WHERE id_product='{$order_item_data['id_product']}'";
                                    $product_result = mysqli_query($link, $product_query);
                                    $product_data = mysqli_fetch_assoc($product_result);
                                ?>
                                    <div class="order-product">
                                        <img class="cart-picture" src="<?= $product_data['image']; ?>">
                                        <p class="order-name-product"><?= $product_data['name_product']; ?></p>
                                        <p class="light">Артикул: <?= $product_data['article']; ?></p>
                                        <p><?= $product_data['price']; ?> ₽</p>
                                        <p><?= $order_item_data['quantity']; ?> шт.</p>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <span role="button" tabindex="0" class="spoilerclose"></span>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>У вас пока нет заказов.</p>";
            }
            ?>
        </div>
    </div>
</div>
<script src="https://unpkg.com/imask"></script>
<script>
    new IMask(document.getElementById('telephone'), {
        mask: '+7(000)000-00-00',
        lazy: false
    });
</script>