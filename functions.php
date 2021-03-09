<?php
session_start();

//Поиск пользователя по email. Принимает в параметрах email
function get_user_by_email ($email, $pdo) {
    
    $pdo = new PDO("mysql:host=localhost;dbname=project1", "root", "mysql");
    $sql = "SELECT * FROM users WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    return $user;
}

//Добавляем пользователя после того, как проверили его существование
function add_user ($pdo, $email, $password){

    $pdo = new PDO("mysql:host=localhost;dbname=project1", "root", "mysql");
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

    $_SESSION[$name] = $message;
}

//Вывод сообщения
function display_flash_message ($name){

   if (isset($_SESSION[$name])) {
      echo "<div class=\"alert alert-$name\">$_SESSION[$name]</div>";
      unset($_SESSION[$name]);
    }
}

function is_not_logged_in () {

    if(isset($_SESSION['email']) && !empty($_SESSION['email'])) {
        return false;
    }

    return true;
};

function check_admin () {
    if($_SESSION['role'] == "admin") {
        return true;
    return false;
};

    }

function edit($table, $data, $user_id) {
    $fields = '';

    foreach($data as $key => $value) {
        if($key == "Name" || $key == "job" || $key == "phone" || $key == "address" || $key == "role"){
            $fields .= $key . "=:" . $key . ",";
        }else {
            unset($data[$key]);
        }
    }

    $data += ['id'=>$user_id];
    $fields = rtrim($fields, ',');

    $sql = "UPDATE $table SET $fields WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute($data);
};


function set_status($table, $status, $user_id) {

    $pdo = new PDO("mysql:host=localhost;dbname=project1", "root", "mysql");
    $sql = "UPDATE $table SET status=:status WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["status" => $status,
        "id" => $user_id
    ]);
};

function upload_avatar($image, $table, $user_id) {


    $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . "." . $extension;

    if(move_uploaded_file($image['tmp_name'], "img/avatar/" . $filename))

    $pdo = new PDO("mysql:host=localhost;dbname=project1", "root", "mysql");
    $sql = "UPDATE $table SET img_avatar=:img_avatar WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["img_avatar" => $filename,
        "id" => $user_id
    ]);
};

function add_social_links($table, $data, $user_id) {
    $fields = '';

    foreach($data as $key => $value) {
        if($key == "vk" || $key == "telegram" || $key == "instagram"){
            $fields .= $key . "=:" . $key . ",";
        }else {
            unset($data[$key]);
        }
    }

    $data += ['id'=>$user_id];
    $fields = rtrim($fields, ',');

    $pdo = new PDO("mysql:host=localhost;dbname=project1", "root", "mysql");
    $sql = "UPDATE $table SET $fields WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute($data);
};

function is_author ($log_user_id, $edit_user_id) {
    if ($log_user_id == $edit_user_id) {
        return true;
    }
    return false;
};

function edit_credentials($user_id, $email, $password) {

    if($email == null) {
        $sql = "UPDATE $table SET password=:password WHERE id=:id";
        $statement = $pdo->prepare($sql);
        $statement->execute(["id" => $user_id,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }elseif($password == null) {
        $sql = "UPDATE $table SET email=:email WHERE id=:id";
        $statement = $pdo->prepare($sql);
        $statement->execute(["id" => $user_id,
            "email" => $email
        ]);
    }else{
        $sql = "UPDATE $table SET email=:email, password=:password  WHERE id=:id";
        $statement = $pdo->prepare($sql);
        $statement->execute(["id" => $user_id,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
};

function set_status($table, $status, $user_id) {
    $sql = "UPDATE $table SET status=:status WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["status" => $status,
        "id" => $user_id
    ]);
};

function has_image($user_id, $table) {
    $user_img = get_user_by_email($table, null, $user_id);

    if(empty($user_img['img_avatar'])) {
        return false;
    }
    return true;
}

function delete_avatar ($table, $user_id) {

    $img_for_delete = get_user_by_email($table, null, $user_id);
    unlink("/first/img/avatar/" . $img_for_delete['img_avatar']);

    $sql = "UPDATE $table SET img_avatar=:img_avatar WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["img_avatar" => NULL,
        "id" => $user_id
    ]);

}

function delete($table, $user_id) {

    $img_delete = get_user_by_email($table, null, $user_id);
    if (!empty($img_delete['img_avatar'])){
        unlink("/first/img/avatar/" . $img_delete['img_avatar']);
    }

    $sql = "DELETE FROM $table WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(":id", $user_id);
    $statement->execute();
}

//Перенаправление на другую страницу
function redirect_to ($path){

    header('Location: '.$path.'.php');
    exit;
    //header("Location: /marlin/page_login.php");
}
