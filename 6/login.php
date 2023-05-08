<?php

/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// Начинаем сессию.
session_start();

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
if (!empty($_SESSION['login'])) {
  // Если есть логин в сессии, то пользователь уже авторизован.
  // TODO: Сделать выход (окончание сессии вызовом session_destroy()
  //при нажатии на кнопку Выход).
  // Делаем перенаправление на форму.
  header('Location: ./');
}

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>Авториация</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js" defer></script>
    <style>
    .wrapper {
    width: 400px;
    margin:0 auto;
    margin-top:12%;
    padding:10px;
    background: #fc0; 
    border: 3px solid #000000;
    border-radius: 10px;
    }
    </style>
</head>


<body>

<div class="wrapper" id="forma">
        <form id="form1" action="" method="POST">
        
                <label for="name">Логин</label>
                <input name="login" id="name" class="form-control" placeholder="Введите ваш логин" ">
                
                <label for="pwd">Пароль</label>
                <input name="pass" class="form-control" id="pwd" placeholder="Введите ваш пароль" >
               <br>
            <input type="submit" id="btnend" class="btn btn-primary" value="Отправить">
            
        </form>
    </div>
    </div>
</body>

</html>

<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
  $user = 'u52844';
  $pass = '3771734';
  $db = new PDO('mysql:host=localhost;dbname=u52844', $user, $pass,
      [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 
      try {
      $stmt = $db->prepare("SELECT * FROM user 
      where user=?");
      $stmt -> execute([$_POST['login']]);
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $flag=false;
      if(password_verify($_POST['login'],$result["pass"]))
          $flag=true;
      }
      catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }
  // TODO: Проверть есть ли такой логин и пароль в базе данных.
  // Выдать сообщение об ошибках.
  if(flag){
  // Если все ок, то авторизуем пользователя.
  $_SESSION['login'] = $_POST['login'];
  // Записываем ID пользователя.
  $_SESSION['uid'] =$result[0]["id"];

  // Делаем перенаправление.
  header('Location: ./');}
}
