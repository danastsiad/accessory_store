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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $name_product = mysqli_real_escape_string($link, $_POST['name_product']);
            $id_product_type = mysqli_real_escape_string($link, $_POST['product_type']);
            $description = mysqli_real_escape_string($link, $_POST['description']);
            $price = mysqli_real_escape_string($link, $_POST['price']);
            $material = mysqli_real_escape_string($link, $_POST['material']);
            $id_color = mysqli_real_escape_string($link, $_POST['color']);
            $quantity_store = mysqli_real_escape_string($link, $_POST['quantity_store']);
            
            if (!empty($_FILES['picture']['name'])) {
                
                $filename = basename($_FILES['picture']['name']);
                $image = '/accessory_store/img/products/' . $filename;
                
                if (!move_uploaded_file($_FILES['picture']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $image)) {
                    exit();
                }
            } else {
                
                $image = '';
            }
            if ($id_color === 'new_color') {
                $new_color = mysqli_real_escape_string($link, $_POST['new_color']);
                $insertColorQuery = "INSERT INTO colors (name_color) VALUES ('$new_color')";
                mysqli_query($link, $insertColorQuery);
                
                $id_color = mysqli_insert_id($link);
            }
            
            $insertProductQuery = "INSERT INTO products (name_product, id_product_type, description, price, material, id_color, quantity_store, image) VALUES ('$name_product', '$id_product_type', '$description', '$price', '$material', '$id_color',  '$quantity_store', '$image')";
            mysqli_query($link, $insertProductQuery);
            
            $newProductId = mysqli_insert_id($link);
            
            
            header("Location: /accessory_store/php/components/staff/edit_product.php?id=$newProductId");
            exit();
        }
        
        $productTypesQuery = "SELECT pt.*, s.name_subcategory FROM product_types pt INNER JOIN subcategories s ON pt.id_subcategories = s.id_subcategories";
        $productTypesResult = mysqli_query($link, $productTypesQuery);
        
        $colorsQuery = "SELECT * FROM colors";
        $colorsResult = mysqli_query($link, $colorsQuery);
?>
        <div class="edit_product">
            <div class="container">
                <h1>Новый товар</h1>
                <div class="product-info">
                    <div class="product-info-left">
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-left">
                                <label class="light" for="name_product">Название:</label>
                                <input type="text" name="name_product" id="name_product" value="">
                                <label class="light" for="product_type">Категория:</label><br>
                                <select name="product_type" id="product_type">
                                    <option value="" disabled selected hidden></option>
                                    <?php while ($productType = mysqli_fetch_assoc($productTypesResult)) : ?>
                                        <option value="<?= $productType['id_product_type'] ?>">
                                            <?= $productType['name_subcategory'] ?> - <?= $productType['name_product_type'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select><br>
                                <label class="light" for="description">Описание:</label>
                                <textarea name="description" id="description"></textarea><br>
                                <label class="light" for="price">Цена:</label>
                                <input type="number" name="price" id="price" value="">
                                <label class="light" for="material">Состав:</label>
                                <input type="text" name="material" id="material" value="">
                                <label class="light" for="color">Цвет:</label><br>
                                <select name="color" id="color">
                                    <option value="" disabled selected hidden></option>
                                    <?php while ($color = mysqli_fetch_assoc($colorsResult)) : ?>
                                        <option value="<?= $color['id_color'] ?>">
                                            <?= $color['name_color'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                    <option value="new_color">Новый цвет</option>
                                </select><br>
                            </div>
                            <div class="form-right">
                                <label class="light" for="quantity_store">На складе:</label>
                                <input type="number" name="quantity_store" id="quantity_store" value="">
                                <div class="new_color" id="new_color" style="display: none;">
                                <label class="light" for="new_color">Новый цвет:</label><br>
                                <input  type="text" name="new_color"><br>
                                </div>
                            </div>
                    </div>
                    <div class="product-info-right">
                        <input type="file" name="picture" id="picture" hidden>
                        <label for="picture" class="button-reset">Загрузить фото</label><br>
                        <button class="button-catalog" type="submit">Сохранить</button>
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
?>
<script src="/accessory_store/js/script.js"></script>