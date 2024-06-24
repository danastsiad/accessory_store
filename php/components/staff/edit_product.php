<?php
session_start();
include '../header.php';
include '../../config/db.php';
if (!isset($_SESSION['id_user'])) {
    header("Location: /accessory_store/php/auth.php");
    exit();
}
$id_user = $_SESSION['id_user'];
$query = "SELECT id_status FROM users WHERE id_user = $id_user";
$result = mysqli_query($link, $query);

if ($result) {
    $user_data = mysqli_fetch_assoc($result);
    $id_status = $user_data['id_status'];
    
    if ($id_status >= 2) {
        if (isset($_POST['update_product'])) {
            
            $name_product = mysqli_real_escape_string($link, $_POST['name_product']);
            $id_product_type = mysqli_real_escape_string($link, $_POST['product_type']);
            $article = mysqli_real_escape_string($link, $_POST['article']);
            $description = mysqli_real_escape_string($link, $_POST['description']);
            $price = mysqli_real_escape_string($link, $_POST['price']);
            $material = mysqli_real_escape_string($link, $_POST['material']);
            $id_color = mysqli_real_escape_string($link, $_POST['color']);
            $quantity = mysqli_real_escape_string($link, $_POST['quantity']);
            $quantity_store = mysqli_real_escape_string($link, $_POST['quantity_store']);
            
            if (!empty($_FILES['picture']['name'])) {
                
                $filename = basename($_FILES['picture']['name']);
                $image = '/accessory_store/img/products/' . $filename;
                
                if (!move_uploaded_file($_FILES['picture']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $image)) {
                    echo "Ошибка загрузки изображения.";
                    exit();
                }
            } else {
                
                $image = mysqli_real_escape_string($link, $_POST['image']);
            }
            
            $product_id = mysqli_real_escape_string($link, $_POST['product_id']);
            $updateProductQuery = "UPDATE products SET name_product = '$name_product', id_product_type = '$id_product_type', article = '$article', description = '$description', price = '$price', material = '$material', id_color = '$id_color', quantity = '$quantity', quantity_store = '$quantity_store'";
            
            if (!empty($_FILES['picture']['name'])) {
                $updateProductQuery .= ", image = '$image'";
            }
            $updateProductQuery .= " WHERE id_product = $product_id";
            mysqli_query($link, $updateProductQuery);
            
            header("Location: ./edit_product.php?id=$product_id");
            exit();
        }
        if (isset($_POST['delete_product'])) {
            
            $product_id = mysqli_real_escape_string($link, $_POST['product_id']);
            
            $deleteProductQuery = "DELETE FROM products WHERE id_product = $product_id";
            
            mysqli_query($link, $deleteProductQuery);
            
            header("Location: /accessory_store/php/lk.php?tab=c");
            exit();
        }
        
        $productTypesQuery = "SELECT pt.*, s.name_subcategory FROM product_types pt INNER JOIN subcategories s ON pt.id_subcategories = s.id_subcategories";
        $productTypesResult = mysqli_query($link, $productTypesQuery);
        
        $colorsQuery = "SELECT * FROM colors";
        $colorsResult = mysqli_query($link, $colorsQuery);
        
        $product_id = mysqli_real_escape_string($link, $_GET['id']);
        $getProductQuery = "SELECT * FROM products WHERE id_product = $product_id";
        $productResult = mysqli_query($link, $getProductQuery);
        $product = mysqli_fetch_assoc($productResult);
?>
        <div class="edit_product">
            <div class="container">
                <h1>Редактирование товара</h1> 
                <div class="product-info">
                    <div class="product-info-left">
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-left">
                                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                <label class="light" for="name_product">Название:</label>
                                <input type="text" name="name_product" id="name_product" value="<?= $product['name_product'] ?>">
                                <label class="light" for="product_type">Категория:</label><br>
                                <select name="product_type" id="product_type">
                                    <?php while ($productType = mysqli_fetch_assoc($productTypesResult)) : ?>
                                        <option value="<?= $productType['id_product_type'] ?>" <?= $productType['id_product_type'] == $product['id_product_type'] ? 'selected' : '' ?>>
                                            <?= $productType['name_subcategory'] ?> - <?= $productType['name_product_type'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select> <br>
                                <label class="light" for="article">Артикул:</label>
                                <input type="text" name="article" id="article" value="<?= $product['article'] ?>">
                                <label class="light" for="description">Описание:</label>
                                <textarea name="description" id="description"><?= $product['description'] ?></textarea><br>
                                <label class="light" for="price">Цена:</label>
                                <input type="number" name="price" id="price" value="<?= $product['price'] ?>">
                                <label class="light" for="material">Состав:</label>
                                <input type="text" name="material" id="material" value="<?= $product['material'] ?>">
                                <label class="light" for="color">Цвет:</label><br>
                                <select name="color" id="color">
                                    <?php while ($color = mysqli_fetch_assoc($colorsResult)) : ?>
                                        <option value="<?= $color['id_color'] ?>" <?= $color['id_color'] == $product['id_color'] ? 'selected' : '' ?>>
                                            <?= $color['name_color'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select><br>
                            </div>
                            <div class="form-right">
                                <label class="light" for="quantity">Доступно:</label>
                                <input type="number" name="quantity" id="quantity" value="<?= $product['quantity'] ?>">
                                <label class="light" for="quantity_store">На складе:</label>
                                <input type="number" name="quantity_store" id="quantity_store" value="<?= $product['quantity_store'] ?>">
                            </div>
                    </div>
                    <div class="product-info-right">
                        <?php if (!empty($product['image'])) : ?>
                            <img class="product-info-img" src="<?= $product['image'] ?>" alt="Текущее изображение товара"> <br>
                        <?php endif; ?>
                        <label for="picture" class="button-reset">Изменить фото</label><br>
                        <input type="file" name="picture" id="picture" hidden>
                        <button class="button-catalog" type="submit" name="update_product">Сохранить</button> <br>
                        <button class="button-reset" type="submit" name="delete_product">Удалить товар</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php
    } else {
        header("Location: /accessory_store/php/auth.php");
    }
}
include '../footer.php';
