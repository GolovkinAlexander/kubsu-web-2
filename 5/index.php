<?php
/**
 * Реализовать возможность входа с паролем и логином с использованием
 * сессии для изменения отправленных данных в предыдущей задаче,
 * пароль и логин генерируются автоматически при первоначальной отправке формы.
 */

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');
$user = 'u52844';
$pass = '3771734';
$db = new PDO('mysql:host=localhost;dbname=u52844', $user, $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 
// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Массив для временного хранения сообщений пользователю.
  $messages = array();

  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
    // Выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';
    // Если в куках есть пароль, то выводим сообщение.
    if (!empty($_COOKIE['pass'])) {
      $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass']));
    }
 
  }

  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['fio'] = !empty($_COOKIE['fio_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['date_of_birth'] = !empty($_COOKIE['date_of_birth_error']);
  $errors['gender'] = !empty($_COOKIE['gender_error']);
  $errors['limbs'] = !empty($_COOKIE['limbs_error']);
  $errors['abilities'] = !empty($_COOKIE['abilities_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['checkbox'] = !empty($_COOKIE['checkbox_error']);


  // Выдаем сообщения об ошибках.
  if ($errors['fio']) {
      // Удаляем куку, указывая время устаревания в прошлом.
      setcookie('fio_error', '', 100000);
      // Выводим сообщение.
      $messages[] = '<div class="error">Имя введено некорректно.</div>';
  }
  
  if ($errors['email']) {
      setcookie('email_error', '', 100000);
      $messages[] = '<div class="error">Email введён некорректно </div>';
  }
  if ($errors['date_of_birth']) {
      setcookie('date_of_birth_error', '', 100000);
      $messages[] = '<div class="error">Укажите дату рождения. </div>';
  }
  if ($errors['gender']) {
      setcookie('gender_error', '', 100000);
      $messages[] = '<div class="error">Укажите пол. </div>';
  }
  if ($errors['limbs']) {
      setcookie('limbs_error', '', 100000);
      $messages[] = '<div class="error">Укажите количество конечностей. </div>';
  }
  if ($errors['abilities']) {
      setcookie('abilities_error', '', 100000);
      $messages[] = '<div class="error">Выберите хотя бы 1 способность. </div>';
  }
  
  if ($errors['bio']) {
      setcookie('bio_error', '', 100000);
      $messages[] = '<div class="error">Биография заполнена некорректно</div>';
  }
  
  if ($errors['checkbox']) {
      setcookie('checkbox_error', '', 100000);
      $messages[] = '<div class="error">Для продолжения необходимо принять условия контракта</div>';
  }

  // Складываем предыдущие значения полей в массив, если есть.
  // При этом санитизуем все данные для безопасного отображения в браузере.
  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : strip_tags($_COOKIE['fio_value']);
  $values['email'] = empty($_COOKIE['email_value']) ? '' : strip_tags($_COOKIE['email_value']);
  $values['date_of_birth'] = empty($_COOKIE['date_of_birth_value']) ? '' :strip_tags($_COOKIE['date_of_birth_value']);
  $values['gender'] = empty($_COOKIE['gender_value']) ? '' : strip_tags($_COOKIE['gender_value']);
  $values['limbs'] = empty($_COOKIE['limbs_value']) ? '' : strip_tags($_COOKIE['limbs_value']);
  $values['abilities'] = empty($_COOKIE['abilities_value']) ? array() : unserialize($_COOKIE['abilities_value']);
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : strip_tags($_COOKIE['bio_value']);
  $values['checkbox'] = empty($_COOKIE['checkbox_value']) ? '' : strip_tags($_COOKIE['checkbox_value']);

  // Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
  // ранее в сессию записан факт успешного логина.
  if (count(array_filter($errors)) === 0 && !empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {

        $stmt = $db->prepare("SELECT * FROM maintable2 where user_id=?");
        $stmt -> execute([$_SESSION['uid']]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $values['fio'] = empty($result[0]['name']) ? '' : strip_tags($result[0]['name']);
    $values['email'] = empty($result[0]['email']) ? '' : strip_tags($result[0]['email']);
    $values['date_of_birth'] = empty($result[0]['date_of_birth']) ? '' :strip_tags($result[0]['date_of_birth']);
    $values['gender'] = empty($result[0]['gender']) ? '' : strip_tags($result[0]['gender']);
    $values['limbs'] = empty($result[0]['limbs']) ? '' : strip_tags($result[0]['limbs']);
    $values['bio'] = empty($result[0]['bio']) ? '' : strip_tags($result[0]['bio']);
    $values['checkbox'] = empty($result[0]['checkbox']) ? '' : strip_tags($result[0]['checkbox']);
    $values['limbs'] = empty($result[0]['limbs']) ? '' : strip_tags($result[0]['limbs']);

  $stmt = $db->prepare("SELECT * FROM m_ab2 where id_m=(SELECT id FROM maintable2 where user_id=?) ");
 
  $stmt -> execute([$_SESSION['uid']]);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($result as $res) {
    $values['abilities'][$res["id_ab"]-1] = empty($res) ? '' : strip_tags($res["id_ab"]);
}
  
  printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
  }

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
    
    if (isset($_POST['logout']) && $_POST['logout'] == 'true') {
        session_destroy();
        setcookie(session_name(), '', time() - 3600);
        setcookie('PHPSESSID', '', time() - 3600, '/');
       
        header('Location: ./');
        exit();
    }
    
  // Проверяем ошибки.
  $errors = FALSE;
 if (empty($_POST['fio']) ) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('fio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {

    if(!preg_match('/^([а-яА-ЯЁёa-zA-Z0-9_,.\s-]+)$/u', $_POST['fio'])){
        setcookie('fio_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('fio_value', $_POST['fio'], time() + 365 * 24 * 60 * 60);
  }

  if (empty($_POST['email'])) {
      setcookie('email_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
  }
  else {
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        setcookie('email_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
    }
      setcookie('email_value', $_POST['email'], time() + 365 * 24 * 60 * 60);
  }
  
  if (empty($_POST['date_of_birth'])) {
      $errors = TRUE;
      setcookie('date_of_birth_error', '1', time() + 24 * 60 * 60);
  }
  else {
    if(!preg_match('%[1-2][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9]%', $_POST['date_of_birth'])){
        $errors = TRUE;
        setcookie('date_of_birth_error', '1', time() + 24 * 60 * 60);
    }
      setcookie('date_of_birth_value', $_POST['date_of_birth'], time() + 365 * 24 * 60 * 60);
  }
  
  if (empty($_POST['gender'])) {
      $errors = TRUE;
      setcookie('gender_error', '1', time() + 24 * 60 * 60);
  }
  else {
    if( !in_array($_POST['gender'], ['w','m','t'])){
        $errors = TRUE;
        setcookie('gender_error', '1', time() + 24 * 60 * 60);
    }
      setcookie('gender_value', $_POST['gender'], time() + 365 * 24 * 60 * 60);
  }
  
  if (empty($_POST['limbs'])) {
      setcookie('limbs_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
  }
  else {
    if(!in_array($_POST['limbs'], [3,4,5])){
        setcookie('limbs_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
      setcookie('limbs_value', $_POST['limbs'], time() + 365 * 24 * 60 * 60);
  }
  
  if (empty($_POST['abilities'])) {
      setcookie('abilities_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
  }
  else {
      foreach ($_POST['abilities'] as $ability) {
          if (!in_array($ability, [1,2,3,4,5])){
              setcookie('abilities_error', '1', time() + 24 * 60 * 60);
              $errors = TRUE;
              break;
          }
      }
      $abs=array();
      
      foreach ($_POST['abilities'] as $res) {
          $abs[$res-1] = $res;
      }
      
      setcookie('abilities_value', serialize($abs), time() + 365 * 24 * 60 * 60);
  }

  
      if (empty($_POST['bio']) ) {
      setcookie('bio_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
  }
  else {
    if(!preg_match('/^([а-яА-ЯЁёa-zA-Z0-9_,.\s-]+)$/u', $_POST['bio'])){
    setcookie('bio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}
      setcookie('bio_value', $_POST['bio'], time() + 365 * 24 * 60 * 60);
  }
  
  
  if (empty($_POST['checkbox'])) {
      setcookie('checkbox_error', '1', time() + 24 * 60 * 60);
      setcookie('checkbox_value', '0', time() + 365 * 24 * 60 * 60);
      $errors = TRUE;
  }
  else {
        setcookie('checkbox_value', '1', time() + 365 * 24 * 60 * 60);


  }
  if ($errors) {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('fio_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('date_of_birth_error', '', 100000);
    setcookie('gender_error', '', 100000);
    setcookie('limbs_error', '', 100000);
    setcookie('abilities_error', '', 100000);
    setcookie('bio_error', '', 100000);
    setcookie('checkbox_error', '', 100000);
  }

  // Проверяем меняются ли ранее сохраненные данные или отправляются новые.
  if (!empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {

          $stmt = $db->prepare("UPDATE maintable2 SET name = ?, email = ?, date_of_birth = ?,gender = ?, limbs=?, bio = ?, checkbox =?  WHERE user_id = ?");
          $stmt -> execute([$_POST['fio'], $_POST['email'], $_POST['date_of_birth'], $_POST['gender'], $_POST['limbs'], $_POST['bio'], $_POST['checkbox'], $_SESSION['uid']]);
          $stmt = $db->prepare("SELECT * FROM m_ab2 where id_m=(SELECT id FROM maintable2 where user_id=?) ");
          $stmt -> execute([$_SESSION['uid']]);
          $result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

          $c=0;
          $flag=false;
          foreach ($_POST['abilities'] as $ability) {
            if ($result2[$ability]!=$ability){
                $flag=true;
                break;
            }
        }

        
          if($flag){
            $stmt = $db->prepare("DELETE FROM m_ab2 WHERE id_m=(SELECT id FROM maintable2 where user_id=?) ");
            $stmt -> execute([$_SESSION['uid']]);

            $stmt = $db->prepare("SELECT id FROM maintable2 where user_id=? ");
            $stmt -> execute([$_SESSION['uid']]);
            $result3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $db->prepare("INSERT INTO m_ab2 (id_m, id_ab) VALUES (?,?)");
            foreach ($_POST['abilities'] as $ability) {
                $stmt->execute([$result3[0]["id"], $ability]);
            }
          }
  }
  else {
    // Генерируем уникальный логин и пароль.
    $login = substr(uniqid('', true), -8, 8);
    $pass = uniqid();
    // Сохраняем в Cookies.
    setcookie('login', $login);
    setcookie('pass', $pass);
        
        // Подготовленный запрос. Не именованные метки.
        try {
          $stmt = $db->prepare("INSERT INTO user (user, pass) VALUES (?,?)");
          $stmt -> execute([$login, password_hash($pass, PASSWORD_DEFAULT)]);
          $id = $db->lastInsertId();
            $stmt = $db->prepare("INSERT INTO maintable2 (name,email,date_of_birth,gender,limbs,bio,checkbox, user_id) VALUES
    (?,?,?,?,?,?,?,?)");
            $stmt -> execute([$_POST['fio'], $_POST['email'], $_POST['date_of_birth'], $_POST['gender'], $_POST['limbs'], $_POST['bio'], $_POST['checkbox'], $id]);
            $id = $db->lastInsertId();
            $stmt = $db->prepare("INSERT INTO m_ab2 (id_m, id_ab) VALUES (?,?)");
            foreach ($_POST['abilities'] as $ability) {
                $stmt->execute([$id, $ability]);
            }
        }
        catch(PDOException $e){
            print('Error : ' . $e->getMessage());
            exit();
        }

  }

  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');
  // Делаем перенаправление.
  header('Location: ./');
}
