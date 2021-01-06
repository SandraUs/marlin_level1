<?php
session_start();

//Поиск пользователя по email. Принимает в параметрах email
function get_user_by_email ($email, $pdo) {
    
 
    $sql = "SELECT * FROM users WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    return $user;
}

//Добавляем пользователя после того, как проверили его существование
function add_user ($pdo, $email, $password){

    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $statement = $pdo->prepare($sql);
    $statement->execute([
    ':email'=>$email,
    ':password'=>password_hash($password, PASSWORD_DEFAULT)]);
}


/*parameters:
         string - $email;
         string - $password;
  Discription: авторизация пользователя
  Return_value: boolean;
*/


function login ($email, $password ){

$user = get_user_by_email($email);
    if(empty($user)) {
        return "email не найден";}
    elseif (!password_verify($password, $user['password'])) {
        return "неверный пароль";
    }else {
        return $user;
    }

}

//Устанавливаем flash сообщение
function set_flash_message ($name, $message){

    $_SESSION['name'] = $name;
    $_SESSION['message'] = $message;
}

//Вывод сообщения
function display_flash_message ($name){

   if (isset($_SESSION[$name])) {
      echo "<div class=\"alert alert-$name\">$_SESSION[$name]</div>";
      unset($_SESSION[$name]);
    }
}

//Перенаправление на другую страницу
function redirect_to ($path){

    header('Location: '.$path.'.php');
    exit;
    //header("Location: /marlin/page_login.php");
}
