<?php

//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);


$errortext = "asfasf";
$place = "Нет";
$fil = "Нет";
$name = " ";
$numbercard = " ";
$true_false_rule = "Нет";
$true_false_card = "Нет";
$true_false_crtus = "Нет";
$true_false_directum = "Нет";
$true_false_domen = "Нет";
$true_false_galaxy = "Нет";

if(isset($_POST["myInputname"]))
{
    $name = htmlentities($_POST["myInputname"]);   
}
if($name == "")
    {
        include ('index.php');
        echo "<script>alert('Введите ФИО сотрудника')</script>"; 
    }
if(isset($_POST["numbercard"]))
{
    $numbercard = htmlentities($_POST["numbercard"]);    
}
if(isset($_POST["pоsition"]))
{
    $place = htmlentities($_POST["position"]);
    
}
if(isset($_POST["filial"]))
{
    $fil = htmlentities($_POST["filial"]);
    //print_r($fil);
}
if(isset($_POST['certus']) == 'yes') 
{
    $true_false_crtus = "Да";
}
if(isset($_POST['directum']) == 'yes') 
{
    $true_false_directum = "Да";
}
 if(isset($_POST['domen']) == 'yes') 
{
    $true_false_domen = "Да";
}
 if(isset($_POST['galaxy']) == 'yes') 
{
    $true_false_galaxy = "Да";
}

 if(isset($_POST['rule']) == 'yes') 
{
    $true_false_rule = "Да";
}
if(isset($_POST['card']) == 'yes') 
{
    $true_false_card = "Да";
}


$message = '
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
.table {
	width: 100%;
	margin-bottom: 20px;
	border: 1px solid #dddddd;
	border-collapse: collapse; 
}
.table th {
	font-weight: bold;
	padding: 5px;
	background: #efefef;
	border: 1px solid #dddddd;
}
.table td {
	border: 1px solid #dddddd;
	padding: 5px;
}
</style>
</head>
<body>
<table class="table">
	<thead>
		<tr>
            <th>ФИО</th>
            <th>Подразделение</th>
            <th>Филиал</th>
			<th>Certus+</th>
			<th>Галактика</th>
			<th>Директум</th>
			<th>Домен</th>
            <th>Карта-пропуск</th>
            <th>Номер карты</th>
			<th>Правила страхования</th>
		</tr>
	</thead>
	<tbody>
		<tr>
            <td>'.$name.'</td>
            <td>'.$place.'</td>
            <td>'.$fil.'</td>
			<td>'.$true_false_crtus.'</td>
            <td>'.$true_false_galaxy.'</td>	
			<td>'.$true_false_directum.'</td>
			<td>'.$true_false_domen.'</td>
            <td>'.$true_false_card.'</td>
            <td>'.$numbercard.'</td>
            <td>'.$true_false_rule.'</td>
		</tr>
	</tbody>
</table>
</body>';
//Дело в том, что тема письма должна быть записана следующим образом: =?<кодировка>?B?<текст в base64>?= https://pavelk.ru/otpravka-pochty-s-kartinkoj-php-mail_mime/
function header_encode($str, $data_charset, $send_charset)
{
    if ($data_charset != $send_charset) {
        $str = iconv($data_charset, $send_charset . '//IGNORE', $str); //-- при необходимости изменим кодировку самого текста
    }
    return ('=?' . $send_charset . '?B?' . base64_encode($str) . '?=');
}

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/var/www/spr/PHPMailer/src/Exception.php';
require '/var/www/spr/PHPMailer/src/PHPMailer.php';
require '/var/www/spr/PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true); // Passing `true` enables exceptions
$mail->CharSet = 'UTF-8';


//Server settings
$mail->SMTPDebug = 2; // Enable verbose debug output
$mail->isSMTP(); // Set mailer to use SMTP
$mail->Host = 'imap.bns.by'; // Specify main and backup SMTP servers
$mail->SMTPAuth = true; // Enable SMTP authentication
$mail->Username = 'bns\911'; // SMTP username
$mail->Password = '$$$'; // SMTP password
$mail->SMTPSecure = 'tls'; // Enable SSL encryption, TLS also accepted with port 465
$mail->Port = 587; // TCP port to connect to
$mail->setFrom('911@bns.by', 'infoportal@bns.by');
if(isset($_POST['certus']) == 'yes') 
{
    $mailperson = "$$$";
    $temamail = 'Обходной лист';
    $text_mail = $message;
    $mail->addAddress($mailperson); // Add a recipient address
    //$mail->addAddress($row['a.yushin@bns.by']); // Add a recipient address
    $tema = $temamail;
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = header_encode($tema, "UTF-8", "UTF-8");
    $mail->Body = $text_mail;
   // $mail->Subject = $Subject;
//$mail->Body    = $Body;
$mail->IsHTML(true);   
    try {
        $mail->send();
        $mail->clearAddresses();
    } catch (Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}
if(isset($_POST['directum']) == 'yes' || isset($_POST['card']) == 'yes' || isset($_POST['rule']) == 'yes' || isset($_POST['galaxy']) == 'yes') 
{
    if(isset($_POST['card']) == 'yes' && $numbercard == "")
    {
        include ('index.php');
        echo "<script>alert('Введите номер карты-пропуска')</script>"; 
    }
    else
    {
        $mailperson = "$$$$";
        $temamail = 'Обходной лист';
        $text_mail = $message;
        $mail->addAddress($mailperson); // Add a recipient address
        //$mail->addAddress($row['a.yushin@bns.by']); // Add a recipient address
        $tema = $temamail;
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = header_encode($tema, "UTF-8", "UTF-8");
        $mail->Body = $text_mail;
        try {
            $mail->send();
            $mail->clearAddresses();
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }
   
}
if(isset($_POST['domen']) == 'yes') 
{
    $mailperson = "$$$";
    $temamail = 'Обходной лист';
    $text_mail = $message;
    $mail->addAddress($mailperson); // Add a recipient address
    //$mail->addAddress($row['a.yushin@bns.by']); // Add a recipient address
    $tema = $temamail;
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = header_encode($tema, "UTF-8", "UTF-8");
    $mail->Body = $text_mail;
    try {
        $mail->send();
        $mail->clearAddresses();
    } catch (Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}

?>