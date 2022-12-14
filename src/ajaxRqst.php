<?php
// Файл используется для реализации ответов с сервера на AJAX-запросы
setlocale(LC_ALL, "ru_RU.UTF8");
mb_internal_encoding("UTF-8");
// Подключаем файл необходимый для реализации текущего функционала
include_once("workspace/unescaped_file.php");
// Создаём объект, который будет отвечать за автоматическую подгрузку файлов классов
$loaderObjs=new Loader();

// Проверяем наличие поля, в котором передаётся путь до обработчика, который должен принимать данные(['phpFileInc']) и наличие файла, который указан в этом поле
if (isset($_POST['phpFileInc'])===true && file_exists($_POST['phpFileInc'])===true)
	// Подключаем в текущий скрипт указанный файл-обработчик для AJAX-запроса
	include_once($_POST['phpFileInc']);
else 
	echo "Простите, произошла ошибка при обработке отправленных данных. Обновите страницу в браузере и повторите попытку.<span id='ajax_close_button'>Скрыть оповещение</span>";
exit;
?>