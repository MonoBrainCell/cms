<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
setlocale(LC_ALL, "ru_RU.UTF8");
mb_internal_encoding("UTF-8");
include_once("specials/unescaped_file.php");

$loaderObjs=new Loader();

$auth=new Wspc__Gatekeeper();
$fbdExes=$auth->checkAuth();

if (isset($_POST['phpFileInc'])===true && file_exists($_POST['phpFileInc'])===true){
	if (in_array($_POST['phpFileInc'],$fbdExes,true)===true)
		ErrorsManager::hideAccessError("exe");
	include_once($_POST['phpFileInc']);
}
else 
	echo "Простите, произошла ошибка при обработке отправленных данных. Обновите страницу в браузере и повторите попытку.<span id='ajax_close_button'>Скрыть оповещение</span>";
exit;
?>