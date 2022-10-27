<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
if (file_exists("specials/unescaped_file.php")===false){
	echo "<h1>Не найден файл unescaped_file.php</h1><p>Пожалуйста закажите backUp сервера или (если у Вас есть резервная копия сайта) закачайте данный файл в директорию office &frasl; specials</p>";
	exit;
}
require_once("specials/unescaped_file.php");//passw: jrn82L2lZc
$loaderObjs=new Loader();
$auth=new Wspc__Gatekeeper();

if (isset($_SESSION['master'])===false){
	include "specials/form_to_enter.php";
	echo $enterContent;
	exit;
}
else {
	echo "<meta http-equiv='refresh' content='0; url=index.php'>";
	exit;
}
?>