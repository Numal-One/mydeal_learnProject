<?php

// массив для передачи переменных в шаблон.
$dataToTemplate = $thisPage['vars'];

// тащим данные из массива $_POST 
// ---------------------------------------------------------------------------
  

  $formNameValue = $_POST['name'];
  $formProjectValue = $_POST['project'] ?? [];
  // если в поле не выбрана дата, то дата равна NULL
  $formDeadlineValue = ($_POST['date']) ? $_POST['date'] : NULL;
  // текущая дата
  $todayDate = date("Y-m-d");
  
  // ищем id выбранного проекта в массиве с проектами
  $formProjectId = NULL;
  foreach ($projectList as $value) {
    if ($value['name'] !== $formProjectValue) {
      continue;
    }
    $formProjectId = $value['id'];
    break;
  }

  //  валидация формы
  // ------------------------------------------------------------------------------

  // из обязательных в этом случае только name. project добавим, как защиту от дурака,
  // а date должна быть корректна, она должна быть "из будущего".
  $requered_fields = ['name', 'project', 'date'];
  $errors = [];
  $isValidate = FALSE;

  foreach ($requered_fields as $field) {
    // проверка на корректность даты
    // если это поле дата, и оно не пустое, и оно "меньше" текущей даты, то
    if($field === 'date' && !empty($_POST[$field]) && $formDeadlineValue < $todayDate) {
      $errors[$field] = 'Не корректная дата';
      continue;
    } 
    if(empty($_POST[$field]) && $field !== 'date'){
      $errors[$field] = 'Поле не заполнено';
    }
  }

  // если после валидации есть ошибки, т.е. массив с ошибками не пуст, то
  if(count($errors)) {
    // добавляем массив с ошибками в массив с данными для формы.
    $dataToTemplate['formErrors'] = $errors;
  } else {
    $isValidate = TRUE;
  }

  //  после валидации, если она удачна, работаем с файлом
  // ------------------------------------------------------------------------------
  // определяем переменную с путем до файла
  // дефолтное - NULL
  $file_url = NULL;
  
  if ($isValidate && isset($_FILES['file']) && strlen($_FILES['file']['name']) > 0) {
    // сгенерируем уникальное имя для сохраненного файла
    // разобьем имя файла из формы (который загрузили) на массив со строками. 
    // Разделителем будет точка (для выделения расширения файла)
    $file_name = explode( '.', $_FILES['file']['name']);
    // сгенерируем имя "прибавим" расширение оригинального файла
    $file_name = 'task_file_'.str_replace(' ', '_', $formNameValue).'_'.uniqid().'.'.$file_name[1];
    // используется для сохранения файла
    $file_path = ROOT_PATH . '/uploads/';
    // используется для записи в БД
    $file_url = '/uploads/' . $file_name;
    
    move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);
  }

  // далее следует код сохранения таска в БД. Выполняется только если валидация пройдена
  // -----------------------------------------------------------------------------------------
  if($isValidate) {
    // для безопасности преобразуем строку
    $formNameValue = htmlspecialchars($formNameValue);
    // формируем массив с данными для передачи в БД
    $dataArray = [$formProjectId, $currentUser['id'], $formNameValue, $formDeadlineValue, $file_url];
    // коннектимся к БД
    $con = mysqli_connect($bdConnectData['bd_path'], $bdConnectData['bd_user'], $bdConnectData['bd_pass'], $bdConnectData['bd_name']);
    mysqli_set_charset($con, 'utf8');
    // Строка запроса
    $sql = "INSERT INTO `task` (project_id, user_id, name, creation_date, deadline, status, file) VALUES (?, ?, ?, NOW(), ?, 0, ?)";
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