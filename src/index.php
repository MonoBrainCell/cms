<?php

setlocale(LC_ALL, "ru_RU.UTF8");
mb_internal_encoding("UTF-8");
// Подключаем файл необходимый для реализации движка CMS
include_once("workspace/unescaped_file.php");

// Создаём объект, который будет отвечать за автоматическую подгрузку файлов классов
$loaderObjs=new Loader();
ob_start();
// Создаём объект, который будет работать с данными переданными через адресную строку и получаем id страницы (pid)
$GETInf=new manipulateGETInf();
$GETPage=$GETInf->searchGETElem("pid");
// Проводим соспоставление id страницы с допустимыми значениями
if ($GETPage===false)
	$GETPage="start";
else {
	// Проверяем id страницы на наличие недопустимых символов (все кроме англ. алфавита, цифр и нижнего подчеркивания)
	$checkPattern=preg_match("/[^a-zA-Z0-9\_]/u",$GETPage);
	// В случае наличия недопустимых символов формируем и выдаём оповещение, что страница не найдена
	if($checkPattern===false || $checkPattern===1){
		header("http/1.1 404 Not Found");
		ErrorsManager::hideErrorByMessage();
	}
}
// Создаём объект движка CMS и выводим сформированный html-код запрашиваемой (pid) страницы
$htmlCode=new Wspc__siteEngine($GETPage);
$htmlCode->echoPage();
ob_end_flush();

exit;
?>