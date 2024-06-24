<?php
session_start();
include 'db.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = mysqli_real_escape_string($link, $_POST['user_name']);
    $surname = mysqli_real_escape_string($link, $_POST['user_surname']);
    $telephone = mysqli_real_escape_string($link, $_POST['user_telephone']);
    $email = mysqli_real_escape_string($link, $_POST['user_email']);
    $password = md5($_POST['user_pass']);

    if (!empty($name) && !empty($surname) && !empty($telephone) && !empty($email) && !empty($_POST['user_pass'])) {
        $check_email_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $check_email_result = mysqli_query($link, $check_email_query);
        $check_telephone_query = "SELECT * FROM users WHERE telephone='$telephone' LIMIT 1";
        $check_telephone_result = mysqli_query($link, $check_telephone_query);

        if (mysqli_num_rows($check_email_result) == 0 && mysqli_num_rows($check_telephone_result) == 0) {
            $insert_query = "INSERT INTO users (name, surname, telephone, email, password) VALUES ('$name', '$surname', '$telephone', '$email', '$password')";
            if (mysqli_query($link, $insert_query)) {
                $user_id = mysqli_insert_id($link);
                $_SESSION['id_user'] = $user_id;
                
                $response['status'] = 'success';
                $response['redirect'] = '/accessory_store/php/lk.php'; 
            } 
        } else {
            $response['status'] = 'error';
            if (mysqli_num_rows($check_email_result) > 0) {
                $response['message'] = 'Пользователь с таким email уже зарегистрирован.';
            } else {
                $response['message'] = 'Пользователь с таким номером телефона уже зарегистрирован.';
            }
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Все поля формы должны быть заполнены.';
    }
}

echo json_encode($response);
