<?php
require "functions.php";

$user_id = $_GET['id'];
$email = $_SESSION['email'];
$user_email = $_POST['user_email'];
$user_password = $_POST['user_password'];

$user1 = get_user_by_email("reg", $user_email, null);

if($email == $user_email and empty($user_password)) {
    redirect_to("/first/security.php?id=".$user_id);
}elseif(!empty($user1['email']) and empty($user_password)) {
    set_flash_message("danger", "Этот эл. адрес уже занят другим пользователем.");
    redirect_to("/first/security.php?id=".$user_id);
}

if(empty($user_password) and empty($user1['email'])) {             
    edit_credentials("reg", $user_id, $user_email);
    if(is_author($_SESSION['id'], $user_id)){$_SESSION['email'] = $user_email;}
}elseif(!empty($user1['email']) and !empty($user_password)) {      
    edit_credentials("reg", $user_id, null, $user_password);
}else {                                                                    
    edit_credentials("reg", $user_id, $user_email, $user_password);
    if(is_author($_SESSION['id'], $user_id)){$_SESSION['email'] = $user_email;}
}

set_flash_message("message", "Профиль успешно обновлен");
redirect_to("/first/security.php?id=".$user_id);