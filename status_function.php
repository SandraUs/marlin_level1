<?php
session_start();
require "functions.php";

$user_id = $_GET['id'];
$user_email = $_SESSION['user_email'];
$set_status = $_POST['status'];

set_status("reg", $set_status, $user_id);

set_flash_message("message", "Профиль успешно обновлен");
redirect_to("/first/status.php?id=".$user_id);
