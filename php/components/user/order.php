<?php
session_start();
include '../header.php';
include '../../config/db.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: /accessory_store/php/auth.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$query = "SELECT * FROM orders WHERE id_user = $id_user ORDER BY id_order DESC LIMIT 1"; 
$result = mysqli_query($link, $query);


if ($result && mysqli_num_rows($result) > 0) {
    $order = mysqli_fetch_assoc($result); 
?>
    <div class="confirmation-order">
        <div class="container">
            <h1>Ваш заказ №<?= $order['id_order']; ?> от <?= $order['order_date']; ?>  принят!</h1> <br>
            <p>Cтоимость заказа: <?= $order['order_price']; ?> ₽</p> <br>
            <p>Самовывоз: <span class="light"> г. Дзержинский, пл.Дмитрия Донского 1А</span></p> <br>
            <p>Отслеживать статус заказа можно в <a href="/accessory_store/php/auth.php"><u>Личном кабинете</u></a>.</p>
        </div>
    </div>
<?php
} else {
    header("Location: /accessory_store/index.php");
    exit();
}
include '../footer.php';
?>
<script src="/accessory_store/js/script.js"></script>
</body>

</html>
