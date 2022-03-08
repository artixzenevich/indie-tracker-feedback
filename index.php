<?php
//require_once 'vendor/autoload.php';

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

// Для деплоя на хероку такой способ не подходит
//$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
//$dotenv->load();

$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$text = $_POST['mail-text'];

$title = $subject;
$body = "
<h2>$title</h2>
<b>Имя:</b> $name<br>
<b>Почта:</b> $email<br><br>
<b>Сообщение:</b><br>$text
";

$mail = new PHPMailer\PHPMailer\PHPMailer();
try {
    $mail->isSMTP();   
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth   = true;
    //$mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};

    // Настройки вашей почты
    $mail->Host       = 'smtp.yandex.ru'; // SMTP сервера вашей почты
    $mail->Username   = getenv('USERNAME'); // Логин на почте
    $mail->Password   = getenv('PASSWORD'); // Пароль на почте
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;
    $mail->setFrom(getenv('EMAIL'), $name); // Адрес самой почты и имя отправителя

    // Получатель письма
    $mail->addAddress(getenv('EMAIL'));  
 

    $mail->isHTML(true);
    $mail->Subject = $title;
    $mail->Body = $body;    

    // Проверяем отравленность сообщения
    if ($mail->send()) {$result = "success";} 
    else {$result = "error";}
} catch (Exception $e) {
    $result = "error";
    $status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
}

// Отображение результата
// echo json_encode(["result" => $result, "resultfile" => $rfile, "status" => $status]);