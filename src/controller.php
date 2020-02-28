<?php

// определяем имя текущей страницы
// берем пусть до имени скрипта
$thisPath = $_SERVER['SCRIPT_NAME'];
// пилим путь по косой черте
$thisPathArr = explode('/', $thisPath);
// отгрызаем последний элемент массива
$thisFileName = array_pop($thisPathArr);
// пилим его по точке - убираем расширение файла в имени
$thisPathArr = explode('.', $thisFileName);
// тадааааам - имя текущей страницы
$thisPageName = $thisPathArr[0];
// выбираем из массива со страницами текущую
$thisPage = $pages[$thisPageName];

// если имя текущей страницы логаут, то очищаем сессию(разлогиниваемся)
if($thisPageName === 'logout') {
    $_SESSION = [];
    header("Location: index.php");
}

// поменять статус у задачи на "завершена"
if(isset($_GET['taskComplete'])){
    taskCompleter($_GET['taskComplete'], $bdConnectData);
    header("Location: index.php");
}

// проверка на пустую строку поиска на главной
if(isset($_GET['search']) && (strlen(trim($_GET['search'])) == 0)){
    header("Location: index.php");
}

// определяем, авторизован пользователь или нет
$isAuthorized = isset($_SESSION['currentUser']) ? TRUE : FALSE;
// определяем, является ли целевая страница авторизацией или регистрацией
$toReg = ($thisPageName === 'authorization' || $thisPageName === 'registration') ? TRUE : FALSE;

// если пользователь авторизован, или целью явл. рег или авториз, то
if ($isAuthorized || $toReg){
// подключаем нужный обработчик формы, если POST
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // если есть пост запрос, то вызываем обработчик
        include_once(ROOT_PATH . '/src/handlers/' . $thisFileName);
    } else {
        // иначе, если нет пост-запроса, подключаем обычный шаблон
        $pageContent = include_template($thisPage['tpl'], $thisPage['vars']);
    }

} else {
    // если пользователь не авторизован, то выдаем страницу-заглушку для неавторизованных
    $pageContent = include_template('guest.php', []);
}

// впихиваем получившийся контент (пляски с бубном выше) в шаблон с лайаутом
$resultPage = include_template('layout.php', [
    'pageContent' => $pageContent, 
    'currentUser' => $currentUser, 
    'pageName' => $thisPageName
]);

// выводим результат
print($resultPage);
?>