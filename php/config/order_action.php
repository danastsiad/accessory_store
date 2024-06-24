<?php
session_start();
include 'db.php';
if (!isset($_SESSION['id_user'])) {
    header("Location: /accessory_store/php/auth.php");
    exit();
}
$id_user = $_SESSION['id_user'];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    $orderPrice = $_POST['order_price'];
    $idOrderStatus = 1;

    $orderQuery = "INSERT INTO orders (order_date, order_price, id_order_status, id_user) 
                   VALUES (NOW(), $orderPrice, $idOrderStatus, $id_user)";
    $orderResult = mysqli_query($link, $orderQuery);

    if ($orderResult) {

        $idOrder = mysqli_insert_id($link);

        $cartQuery = "SELECT ci.id_cart_item, ci.quantity, ci.id_product
                      FROM cart_items ci
                      INNER JOIN carts c ON ci.id_cart = c.id_cart
                      WHERE c.id_user = $id_user";
        $cartResult = mysqli_query($link, $cartQuery);

        while ($cartRow = mysqli_fetch_assoc($cartResult)) {
            $idCartItem = $cartRow['id_cart_item'];
            $quantity = $cartRow['quantity'];
            $idProduct = $cartRow['id_product'];

            $orderItemQuery = "INSERT INTO order_items (quantity, id_order, id_product) 
                               VALUES ($quantity, $idOrder, $idProduct)";
            $orderItemResult = mysqli_query($link, $orderItemQuery);
            $updateProductQuantityQuery = "UPDATE products 
                                           SET quantity = quantity - $quantity 
                                           WHERE id_product = $idProduct";
            mysqli_query($link, $updateProductQuantityQuery);

            $deleteCartItemQuery = "DELETE FROM cart_items WHERE id_cart_item = $idCartItem";
            mysqli_query($link, $deleteCartItemQuery);
        }
        header("Location: /accessory_store/php/components/user/order.php");
        exit();
    }
}
if (isset($_POST['cancel'])) {

    $idOrder = $_POST['order_id'];

    $updateOrderStatusQuery = "UPDATE orders SET id_order_status = 2 WHERE id_order = $idOrder";
    $updateOrderStatusResult = mysqli_query($link, $updateOrderStatusQuery);
    if ($updateOrderStatusResult) {

        $cancelledOrderItemsQuery = "SELECT * FROM order_items WHERE id_order = $idOrder";
        $cancelledOrderItemsResult = mysqli_query($link, $cancelledOrderItemsQuery);

        while ($cancelledOrderItem = mysqli_fetch_assoc($cancelledOrderItemsResult)) {
            $cancelledProductId = $cancelledOrderItem['id_product'];
            $cancelledQuantity = $cancelledOrderItem['quantity'];

            $updateItemStatusQuery = "UPDATE order_items SET id_order_item_action = 3 WHERE id_order = $idOrder AND id_product = $cancelledProductId";
            mysqli_query($link, $updateItemStatusQuery);

            $returnProductQuery = "UPDATE products SET quantity = quantity + $cancelledQuantity WHERE id_product = $cancelledProductId";
            mysqli_query($link, $returnProductQuery);
        }

        $cancelActionQuery = "INSERT INTO order_actions (id_order, id_order_status, id_user, time_order_actions, date_order_actions) 
                              VALUES ($idOrder, 2, $id_user, NOW(), CURDATE())";
        mysqli_query($link, $cancelActionQuery);
        $id_user = $_SESSION['id_user'];
        $query = "SELECT id_status FROM users WHERE id_user = $id_user";
        $result = mysqli_query($link, $query);

        if ($result) {
            $user_data = mysqli_fetch_assoc($result);
            $id_status = $user_data['id_status'];

            if ($id_status >= 2) {
                header("Location: /accessory_store/php/lk.php?tab=b");
            } else {
                header("Location: /accessory_store/php/lk.php");
            }
        }
        exit();
    }
}
if (isset($_POST['issue'])) {

    $idOrder = $_POST['order_id'];

    $updateOrderStatusQuery = "UPDATE orders SET id_order_status = 5 WHERE id_order = $idOrder";
    $updateOrderStatusResult = mysqli_query($link, $updateOrderStatusQuery);

    $issueActionQuery = "INSERT INTO order_actions (id_order, id_order_status, id_user, time_order_actions, date_order_actions) 
    VALUES ($idOrder, 5, $id_user, NOW(), CURDATE())";
    mysqli_query($link, $issueActionQuery);
    if ($updateOrderStatusResult) {

        $orderItemsQuery = "SELECT * FROM order_items WHERE id_order = $idOrder";
        $orderItemsResult = mysqli_query($link, $orderItemsQuery);

        while ($orderItem = mysqli_fetch_assoc($orderItemsResult)) {
            $productId = $orderItem['id_product'];
            $quantity = $orderItem['quantity'];

            $updateItemStatusQuery = "UPDATE order_items SET id_order_item_action = 4 WHERE id_order_item = {$orderItem['id_order_item']}";
            mysqli_query($link, $updateItemStatusQuery);

            $updateProductQuantityQuery = "UPDATE products SET quantity_store = quantity_store - $quantity WHERE id_product = $productId";
            $updateProductQuantityResult = mysqli_query($link, $updateProductQuantityQuery);
        }

        header("Location: /accessory_store/php/lk.php?tab=b");
        exit();
    }
}
mysqli_close($link);
