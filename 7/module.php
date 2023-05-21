<?php

global $db;
    $user = 'u52844';
    $pass = '3771734';
    $db = new PDO('mysql:host=localhost;dbname=u52844', $user, $pass, [
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    

function update_tables($db, $id, $data, $abilities) {
    $stmt = $db->prepare("UPDATE maintable2 SET name = ?, email = ?, date_of_birth = ?, gender = ?, limbs = ?, bio = ?, checkbox = ? WHERE id = ?");
    $stmt -> execute([$data['name'], $data['email'], $data['date_of_birth'], $data['gender'], $data['limbs'], $data['bio'], $data['checkbox'], $id]);
    
    $stmt = $db->prepare("SELECT * FROM m_ab2 WHERE id_m = ?");
    $stmt -> execute([$id]);
    $result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $flag = false;
    foreach ($abilities as $ability) {
        $found = false;
        foreach ($result2 as $row) {
            if ($row['id_ab'] == $ability) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $flag = true;
            break;
        }
    }
 
    if(count($result2)==3&&count($abilities)!=3)
        $flag=true;
    if ($flag) {
        $stmt = $db->prepare("DELETE FROM m_ab2 WHERE id_m = ?");
        $stmt -> execute([$id]);
        $stmt = $db->prepare("INSERT INTO m_ab2 (id_m, id_ab) VALUES (?,?)");
        foreach ($abilities as $ability) {
            $stmt->execute([$id, $ability]);
        }
    }

}
function validateFormData($data, $abilities ,$row=null) {
    $errors = false;
    
    if (empty($data['name'])) {
        setcookie('fio_error'.$row, '1', time() + 24 * 60 * 60);
        $errors = true;
    } else {
        if (!preg_match('/^([а-яА-ЯЁёa-zA-Z0-9_,.\s-]+)$/u', $data['name'])) {
            setcookie('fio_error'.$row, '1', time() + 24 * 60 * 60);
            $errors = true;
        }
        setcookie('fio_value', $data['name'], time() + 365 * 24 * 60 * 60);
    }
    
    if (empty($data['email'])) {
        setcookie('email_error'.$row, '1', time() + 24 * 60 * 60);
        $errors = true;
    } else {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            setcookie('email_error'.$row, '1', time() + 24 * 60 * 60);
            $errors = true;
        }
        setcookie('email_value', $data['email'], time() + 365 * 24 * 60 * 60);
    }
    
    if (empty($data['date_of_birth'])) {
        $errors = true;
        setcookie('date_of_birth_error'.$row, '1', time() + 24 * 60 * 60);
    } else {
        if (!preg_match('%[1-2][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9]%', $data['date_of_birth'])) {
            $errors = true;
            setcookie('date_of_birth_error', '1', time() + 24 * 60 * 60);
        }
        setcookie('date_of_birth_value', $data['date_of_birth'], time() + 365 * 24 * 60 * 60);
    }
    
    if (empty($data['gender'])) {
        $errors = true;
        setcookie('gender_error'.$row, '1', time() + 24 * 60 * 60);
    } else {
        if (!in_array($data['gender'], ['w', 'm', 't'])) {
            $errors = true;
            setcookie('gender_error'.$row, '1', time() + 24 * 60 * 60);
        }
        setcookie('gender_value', $data['gender'].$row, time() + 365 * 24 * 60 * 60);
    }
    
    if (empty($data['limbs'])) {
        setcookie('limbs_error'.$row, '1', time() + 24 * 60 * 60);
        $errors = true;
    } else {
        if (!in_array($data['limbs'], [3, 4, 5])) {
            setcookie('limbs_error'.$row, '1', time() + 24 * 60 * 60);
            $errors = true;
        }
        setcookie('limbs_value', $data['limbs'], time() + 365 * 24 * 60 * 60);
    }
    
    if (empty($abilities)) {
        setcookie('abilities_error'.$row, '1', time() + 24 * 60 * 60);
        $errors = true;
    } else {
        foreach ($abilities as $ability) {
            if (!in_array($ability, [1, 2, 3, 4, 5])) {
                setcookie('abilities_error'.$row, '1', time() + 24 * 60 * 60);
                $errors = true;
                break;
            }
        }
        $abs = array();
        foreach ($abilities as $res) {
            $abs[$res - 1] = $res;
        }
        setcookie('abilities_value', serialize($abs), time() + 365 * 24 * 60 * 60);
    }
    
    if (empty($data['bio'])) {
        setcookie('bio_error'.$row, '1', time() + 24 * 60 * 60);
        $errors = true;
    } else {
        if (!preg_match('/^([а-яА-ЯЁёa-zA-Z0-9,.\s-]+)$/u', $data['bio'])) {
            setcookie('bio_error'.$row, '1', time() + 24 * 60 * 60);
            $errors = true;
        }
        setcookie('bio_value', $data['bio'], time() + 365 * 24 * 60 * 60);
    }
    
    if (empty($data['checkbox'])) {
        setcookie('checkbox_error'.$row, '1', time() + 24 * 60 * 60);
        setcookie('checkbox_value', '0', time() + 365 * 24 * 60 * 60);
        $errors = true;
    } else {
        setcookie('checkbox_value', '1', time() + 365 * 24 * 60 * 60);
    }
    
    return $errors;
}
function err_declare($counter=null){
    $errors = array();
    $errors['fio'] = !empty($_COOKIE['fio_error'.$counter]);
    $errors['email'] = !empty($_COOKIE['email_error'.$counter]);
    $errors['date_of_birth'] = !empty($_COOKIE['date_of_birth_error'.$counter]);
    $errors['gender'] = !empty($_COOKIE['gender_error'.$counter]);
    $errors['limbs'] = !empty($_COOKIE['limbs_error'.$counter]);
    $errors['abilities'] = !empty($_COOKIE['abilities_error'.$counter]);
    $errors['bio'] = !empty($_COOKIE['bio_error'.$counter]);
    $errors['checkbox'] = !empty($_COOKIE['checkbox_error'.$counter]);
    return $errors;
}

function msg_declare($messages,$errors, $counter=null){
   
    if ($errors['fio']) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('fio_error'.$counter, '', 100000);
        // Выводим сообщение.
        $messages[] = '<div class="error">Имя введено некорректно.</div>';
    }
    
    if ($errors['email']) {
        setcookie('email_error'.$counter, '', 100000);
        $messages[] = '<div class="error">Email введён некорректно. </div>';
    }
    if ($errors['date_of_birth']) {
        setcookie('date_of_birth_error'.$counter, '', 100000);
        $messages[] = '<div class="error">Укажите дату рождения. </div>';
    }
    if ($errors['gender']) {
        setcookie('gender_error'.$counter, '', 100000);
        $messages[] = '<div class="error">Укажите пол. </div>';
    }
    if ($errors['limbs']) {
        setcookie('limbs_error'.$counter, '', 100000);
        $messages[] = '<div class="error">Укажите количество конечностей. </div>';
    }
    if ($errors['abilities']) {
        setcookie('abilities_error'.$counter, '', 100000);
        $messages[] = '<div class="error">Укажите хотя бы одну способность. </div>';
    }
    
    if ($errors['bio']) {
        setcookie('bio_error'.$counter, '', 100000);
        $messages[] = '<div class="error">Биография заполнена некорректно.</div>';
    }
    
    if ($errors['checkbox']) {
        setcookie('checkbox_error'.$counter, '', 100000);
        $messages[] = '<div class="error">Для продолжения необходимо принять условия контракта.</div>';
    }
    return $messages;
}



