<?php
// Файл использующийся для реализации функционала предоставления файлов для скачивания с сайта
setlocale(LC_ALL, "ru_RU.UTF8");
mb_internal_encoding("UTF-8");
// Подключаем файл необходимый для реализации текущего функционала
include_once("workspace/unescaped_file.php");

$path="files/share/"; // Путь до файлов, которые можно скачать с сайта
$settingsFile="settings.fbd.php";// Путь до файла настроек CMS

// Проверяем наличие файла с настройками(settingsFile)
if (file_exists($settingsFile)===false)
	ErrorsManager::hideErrorByMessage();
// В файле настроек(settingsFile) существуют 3 переменные:
//	siteEngineSwitcher - определяет включен ли движок CMS
//	selectedDesign - имя текущей темы оформления страниц сайта
//	functional - сериализованный массив, хранящий список всех классов и методов, задействованных в формировании контента страниц сайта
include $settingsFile;
// Проверяем наличие и значение переменной, отвечающей за состояние движка CMS; если её нет ИЛИ значение указывает на выключенный движок - прерываем скрипт
if (isset($siteEngineSwitcher)===true && $siteEngineSwitcher=="off"){
	echo "<h1>Сайт закрыт на реконструкцию</h1>";
	exit;
}
// Проверяем наличие, отвечающей за состояние движка CMS, а также переменной, отвечающей за функционал CMS; если хотя бы одной из них нет, то выводим ошибку
else if (isset($siteEngineSwitcher)===false || isset($functional)===false)
	ErrorsManager::hideErrorByMessage();

// Конвертируем сериализованную строку со списком классов и методов(functional) в массив(functionalArr)	
$functional=stripslashes($functional);
$functionalArr=@unserialize($functional);
if ($functionalArr===false)
	ErrorsManager::hideErrorByMessage();
// Обходим часть списка классов и методов, отвечающих за основной контент CMS и ищем класс, отвечающий за функционал предоставления файлов для скачивания(Pre__filesShare)
for ($a=0,$b=count($functionalArr['mainEngineSearch']);$a<$b;$a++){
	if ($functionalArr['mainEngineSearch'][$a]['className']=='Pre__filesShare'){
		// Обходим список всех методов, найденного класса(Pre__filesShare) и ищем метод отвечающий за вывод файла на скачивание(getFilesLists)
		for ($c=0,$d=count($functionalArr['mainEngineSearch'][$a]['ramification']);$c<$d;$c++){
			if ($functionalArr['mainEngineSearch'][$a]['ramification'][$c]['methodName']=="getFilesLists"){
				// Проверяем состояние данного функционала(вкл/выкл); если вкл - устанавливаем переменную-флаг, указывающую, что метод готов к реализации(catched)
				if ($functionalArr['mainEngineSearch'][$a]['ramification'][$c]['engineSwitcher']=="off"){
					echo "<h1>Данный функционал временно недоступен!</h1>";
					exit;
				}
				else
					$catched=true;
			}
		}
	}
}
// Проверяем отсутствует ли переменная-флаг, показывающая, что метод выгрузки файлов готов к использованию
if (isset($catched)===false){
	echo "<h1>Данный функционал временно недоступен!</h1>";
	exit;
}
// Создаём объект, который будет работать с данными переданными через адресную строку и получаем имя файла, который необходимо предоставить (file)
$GETInf=new manipulateGETInf();
$GETFile=$GETInf->searchGETElem("file");
// Проверяем удалось ли получить значение указанного ранее поля(file); если не удалось - останавливаем скрипт
if ($GETFile===false){
	echo "<h1>Был передан неверный параметр для скачивания файла</h1>";
	exit;
}
// Проверяем имя файла на наличие недопустимых символов (все кроме англ. алфавита, цифр, нижнего подчеркивания и точки)
$checkPattern=preg_match("/[^a-zA-Z0-9\_\.]/u",$GETFile);
// В случае наличия недопустимых символов формируем и выдаём оповещение об ошибке
if ($checkPattern===false || $checkPattern===1){
	echo "<h1>Был передан неверный параметр для скачивания файла</h1>";
	exit;
}
// Проверяем наличие файла, который нужно отдать на скачивание
if (file_exists($path.$GETFile)===true){
	// Передаем в браузер пользователя заголовок о том, что передаваемые ниже данные являются бинарными, которые возможно открыть только при помощи отдельного приложения, отсуствующего в браузере
	header("Content-Type: application/octet-stream");
	// Передаем в браузер заголовок, о том, что следующий контент является скачиваемым, а также указываем имя файла, который будет предложен для скачивания
	header("Content-Disposition: Attachment; filename=".$GETFile);
	// Получаем вест контент файла, который необходимо предоставить
	$content=file_get_contents($path.$GETFile);
	// Выводим полученный контента файла в документ
	echo $content;
	exit;
}
else {
	echo "<h1>Запрашиваемого Вами файла не существует.</h1>";
	exit;
}
?>