<?php
require "functions.php";
$user_id = $_GET['id'];
$user = get_user_by_email("reg", null, $user_id);
if (empty($_FILES['image']['name'])) {
    redirect_to("/first/media.php?id=" . $user_id);
} else {
    if (!empty($user['img_avatar'])) {
        delete_avatar("reg", $user_id);
    }
    upload_avatar($_FILES['image'], "reg", $user_id); //загрузка аватара
}

set_flash_message("message", "Профиль успешно обновлен");
redirect_to("/first/page_profile.php?id=" . $user_id);