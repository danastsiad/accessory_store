<?php
session_start();
include 'db.php';

$response = array(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['id_user'])) {
        $response['redirect'] = 'auth.php';
    } else {
        
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $user_id = $_SESSION['id_user'];
        
        $get_max_quantity_query = "SELECT quantity FROM products WHERE id_product = '$product_id'";
        $get_max_quantity_result = mysqli_query($link, $get_max_quantity_query);
        $max_quantity_data = mysqli_fetch_assoc($get_max_quantity_result);

        $max_quantity = $max_quantity_data['quantity'];
        
        $check_cart_query = "SELECT * FROM carts WHERE id_user = '$user_id'";
        $check_cart_result = mysqli_query($link, $check_cart_query);
        if (mysqli_num_rows($check_cart_result) == 0) {
            
            $create_cart_query = "INSERT INTO carts (id_user) VALUES ('$user_id')";
            mysqli_query($link, $create_cart_query);
        }
        
        $cart_query = "SELECT * FROM carts WHERE id_user = '$user_id'";
        $cart_result = mysqli_query($link, $cart_query);
        $cart_data = mysqli_fetch_assoc($cart_result);

        $cart_id = $cart_data['id_cart'];
        
        $current_quantity_query = "SELECT SUM(quantity) as total_quantity FROM cart_items WHERE id_cart = '$cart_id' AND id_product = '$product_id'";
        $current_quantity_result = mysqli_query($link, $current_quantity_query);
        $current_quantity_data = mysqli_fetch_assoc($current_quantity_result);
        
        $current_quantity = $current_quantity_data['total_quantity'];
        
        if ($current_quantity + $quantity <= $max_quantity) {
            
            if ($quantity > 0) {
                
                $check_item_query = "SELECT * FROM cart_items WHERE id_cart = '$cart_id' AND id_product = '$product_id'";
                $check_item_result = mysqli_query($link, $check_item_query);

                if (mysqli_num_rows($check_item_result) > 0) {
                    $update_quantity_query = "UPDATE cart_items SET quantity = quantity + $quantity WHERE id_cart = '$cart_id' AND id_product = '$product_id'";
                    mysqli_query($link, $update_quantity_query);
                } else {
                    $add_to_cart_query = "INSERT INTO cart_items (id_cart, quantity, id_product) VALUES ('$cart_id', '$quantity', '$product_id')";
                    mysqli_query($link, $add_to_cart_query);
                }
                
                $response['success'] = true;
            }
        }
    }
}
echo json_encode($response);
