<?php
session_start();
require "functions.php";

$user_id = $_GET['id'];
$data = $_POST;

edit("reg", $data, $user_id);
set_flash_message("success", "Профиль обновлен");
redirect_to("/first/edit.php?id=" . $user_id);