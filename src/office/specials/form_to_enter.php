<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
$baseHtml=<<<EOT
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Вход в административную часть Веб-сайта</title>
<base href="http://{$_SERVER['HTTP_HOST']}/">
<script type="text/javascript" src="office/jq_libs/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="office/specials/jss/fields_manager.js"></script>
<link type="text/css" rel="stylesheet" href="office/specials/html-dummyes/style.css">
</head>
<body>
<h1>Пожалуйста, пройдите процедуру авторизации</h1>
{EDITED_PART}
</body>
</html>
EOT;
if (isset($_POST['send_datas'])===true) {
	$auth->checkAdmission($_POST['login'],$_POST['pasw']);
}
$enterContent=<<<EOT
<div class='integral_content'>
<span class="message">{MESSAGE}</span>
<form name='enter_to_admin'	method='post' action='office/enter.php'>
<div class='left_part'>
<span>
Введите Логин:<sup>Используются только латинические буквы, числа и символы <q>_</q>, <q>-</q>, <q>#</q>, <q>!</q>, <q>@</q></sup>
</span>
<input type='password' id='login' name='login' maxlength='15'>
<span class='warning_field' id='login_symbols_warning'></span>
<span class='warning_field' id='login_length_warning'></span>
</div>
<div class='right_part'>
<span>
Введите Пароль: <sup>Используются только латинические буквы, числа и символы <q>_</q>, <q>-</q>, <q>#</q>, <q>!</q>, <q>@</q></sup>
</span>
<input type='password' id='pasw' name='pasw' maxlength='25'>
<span class='warning_field' id='pasw_symbols_warning'></span>
<span class='warning_field' id='pasw_length_warning'></span>
</div>
<input type='submit' name='send_datas' value='Идентификация'>
</form>
</div>
EOT;

$enterContent=str_replace("{EDITED_PART}",$enterContent,$baseHtml);
if (isset($_GET['message'])===true){
	switch ($_GET['message']){
		case 'failed_id_datas':
			$message='Приносим свои извинения, Ваш доступ был аннулирован системой. Пожалуйста повторите процедуру авторизации.';
		break;
		case 'output_produced':
			$message='Наблюдается странное поведение Вашего доступа. Пожалуйста повторите процедуру авторизации!';
		break;
		case 'attempt_to_enter_failed':
			$message='Вы ввели неверное сочетание ЛОГИН - ПАРОЛЬ. Повторите попытку.';
		break;
		default:
			$message="";
		break;
	}
}
else {
	$message="";
}
$enterContent=str_replace("{MESSAGE}",$message,$enterContent);
?>
