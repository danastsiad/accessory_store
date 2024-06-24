<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cartItemId']) && isset($_POST['quantity'])) {
        
        $cartItemId = $_POST['cartItemId'];
        $newQuantity = $_POST['quantity'];
        
        if ($newQuantity > 0) {
            
            $query = "UPDATE cart_items SET quantity = $newQuantity WHERE id_cart_item = $cartItemId";
            $result = mysqli_query($link, $query);
            
            if ($result) {
                http_response_code(200);
            } else {
                http_response_code(500);
            }
        } else {
            http_response_code(400);
        }
    } elseif (isset($_POST['cartItemId'])) {
        
        $cartItemId = $_POST['cartItemId'];
        $query = "DELETE FROM cart_items WHERE id_cart_item = $cartItemId";
        $result = mysqli_query($link, $query);
        
        if ($result) {
            http_response_code(200);
        } else {
            http_response_code(500);
        }
    } else {
        http_response_code(400);
    }
}

mysqli_close($link);
?>
