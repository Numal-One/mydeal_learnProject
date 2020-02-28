<?php
// авторизован ли пользователь
$isAuthorized = isset($_SESSION['currentUser']) ? TRUE : FALSE;


// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

// фильтр задач
if(isset($_GET['taskFilter']) && $_GET['taskFilter'] > 0 && $_GET['taskFilter'] < 5) {
  $taskFilter = $_GET['taskFilter'];
  // header("Location: index.php");
} else {
  $taskFilter = 1;
}

// для локального
$bdConnectData = array(
  'bd_path' => 'localhost',
  'bd_user' => 'root',
  'bd_pass' => '',
  'bd_name' => 'mydealsDB'
);

// для сайта
// $bdConnectData = array(
//   'bd_path' => 'localhost',
//   'bd_user' => 'u0857553_root',
//   'bd_pass' => 'U0l7D5q1',
//   'bd_name' => 'u0857553_mydealsdb'
// );

// если не авторизован пользователь, то...
$currentUser = array(
  'id' => '',
  'name' => 'Гость',
  'email' => '',
  'password' => '',
  'reg_date' => ''
);

$projectList = [];
$taskList = [];



if ($isAuthorized) {
  $currentUser = $_SESSION['currentUser'];

  $con = mysqli_connect($bdConnectData['bd_path'], $bdConnectData['bd_user'], $bdConnectData['bd_pass'], $bdConnectData['bd_name']);

  if(!$con) {
      echo 'Ошибка подключения к MySQL '. mysqli_connect_error();
  }

  mysqli_set_charset($con, 'utf8');
  // запрос списка проектов
  
  $sqlRes = mysqli_query($con, 'SELECT `id`, `name` FROM `project` WHERE `user_id` = '.$currentUser['id'].' ORDER BY `name` ');
  $projectList = mysqli_fetch_all($sqlRes, MYSQLI_ASSOC);
  
  // запрос списка задач
  $sqlSearchAdd = '';
  $searchKey = '';
  if(isset($_GET['search'])){
    $searchKey = $_GET['search'];
    $sqlSearchAdd = " AND MATCH (task.name) AGAINST ('".$searchKey."')";
  } 

  $sqlQuery = "SELECT project.id AS categoryId, task.id AS taskId, task.name, task.deadline, project.name AS category, task.status AS isComplete, task.file AS `file`
    FROM `task` 
    JOIN `project` ON task.project_id = project.id 
    WHERE task.user_id = '".$currentUser['id']."'".$sqlSearchAdd." 
    ORDER BY task.status ASC, task.name ASC";

  $sqlRes = mysqli_query($con, $sqlQuery);
  
  $taskList = mysqli_fetch_all($sqlRes, MYSQLI_ASSOC);
  
  mysqli_close($con);
}


$pages = array(
  'index' => array(
    'url_key' => '/index.php',
    'tpl' => 'main.php',
    'vars' => array(
      'pageTitle' => 'Главная страница',
      'show_complete_tasks' => $show_complete_tasks, 
      'projectList' => $projectList, 
      'taskList' => $taskList,
      'currentUser' => $currentUser,
      'bdConnectData' => $bdConnectData,
      'taskFilter' => $taskFilter
    )
  ),
  'add' => array(
    'url_key' => '/add.php',
    'tpl' => 'add.php',
    'vars' => array(
      'pageTitle' => 'Добавить задачу',
      'show_complete_tasks' => $show_complete_tasks, 
      'projectList' => $projectList, 
      'taskList' => $taskList,
      'bdConnectData' => $bdConnectData,
      'currentUser' => $currentUser
    )
  ),
  'registration' => array(
    'url_key' => '/registration.php',
    'tpl' => 'registration.php',
    'vars' => array(
      'bdConnectData' => $bdConnectData,
      'pageTitle' => 'Регистрация',
      
    )
  ),
  'authorization' => array(
    'url_key' => '/authorization.php',
    'tpl' => 'authorization.php',
    'vars' => array(
      'bdConnectData' => $bdConnectData,
      'pageTitle' => 'Авторизация',
      
    )
  ),
  'guest' => array(
    'url_key' => '/guest.php',
    'tpl' => 'guest.php',
    'vars' => array(
      'bdConnectData' => $bdConnectData,
      'pageTitle' => 'Гость',
      
    )
  ),
  'logout' => array(
    'url_key' => '/logout.php',
    'tpl' => 'logout.php',
    'vars' => array(
      'bdConnectData' => $bdConnectData,
      'pageTitle' => 'Logout',
      
    )
  ),
  'addproject' => array(
    'url_key' => '/addproject.php',
    'tpl' => 'addproject.php',
    'vars' => array(
      'pageTitle' => 'addproject',
      'projectList' => $projectList, 
      'taskList' => $taskList,
      'bdConnectData' => $bdConnectData,
      'currentUser' => $currentUser
    )
  ),
  'sendmail' => array(
    'url_key' => '/sendmail.php',
    'tpl' => 'sendmail.php',
    'vars' => array(
      'pageTitle' => 'sendmail',
      'projectList' => $projectList, 
      'taskList' => $taskList,
      'bdConnectData' => $bdConnectData,
      'currentUser' => $currentUser
    )
  )
); 
  

// $projectList = array('Входящие','Учеба','Работа','Домашние дела','Авто');

// $taskList = array(
//     array (
//         'name' => 'Собеседование в IT компании',
//         'deadline' =>'03.02.2020',
//         'category' => 'Работа',
//         'isComplete' => false
//     ),
//     array (
//         'name' => 'Выполнить тестовое задание',
//         'deadline' =>'01.02.2020',
//         'category' => 'Работа',
//         'isComplete' => false
//     ),
//     array (
//         'name' => 'Сделать задание первого раздела',
//         'deadline' =>'17.01.2020',
//         'category' => 'Учеба',
//         'isComplete' => true
//     ),
//     array (
//         'name' => 'Встреча с другом',
//         'deadline' =>'17.01.2020',
//         'category' => 'Входящие',
//         'isComplete' => false
//     ),
//     array (
//         'name' => 'Купить корм для кота',
//         'deadline' => '15.01.2020',
//         'category' => 'Домашние дела',
//         'isComplete' => false
//     ),
//     array (
//         'name' => 'Заказать пиццу',
//         'deadline' => null,
//         'category' => 'Домашние дела',
//         'isComplete' => false
//     )

// );


?>