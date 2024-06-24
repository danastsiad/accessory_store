<?php
session_start();
include './components/header.php';
include './config/db.php';

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

    if ($id_status == 1) {
        require_once('./components/user/user.php');
    } elseif ($id_status == 2) {
        require_once('./components/staff/staff.php');
    } elseif ($id_status == 3) {
    }
}

mysqli_close($link);

include './components/footer.php';
?>
<script src="/accessory_store/js/script.js"></script>
</body>
</html>