<?php
include('module.php');
header('Content-Type: text/html; charset=UTF-8');

session_start();

if (!empty($_SESSION['login'])) {
    
    header('Location: ./');
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    ?>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="favicon.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>Логин</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js" defer></script>
</head>
<body>
<div class="col col-10 col-md-11" id="forma">
        <form id="form1" action="" method="POST">
            <div class="form-group">
                <label for="name">Логин</label>
                <input name="login" id="name" class="form-control" placeholder="Введите ваш логин" ">
            </div>
            <div class="form-group">
                <label for="pwd">Пароль</label>

                <input name="pass" class="form-control" id="pwd" placeholder="Введите ваш пароль" >

            </div>
           
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
