<?php
// Файл-генератор страниц системы отзывов
setlocale(LC_ALL, "ru_RU.UTF8");
mb_internal_encoding("UTF-8");
// Подключаем файл необходимый для реализации движка CMS
include_once("workspace/unescaped_file.php");

// Создаём объект, который будет отвечать за автоматическую подгрузку файлов классов
$loaderObjs=new Loader();
ob_start();
// Создаём объект, который будет работать с данными переданными через адресную строку и получаем id страницы отзывов (gId)
$GETInf=new manipulateGETInf();
$GETPage=$GETInf->searchGETElem("gId");
// Проводим соспоставление id страницы с допустимыми значениями
if ($GETPage===false){
	$GETPage="1";
}
// Проверяем id страницы на наличие недопустимых символов (всё кроме цифр)
$checkPattern=preg_match("/[^0-9]/u",$GETPage);
// В случае наличия недопустимых символов формируем и выдаём оповещение, что страница не найдена
if($checkPattern===false || $checkPattern===1){
	header("http/1.1 404 Not Found");
	ErrorsManager::hideErrorByMessage();
}
// Создаём объект функционала системы отзывов и выводим сформированный html-код запрашиваемой (gId) страницы
$htmlCode=new Pre__guestbook($GETPage);
$htmlCode->echoPage();
ob_end_flush();

exit;
?>