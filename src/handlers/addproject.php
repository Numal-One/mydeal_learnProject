<?php

// массив для передачи переменных в шаблон.
$dataToTemplate = $thisPage['vars'];

// тащим данные из массива $_POST 
// ---------------------------------------------------------------------------
  

  $formNameValue = $_POST['name'];
  
  // ищем id выбранного проекта в массиве с проектами
  $formProjectId = NULL;
  foreach ($projectList as $value) {
    if ($value['name'] !== $formNameValue) {
      continue;
    }
    $formProjectId = $value['id'];
    break;
  }

  //  валидация формы
  // ------------------------------------------------------------------------------
  $isValidate = FALSE;
  $errors = [];

  if ($formProjectId){
    $errors['name'] = 'Такой проект уже есть';
  }

  // если после валидации есть ошибки, т.е. массив с ошибками не пуст, то
  if(count($errors)) {
    // добавляем массив с ошибками в массив с данными для формы.
    $dataToTemplate['formErrors'] = $errors;
  } else {
    $isValidate = TRUE;
  }

  // далее следует код сохранения таска в БД. Выполняется только если валидация пройдена
  // -----------------------------------------------------------------------------------------
  if($isValidate) {
    // для безопасности преобразуем строку
    $formNameValue = htmlspecialchars($formNameValue);
    // формируем массив с данными для передачи в БД
    $dataArray = [$formNameValue, $currentUser['id']];
    // коннектимся к БД
    $con = mysqli_connect($bdConnectData['bd_path'], $bdConnectData['bd_user'], $bdConnectData['bd_pass'], $bdConnectData['bd_name']);
    mysqli_set_charset($con, 'utf8');
    // Строка запроса
    $sql = "INSERT INTO `project` (name, user_id) VALUES (?, ?)";
    // используем функцию для подготовки запроса
    $stmt = db_get_prepare_stmt($con, $sql, $dataArray);
    // выполняем получившееся выражение
    $result = mysqli_stmt_execute($stmt);
    // закрываем коннект
    mysqli_close($con);

    // Если все хорошо с сохранением в БД, то
    if($result) {
      // ошибки нет, показываем главную страницу
      header("Location: index.php");
    } else {
      // ошибка есть
      echo 'Ошибка сохранения данных';
    }
  } 

  // если валидация не пройдена, то в массивe с данными будет массив с ошибками $dataToTemplate['formErrors']
  $pageContent = include_template($thisPage['tpl'], $dataToTemplate);
  

  



  
  
?>