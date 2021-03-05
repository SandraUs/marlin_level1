<?php
require "functions.php";
$user_id = $_GET['id'];

if (is_not_logged_in()) {
    redirect_to("/first/page_login.php");
}

if (!check_admin() and !is_author($_SESSION['id'], $user_id)) {
    set_flash_message("danger", "Редактируйте только свой профиль");
    redirect_to("/first/users.php");
}

$user = get_user_by_email("reg", null, $user_id);

delete("reg", $user_id);

if ($_SESSION['id'] == $user_id) {
    session_unset();
    session_destroy();
    redirect_to('/first/page_register.php');
} else {
    set_flash_message("message", "Пользователь " . $user['Name'] .  " был удален");
    redirect_to("/first/users.php");
}
