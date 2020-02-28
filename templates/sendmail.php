<?php
// подключаем маилер
require_once(ROOT_PATH . '/vendor/autoload.php');

// коннектимся к базе
$con = mysqli_connect($bdConnectData['bd_path'], $bdConnectData['bd_user'], $bdConnectData['bd_pass'], $bdConnectData['bd_name']);
if(!$con) {
    echo 'Ошибка подключения к MySQL '. mysqli_connect_error();
}
// кодировка
mysqli_set_charset($con, 'utf8');

// текст запроса. выбирает задачи, где разница в днях до "сегодня" 1 или 0 (т.е. сегодня или завтра надо сделать задачу)
$sqlQuery = "SELECT task.id AS id, task.name AS taskName, task.deadline, user.name AS username, user.email AS email
FROM task
JOIN user ON task.user_id = user.id
WHERE (TO_DAYS(task.deadline) - TO_DAYS(NOW())) >= 0 AND (TO_DAYS(task.deadline) - TO_DAYS(NOW())) <= 1 ";

$sqlRes = mysqli_query($con, $sqlQuery);

$emailData = mysqli_fetch_all($sqlRes, MYSQLI_ASSOC);
mysqli_close($con);

// преобразуем массив данных после запроса в удобную нам форму
$resultMailArray = mailDataConverter($emailData);
// выводим получившийся массив
echo '<br><pre>';
print_r($resultMailArray);
echo '</pre>';

foreach ($resultMailArray as $userEmail => $userData) {
  
  try {
    $transport = (new Swift_SmtpTransport('smtp.yandex.ru', 465, 'ssl'))
      ->setUsername('mydeals2020@yandex.ru')
      ->setPassword('xc_1J-25Anb');

    // Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);

    // Create a message
    $message = (new Swift_Message('Пора делать дела!'))
    ->setFrom(['mydeals2020@yandex.ru' => 'MyDeals'])
    ->setTo([$userEmail => $userData['username']])
    ->setBody("Напоминаем, что : \n".$userData['message'])
    ;

    // Send the message
    $numSent = $mailer->send($message);

    printf("Sent %d messages\n", $numSent);
      
  } catch (Exception $e) {
    echo $e->getMessage();
  }
}



?>