<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>Задание 6</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js" defer></script>
    <style>
    .wrapper{
        width: 100%;
    }
    .form {
        width: 400px;
        margin:0 auto;
        margin-top: 5%;
        margin-bottom: 5%;
        padding: 5px;
        background: #fc0; 
        border: 3px solid #000000;
        border-radius: 10px;
        display:flex;
    }
    .login {
    display:flex;
    }
    .admin {
    position:absolute;
    right:20%;
    top:20%;
    }
    
    </style>
</head>

<body>


<?php

if (empty($_COOKIE[session_name()]) || empty($_SESSION['login'])){
    echo '
         <div class = "admin">
    <form action="admin.php">
    <button>Я Админ</button>
    </form>
    </div>
    ';
}
?>
    <div class="wrapper" id="forma">
    <?php
if (!empty($messages)) {
  print('<div id="messages">');
  // Выводим все сообщения.
  foreach ($messages as $message) {
    print($message);
  }
  print('</div>');
}

// Далее выводим форму отмечая элементы с ошибками классом error
// и задавая начальные значения элементов ранее сохраненными.
?>


		<div class="form">
		
		
        <form id="form1" action="" method="POST">
        
            <label for="name">
            Имя:<br>
            <input name="fio" id="name" class="form-control 
            <?php if ($errors['fio']) {print 'is-invalid';} ?>" placeholder="Введите ваше имя"  
            value="<?php print $values['fio']; ?>">
            </label><br>
                
            <label for="email">
            E-mail:<br>
            <input name="email" type="email" class="form-control 
            <?php if ($errors['email']) {print 'is-invalid';} ?>" id="email" placeholder="Введите вашу почту" 
            value="<?php print $values['email']; ?>">
			</label><br>
				
            <label for="date_of_birth">
            Дата рождения:<br>
            <input name="date_of_birth" type="date" class="form-control 
            <?php if ($errors['date_of_birth']) {print 'is-invalid';} ?>" 
            value="<?php print $values['date_of_birth']; ?>" />
            </label><br>
                
            Пол:<br>
            
                <label for="g1"><input type="radio" class="form-check-input 
                <?php if ($errors['gender']) {print 'is-invalid';} ?>" name="gender" id="g1" value="m" 
                <?php if ($values['gender']=='m') {print 'checked';} ?>>
                Мужской</label>
                
                <label for="g2"><input type="radio" class="form-check-input 
                <?php if ($errors['gender']) {print 'is-invalid';} ?>" name="gender" id="g2" value="w" 
                <?php if ($values['gender']=='w') {print 'checked';} ?>>
                Женский</label>
                    
                <label for="g3"><input type="radio" class="form-check-input
                <?php if ($errors['gender']) {print 'is-invalid';} ?>" name="gender" id="g3" value="t" 
                <?php if ($values['gender']=='t') {print 'checked';} ?>>
                Трансформер</label>
            
            Количество конечностей:<br>
                <label for="l1"><input type="radio" class="form-check-input 
                <?php if ($errors['limbs']) {print 'is-invalid';} ?>" name="limbs" id="l1" value="3" 
                <?php if ($values['limbs']=='3') {print 'checked';} ?>>
                    Меньше 4</label>
                <label for="l2"><input type="radio" class="form-check-input 
                <?php if ($errors['limbs']) {print 'is-invalid';} ?>" name="limbs" id="l2" value="4" 
                <?php if ($values['limbs']=='4') {print 'checked';} ?>>
                    4</label>
                <label for="l3"><input type="radio" class="form-check-input 
                <?php if ($errors['limbs']) {print 'is-invalid';} ?>" name="limbs" id="l3" value="5" 
                <?php if ($values['limbs']=='5') {print 'checked';} ?>>
                    Больше 4</label><br>    
            
            <div class = "fix">
                <label for="ab">
                	Сверхспособности (Можно выбрать несколько):
                	<br>
                	<select class="form-control"
                	<?php if ($errors['abilities']) {print 'is-invalid';} ?>" name="abilities[]" id="ab" 
                	multiple="multiple">
                    <option value="1" <?php if(!empty($values['abilities'][0])) {if ($values['abilities'][0]=='1') {print 'selected';}} ?>>Неуязвимость</option>         
       				<option value="2" <?php if(!empty($values['abilities'][1])) {if ($values['abilities'][1]=='2') {print 'selected';}} ?>>Сверхсила</option>
        			<option value="3" <?php if(!empty($values['abilities'][2])) {if ($values['abilities'][2]=='3') {print 'selected';}} ?>>Левитация</option>
        			<option value="4" <?php if(!empty($values['abilities'][3])) {if ($values['abilities'][3]=='4') {print 'selected';}} ?>>Манипуляции с временем</option>
        			<option value="5" <?php if(!empty($values['abilities'][4])) {if ($values['abilities'][4]=='5') {print 'selected';}} ?>>Я Бэтмен</option>
                	</select>
                </label><br>
             </div>
			<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
			<div class = "fix">
                <label for="bio">
                Биография:<br>
                <textarea name="bio" id="bio" cols="40" rows="2" class="form-control 
                <?php if ($errors['bio']) {print 'is-invalid';} ?>" ><?php print $values['bio']; ?></textarea>
                </label><br>
            </div>
            
            <label><input type="checkbox" class="form-check-input 
            <?php if ($errors['checkbox']) {print 'is-invalid';} ?>" id="checkbox" value="1" name="checkbox" 
            <?php if ($values['checkbox']=='1') {print 'checked';} ?>>
                С контрактом ознакомлен(а) </label><br>
            <input type="submit" id="btnend" class="btn btn-primary" value="Отправить">
 
        </form>
        
        <?php
if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login'])){
    echo '
        <div class = "login">
        <form action="" method="POST" >
            <input type="hidden" name="logout" value="true">
            <button type="submit">Выйти</button>
        </form>
        </div>
    ';
    if (empty($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(32));
    }
    $token = $_SESSION['token'];
}
else 
    echo'
    <div class = "login">
    <form action="login.php">
    <button>Войти</button>
    </form>
    </div>
';
?>
     	</div>
    </div>
</body>

</html>
