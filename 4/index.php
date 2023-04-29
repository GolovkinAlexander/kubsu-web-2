<?php
/**
 * Реализовать проверку заполнения обязательных полей формы в предыдущей
 * с использованием Cookies, а также заполнение формы по умолчанию ранее
 * введенными значениями.
 */

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

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
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';
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
  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['date_of_birth'] = empty($_COOKIE['date_of_birth_value']) ? '' : $_COOKIE['date_of_birth_value'];
  $values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
  $values['limbs'] = empty($_COOKIE['limbs_value']) ? '' : $_COOKIE['limbs_value'];
  $values['abilities'] = empty($_COOKIE['abilities_value']) ? array() : unserialize($_COOKIE['abilities_value']);
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
  $values['checkbox'] = empty($_COOKIE['checkbox_value']) ? '' : $_COOKIE['checkbox_value'];
  
  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
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
      setcookie('abilities_value', serialize($_POST['abilities']), time() + 365 * 24 * 60 * 60);
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
    // TODO: тут необходимо удалить остальные Cookies.
  }

  // Сохранение в БД.
  $user = 'u52844';
  $pass = '3771734';
  $db = new PDO('mysql:host=localhost;dbname=u52844', $user, $pass,
      [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
      // Подготовленный запрос. Не именованные метки.
      try {
          $stmt = $db->prepare("INSERT INTO maintable (name,email,date_of_birth,gender,limbs,bio,checkbox) VALUES
  (?,?,?,?,?,?,?)");
          $stmt -> execute([$_POST['fio'], $_POST['email'], $_POST['date_of_birth'], $_POST['gender'], $_POST['limbs'], $_POST['bio'], $_POST['checkbox']]);
          $id = $db->lastInsertId();
          $stmt = $db->prepare("INSERT INTO m_ab (id_m, id_ab) VALUES (?,?)");
          foreach ($_POST['abilities'] as $ability) {
              $stmt->execute([$id, $ability]);
          }
      }
      catch(PDOException $e){
          print('Error : ' . $e->getMessage());
          exit();
      }
      
  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');
  // Делаем перенаправление.
  header('Location: index.php');
}
