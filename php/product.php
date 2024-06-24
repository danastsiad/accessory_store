<?php
session_start();
include './components/header.php';
include './config/db.php';

$id_product = $_GET['id'] ?? '';

$query = "SELECT * FROM products p
INNER JOIN colors c ON p.id_color = c.id_color
WHERE p.id_product = $id_product AND p.quantity >= 1";
$result = mysqli_query($link, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_assoc($result);
?>

    <div class="product">
        <div class="container">
            <div class="product-content">
                <div class="product-content-picture">
                    <img src="<?= $product['image']; ?>" alt="Product Image">
                </div>
                <div class="product-content-points">
                    <h1><?= $product['name_product']; ?></h1>
                    <p class="product-article">Артикул: <?= $product['article']; ?></p>
                    <h2><?= $product['price']; ?> ₽</h2>
                    <form class="addToCart" method="post" action="/accessory_store/php/config/add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?= $product['id_product']; ?>">
                        <div class="counter">
                            <span class="input-number-decrement">–</span><input class="input-number" type="text" value="1" min="0" max="<?php echo $product['quantity']; ?>"><span class="input-number-increment">+</span>
                            <button type="submit" class="button-product">В КОРЗИНУ</button>
                        </div>
                    </form>
                    <p><?= $product['description']; ?></p>
                    <p class="product-material"><span class="characteristics">Материал:</span> <?= $product['material']; ?></p>
                    <p><span class="characteristics">Цвет: </span><?= $product['name_color']; ?></p>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
    echo "Товар не найден.";
}
$query_related = "SELECT * FROM products WHERE id_product != '$id_product' ORDER BY RAND() LIMIT 4";
$result_related = mysqli_query($link, $query_related);

$related_products = [];

if (mysqli_num_rows($result_related) > 0) {
    while ($row_related = mysqli_fetch_assoc($result_related)) {
        $related_products[] = $row_related;
    }
?>
    <div class="catalog">
        <div class="container">
        <h1 class="title">Смотрите также</h1>
            <div class="catalog-rec">
                <?php foreach ($related_products as $related_product) : ?>
                    <div class="catalog-cart">
                        <a href="product.php?id=<?php echo $related_product['id_product']; ?>">
                            <img class="catalog-picture" src="<?php echo $related_product['image']; ?>" alt="">
                            <p><?php echo $related_product['name_product']; ?></p>
                        </a>
                        <div class="catalog-cart-points">
                            <p><?= $related_product['price']; ?> ₽</p>
                            <form class="addToCartForm" action="/accessory_store/php/config/add_to_cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?= $related_product['id_product']; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" style="border: none; background: none; cursor: pointer;">
                                    <img src="/accessory_store/img/cart.svg" alt="">
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php }
include './components/footer.php';
?>

<script src="/accessory_store/js/script.js"></script>
</body>

</html>