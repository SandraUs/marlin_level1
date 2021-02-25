<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

require_once'functions.php';

$email=$_POST['email'];
$password=$_POST['password'];

if(isset($email,$password)) {

    $pdo = new PDO("mysql:host=localhost;dbname=project1", "root", "mysql");


    $user=get_user_by_email ($email, $pdo);

    if(!empty($user)) {
        $_SESSION["danger"] = " Этот эл. адрес уже занят другим пользователем.";
        //header("Location: /first/page_register.php");
        //exit;
    } else {

        add_user($pdo, $email, $password);
        header("Location: /first/page_login.php");
        exit;
    }

}
