<?php

// echo '<br> include functions.php';

function projectsInTaskListCount($taskList, $projectName){
    $projectCount = 0;
    foreach ($taskList as $task) {
        if ($task['category'] === $projectName) {
            $projectCount++;
        }
    }
    return $projectCount;
}


function include_template($name, $data){
    $name = 'templates/' . $name;
    $result = '';

    if(!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;

}

function deadLineLeftHours($deadLine) {
    if ($deadLine === null) {
        return 'noDeadline';
    }
    $deadLineDate = strtotime($deadLine);
    $nowDate = strtotime('today');
    $diffTime = $deadLineDate - $nowDate;
    $secsInHour = 3600;

    return floor($diffTime / $secsInHour);
}

// функция проверяет есть ли такой id в базе с проектами
function projectIdCheck($projectId, $projectList){
    foreach ($projectList as $project) {
       if ($project['id'] === $projectId) {
           return TRUE;
       }
    }
    return FALSE;
}



function db_get_prepare_stmt($con, $sql, $data = []) {
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($con);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($con) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($con);
            die($errorMsg);
        }
    }

    return $stmt;
}

function getPostVal($name){
    return $_POST[$name] ?? "";
}

function deadlineFieldValidation($date){
    $todayDate = date("Y-m-d");
    if(isset($date) && $date < $todayDate) {
       return 'Ошибка даты. Дедлайн уже прошел';
      }
}


function emailValidation($email){

}


function deadlineFilter($taskDeadlineDate, $filterTab){
   
    $result = false;
    $filterTab = ($filterTab) ? intval($filterTab) : 1;
    $today = time();
    $deadline = strtotime($taskDeadlineDate);
    
    
    switch($filterTab) {
        case 1:
            $result = TRUE;
            break;
        case 2: 
            if(strtotime('today') == $deadline){
                $result = TRUE;
            } 
            break;
        case 3:
            if(strtotime('tomorrow') == $deadline){
                $result = TRUE;
            }
            break;
        case 4:
            if(strtotime('today') > $deadline && $deadline != NULL){
                $result = TRUE;
            }
            break;
    }

    return $result;
}


function taskCompleter($taskId, $bdConnectData){
    $con = mysqli_connect($bdConnectData['bd_path'], $bdConnectData['bd_user'], $bdConnectData['bd_pass'], $bdConnectData['bd_name']);
    mysqli_set_charset($con, 'utf8');
    $query = 'UPDATE `task` SET `status` = 1 WHERE `id` ='.$taskId;
    $sqlRes = mysqli_query($con, $query);
    // $reas = mysqli_fetch($sqlRes);
    mysqli_close($con);
}

function mailDataConverter($dataArr){
    $result = Array();
    foreach ($dataArr as $taskArr) {
        $taskName = $taskArr['taskName'];
        $userName = $taskArr['username'];
        $deadline = $taskArr['deadline'];
        $email = $taskArr['email'];
        $result[$email]['username'] = $userName;
        $result[$email]['message'] = (isset($result[$email]['message'])) ?  $result[$email]['message'].", задача \"$taskName\" должна быть сделана $deadline" : "задача  \"$taskName\" должна быть сделана $deadline";
    }
    return $result;
    // макетик массива $result, чтоб не забыть
    // $result = array(
    //     "email1" => array(
    //         'username' => 'name1',
    //         'message' => 'тут сообщение о задачах1',
    //     ),
    //     "email2" => array(
    //         'username' => 'name2',
    //         'message' => 'тут сообщение о задачах2',
    //     ),
    
    // )
}


?>