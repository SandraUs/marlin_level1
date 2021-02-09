<?php
session_start();
require "functions.php";

$email = $_POST['email'];
$password = $_POST['password'];
$data = $_POST;
$user = get_user_by_email("reg", $email);

if (!empty($user)) {
    set_flash_message("danger", "Этот эл. адрес уже занят другим пользователем");
    redirect_to("/users.php");
}

$user_id = add_user("reg", $email, $password);

edit("reg", $data, $user_id);
set_status("reg", $data['status'], $user_id);
add_social_links("reg", $data, $user_id);
upload_avatar($_FILES['img_avatar'], "reg", $user_id);
set_flash_message("success", "Профиль успешно создан");

redirect_to("/users.php");
?>