<?php
include('module.php');
header('Content-Type: text/html; charset=UTF-8');

session_start();

if (!empty($_SESSION['login'])) {
    
    header('Location: ./');
}

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

else {
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

  if(flag){

  $_SESSION['login'] = $_POST['login'];

  $_SESSION['uid'] =$result[0]["id"];


  header('Location: ./');}
}
