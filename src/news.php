<?php
// Файл-генератор новостных страниц
setlocale(LC_ALL, "ru_RU.UTF8");
mb_internal_encoding("UTF-8");
// Подключаем файл необходимый для реализации движка CMS
include_once("workspace/unescaped_file.php");

// Создаём объект, который будет отвечать за автоматическую подгрузку файлов классов
$loaderObjs=new Loader();
ob_start();
// Создаём объект, который будет работать с данными переданными через адресную строку и пытаемся получить id страницы новостей(gId) и номер отдельной новости(article)
$GETInf=new manipulateGETInf();
$GETPage=$GETInf->searchGETElem("gId");
$GETArticle=$GETInf->searchGETElem("article");
// Если в адресной строке не были указаны ни id страницы новостей, ни номер новости, то считаем, что была запрошена первая страница списка новостей
if ($GETPage===false && $GETArticle===false){
	$type="news"; $num="1";
}
// Если в адресной строке не был указан id страницы новостей, но указан номер новости, то считаем, что была запрошена определённая новость
else if ($GETPage===false && $GETArticle!==false){
	$type="article"; $num=$GETArticle;
}
// В остальных случаях считаем что была запрошена определённая страница списка новостей
else {
	$type="news"; $num=$GETPage;
}
// Проверяем id новости/страницы списка новостей на наличие недопустимых символов (всё кроме цифр)
$checkPattern=preg_match("/[^0-9]/u",$num);
// В случае наличия недопустимых символов формируем и выдаём оповещение, что страница не найдена
if($checkPattern===false || $checkPattern===1){
	header("http/1.1 404 Not Found");
	ErrorsManager::hideErrorByMessage();
}
// Создаём объект функционала новостей и выводим сформированный html-код запрашиваемой страницы, основываясь на типе ресурса(type) и id ресурса(num)
$htmlCode=new Pre__news($type,$num);
$htmlCode->echoPage();
ob_end_flush();

exit;
?>