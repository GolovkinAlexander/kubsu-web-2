<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Задание 3</title>
    <style>
    .wrapper{
        width: 100%;
    }
    .form {
        width: 360px;
        margin:0 auto;
        margin-top: 5%;
        padding: 5px;
        background: #fc0; 
        border: 3px solid #000000;
        border-radius: 10px;
    }
    </style>
</head>

<body>
    <div class="wrapper" id="forma">
    	<div class="form">
        <form id="form1" action="" method="POST">
        
                <label for="name">
                Имя:<br>
                <input name="fio"
                 id="name" class="form-control" placeholder="Введите ваше имя">
                </label><br>
                
                <label for="email">
                E-mail:<br>
                <input name="email"
                type="email" class="form-control" id="email" 
                placeholder="Введите вашу почту">
				</label><br>
				
				<label for="date_of_birth">
                Дата рождения:<br>
                <input name="date_of_birth" 
                type="date" class="form-control" value="2000-01-01" />
                </label><br>

                Пол:<br>
                <label for="g1"><input type="radio" 
                	class="form-check-input" name="gender" id="g1" value="m">
                    Мужской</label>
                <label for="g2"><input type="radio" 
                	class="form-check-input" name="gender" id="g2" value="w">
                    Женский</label>
                <label for="g2"><input type="radio" 
                	class="form-check-input" name="gender" id="g3" value="t">
                    Трансформер</label>
                    
                Количество конечностей:<br>
                <label for="l1"><input type="radio" 
                class="form-check-input" name="limbs" id="l1" value="3">
                    Меньше 4</label>
                <label for="l2"><input type="radio" 
                class="form-check-input" name="limbs" id="l2" value="4">
                    4</label>
                <label for="l3"><input type="radio" 
                class="form-check-input" name="limbs" id="l3" value="5">
                    Больше 4</label><br>    
                    
                <label for="ab">
                	Сверхспособности (Можно выбрать несколько):
                	<br>
                	<select class="form-control"
                	name="abilities[]" id="ab" multiple="multiple">
                    <option value="1">Неуязвимость</option>         
       				<option value="2">Сверхсила</option>
        			<option value="3">Левитация</option>
        			<option value="4">Манипуляции с временем</option>
        			<option value="5">Я Бэтмен</option>
                	</select>
                </label><br>

                <label for="bio">
                Биография:<br>
                <textarea name="bio" id="bio" class="form-control">Расскажите о себе</textarea>
                </label><br>

            <label><input type="checkbox" 
            	name="checkbox" class="form-check-input" id="checkbox" value="1">
                С контрактом ознакомлен(а) </label><br>
            <input type="submit" id="btnend" class="btn btn-primary" value="Отправить">
            
        </form>
      </div>
    </div>
</body>

</html>