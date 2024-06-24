<?php
include './config/db.php';

if (!isset($_GET['min_price']) || empty($_GET['min_price'])) {
    $query_min_price = "SELECT MIN(price) AS min_price FROM products";
    $row_min_price = mysqli_fetch_assoc(mysqli_query($link, $query_min_price));
    $minPrice = intval($row_min_price['min_price']);
} else {
    $minPrice = intval($_GET['min_price']);
}

if (!isset($_GET['max_price']) || empty($_GET['max_price'])) {
    $query_max_price = "SELECT MAX(price) AS max_price FROM products";
    $row_max_price = mysqli_fetch_assoc(mysqli_query($link, $query_max_price));
    $maxPrice = intval($row_max_price['max_price']);
} else {
    $maxPrice = intval($_GET['max_price']);
}

if (isset($_GET['min_price']) && isset($_GET['max_price']) && !empty($_GET['min_price']) && !empty($_GET['max_price'])) {
    $query .= " AND price BETWEEN ? AND ?";
}

$colorConditions = "";
if (isset($_GET['colors']) && is_array($_GET['colors']) && !empty($_GET['colors'])) {
    $colors = array_map(function ($color) use ($link) {
        return mysqli_real_escape_string($link, $color);
    }, $_GET['colors']);

    $colorConditions = " AND id_color IN (SELECT id_color FROM colors WHERE name_color IN ('" . implode("','", $colors) . "'))";
}

$productTypeConditions = "";
if (isset($_GET['product_type']) && is_array($_GET['product_type']) && !empty($_GET['product_type'])) {
    $productTypes = array_map('intval', $_GET['product_type']);
    $productTypeConditions = " AND id_product_type IN (" . implode(",", $productTypes) . ")";
}

$query .= $productTypeConditions;

$query_colors = "SELECT * FROM colors";

if (isset($_GET['id'])) {

    $categoryId = intval($_GET['id']);

    $categoryQuery = "SELECT * FROM categories WHERE id_category = $categoryId";
    $categoryName = mysqli_fetch_assoc(mysqli_query($link, $categoryQuery));

    $subcategory_query = "SELECT * FROM subcategories WHERE id_category = $categoryId";
    $result_subcategories = mysqli_query($link, $subcategory_query);

    $priceQuery = "SELECT MIN(price) AS min_price, MAX(price) AS max_price FROM products 
                    WHERE id_product_type IN 
                        (SELECT id_product_type FROM product_types 
                        WHERE id_subcategories IN 
                            (SELECT id_subcategories FROM subcategories 
                            WHERE id_category = ?))";

    $priceStmt = mysqli_prepare($link, $priceQuery);
    mysqli_stmt_bind_param($priceStmt, "i", $categoryId);
    mysqli_stmt_execute($priceStmt);
    $priceResult = mysqli_stmt_get_result($priceStmt);
    $priceRow = mysqli_fetch_assoc($priceResult);

    $minPrice = intval($priceRow['min_price']);
    $maxPrice = intval($priceRow['max_price']);
    if (isset($_GET['min_price']) && !empty($_GET['min_price'])) {
        $minPrice = intval($_GET['min_price']);
    }

    if (isset($_GET['max_price']) && !empty($_GET['max_price'])) {
        $maxPrice = intval($_GET['max_price']);
    }

    $query = "SELECT * FROM products 
              WHERE quantity >= 1 
              AND id_product_type IN 
                  (SELECT id_product_type FROM product_types 
                  WHERE id_subcategories IN 
                      (SELECT id_subcategories FROM subcategories 
                      WHERE id_category = ?))";

    $query .= " AND price BETWEEN ? AND ?";
    $query .= $colorConditions;
    $query .= $productTypeConditions;
  
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "iii", $categoryId, $minPrice, $maxPrice);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $stmt = mysqli_prepare($link, $query . $colorConditions);
    mysqli_stmt_bind_param($stmt, "iii", $categoryId, $minPrice, $maxPrice);

    $query_colors .= " WHERE id_color IN (SELECT DISTINCT id_color FROM products 
                                            WHERE id_product_type IN 
                                                (SELECT id_product_type FROM product_types 
                                                WHERE id_subcategories IN 
                                                    (SELECT id_subcategories FROM subcategories 
                                                    WHERE id_category = $categoryId))
                                            AND quantity >= 1) ORDER BY name_color ASC";
} else {
    $stmt = mysqli_prepare($link, $query . $colorConditions);

    if (isset($_GET['min_price']) && isset($_GET['max_price']) && !empty($_GET['min_price']) && !empty($_GET['max_price'])) {
        mysqli_stmt_bind_param($stmt, "ii", $minPrice, $maxPrice);
    }

    $query_colors .= " WHERE id_color IN (SELECT DISTINCT id_color FROM products WHERE quantity >= 1) ORDER BY name_color ASC";
}

$result_colors = mysqli_query($link, $query_colors);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$products = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
$query_categories = "SELECT * FROM categories ORDER BY FIELD(id_category, 2) DESC, FIELD(id_category, 1) DESC";
$result_categories = mysqli_query($link, $query_categories);

$colors = [];
while ($row_color = mysqli_fetch_assoc($result_colors)) {
    $colors[] = $row_color['name_color'];
}