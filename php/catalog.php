<?php
session_start();
include './components/header.php';
include './config/db.php';

$query = "SELECT * FROM products WHERE quantity >= 1";

include './config/catalog_filter.php';

?>
<div class="catalog">
    <div class="container">
        <h1 class="title">Каталог</h1>
        <div class="catalog-content">
            <div class="catalog-filter">
                <form method="GET" action="">
                    <?php if (isset($_GET['id'])) : ?>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id']) ?>">
                    <?php endif; ?><div class=""></div>
                    <p class="filter-name">Цена</p>
                    <div class="filter-price">
                        <div class="">
                            <label for="min_price">от</label>
                            <input type="number" name="min_price" id="min_price" value="<?= isset($minPrice) ? $minPrice : '' ?>">
                        </div>
                        <div class="max_price">
                            <label for="max_price">до</label>
                            <input type="number" name="max_price" id="max_price" value="<?= isset($maxPrice) ? $maxPrice : '' ?>">
                        </div>
                    </div>
                    <div class="filter-subcategory">
                        <p class="filter-name">Категория</p>
                        <?php if (!isset($_GET['id'])) : ?>
                            <?php while ($row_category = mysqli_fetch_assoc($result_categories)) : ?>
                                <p class="category-name"><?= $row_category['name_category']; ?></p>
                                <?php
                                $category_id = $row_category['id_category'];
                                $subcategory_query = "SELECT * FROM subcategories WHERE id_category = $category_id";
                                $result_subcategories = mysqli_query($link, $subcategory_query);
                                ?>

                                <?php while ($row_subcategory = mysqli_fetch_assoc($result_subcategories)) : ?>

                                    <div class="subcategory">
                                        <p class="subcategory-name" data-subcategory-id="<?= $row_subcategory['id_subcategories']; ?>"><?= $row_subcategory['name_subcategory']; ?></p>
                                        <img class="subcategory-img" src="/accessory_store/img/1.svg" alt="">
                                    </div>
                                    <div class="product-types" style="display: none;">
                                        <?php
                                        $subcategory_id = $row_subcategory['id_subcategories'];
                                        $product_type_query = "SELECT * FROM product_types WHERE id_subcategories = $subcategory_id ORDER BY name_product_type ASC";
                                        $result_product_types = mysqli_query($link, $product_type_query);
                                        ?>
                                        <?php while ($row_product_type = mysqli_fetch_assoc($result_product_types)) : ?>
                                            <div class="filter-checkbox">
                                            <?php
                                            $productTypeId = $row_product_type['id_product_type'];
                                            $isChecked = isset($_GET['product_type']) && in_array($productTypeId, $_GET['product_type']);
                                            ?>
                                            <input type="checkbox" name="product_type[]" value="<?= $productTypeId; ?>" <?= $isChecked ? 'checked' : ''; ?>>
                                            <label class="light"><?= $row_product_type['name_product_type']; ?></label>
                                        </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php endwhile; ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <p><?= $categoryName['name_category']; ?></p>
                            <?php while ($row_subcategory = mysqli_fetch_assoc($result_subcategories)) : ?>
                                <div class="subcategory">
                                        <p class="subcategory-name" data-subcategory-id="<?= $row_subcategory['id_subcategories']; ?>"><?= $row_subcategory['name_subcategory']; ?></p>
                                        <img class="subcategory-img" src="/accessory_store/img/1.svg" alt="">
                                    </div>
                                <div class="product-types" style="display: none;">
                                    <?php
                                    $subcategory_id = $row_subcategory['id_subcategories'];
                                    $product_type_query = "SELECT * FROM product_types WHERE id_subcategories = $subcategory_id ORDER BY name_product_type ASC";
                                    $result_product_types = mysqli_query($link, $product_type_query);
                                    ?>
                                    <?php while ($row_product_type = mysqli_fetch_assoc($result_product_types)) : ?>
                                        <div class="filter-checkbox">
                                            <?php
                                            $productTypeId = $row_product_type['id_product_type'];
                                            $isChecked = isset($_GET['product_type']) && in_array($productTypeId, $_GET['product_type']);
                                            ?>
                                            <input type="checkbox" name="product_type[]" value="<?= $productTypeId; ?>" <?= $isChecked ? 'checked' : ''; ?>>
                                            <label class="light"><?= $row_product_type['name_product_type']; ?></label>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                    <p class="filter-name">Цвет</p>
                    <?php foreach ($colors as $color) : ?>
                        <?php $isChecked = isset($_GET['colors']) && in_array($color, $_GET['colors']); ?>
                        <div class="filter-checkbox">
                            <input type="checkbox" name="colors[]" id="<?= $color ?>" value="<?= $color ?>" <?= $isChecked ? 'checked' : ''; ?>>
                            <label for="<?= $color ?>" <?= $isChecked ? 'class="selected"' : ''; ?>><?= $color ?></label>
                        </div>
                    <?php endforeach; ?>
                    <div class="filter-button">
                        <button class="button-catalog" type="submit">ПРИМЕНИТЬ</button>
                        <button class="button-reset"><a href="<?= isset($_GET['id']) ? 'catalog.php?id=' . $_GET['id'] : 'catalog.php' ?>" class="reset-filter-button">СБРОСИТЬ</a></button>
                    </div>
                </form>
            </div>
            <div class="catalog-products">

                <?php if (!empty($products)) : ?>
                    <?php foreach ($products as $product) : ?>
                        <div class="catalog-cart">
                            <a href="product.php?id=<?= $product['id_product']; ?>">
                                <img class="catalog-picture" src="<?= $product['image']; ?>" alt="">
                                <p><?= $product['name_product']; ?></p>
                            </a>
                            <div class="catalog-cart-points">
                                <p><?= $product['price']; ?> ₽</p>
                                <form class="addToCartForm" action="/accessory_store/php/config/add_to_cart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?= $product['id_product']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" style="border: none; background: none; cursor: pointer;">
                                        <img src="/accessory_store/img/cart.svg" alt="">
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <h2>Нет товаров</h2>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php include './components/footer.php'; ?>
<script src="/accessory_store/js/script.js"></script>
</body>

</html>