<?php
session_start();
include 'db.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $telephone = mysqli_real_escape_string($link, $_POST['user_telephone']);
    $password = md5($_POST['user_pass']);

    if (!empty($telephone) && !empty($_POST['user_pass'])) {
        $check_query = "SELECT * FROM users WHERE telephone='$telephone' AND password='$password' LIMIT 1";
        $check_result = mysqli_query($link, $check_query);

        if (mysqli_num_rows($check_result) == 1) {
            $user_data = mysqli_fetch_assoc($check_result);
            $_SESSION['id_user'] = $user_data['id_user'];
            $response['status'] = 'success';
            $response['redirect'] = '/accessory_store/php/lk.php';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Неверный номер телефона или пароль.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Все поля формы должны быть заполнены.';
    }
}


echo json_encode($response);
