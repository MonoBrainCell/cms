<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class siteEngineStatus extends ErrorsManager {
	const FILE_OF_SETTINGS="../settings.fbd.php";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к изменению функционала сайта</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=settings&trunk=settingsManager.php'>Вернуться к настройкам</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	public function __construct(){
		if (file_exists(self::FILE_OF_SETTINGS)===false){
			echo "<span class='error_class'>Не найден файл с рабочими настройками сайта! Попробуйте открыть веб-сайт, если это сделать не получится - обратитесь к Хостинг-Провайдеру для произведения BackUp'а сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (isset($_POST['site_main_engine'])===false || isset($_POST['submit_action'])===false
		||
		($_POST['site_main_engine']!="on" && $_POST['site_main_engine']!="off")
		){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	public function saveStatus(){
		
		include self::FILE_OF_SETTINGS;
		
		$newContent="<?php\r\n".
		"// Программное Обеспечение для управления сайтом 'Adept Illusion'\r\n".
		"// Данное ПО является открытым для его изменения и использования\r\n".
		"\r\n".
		"\$siteEngineSwitcher='".$_POST['site_main_engine']."';\r\n".
		"\$selectedDesign='".$selectedDesign."';\r\n".
		"\$functional='".$functional."';\r\n".
		"\r\n?>";
		if (@file_put_contents(self::FILE_OF_SETTINGS,$newContent)===false){
			echo "<span class='error_class'>Произошёл сбой при записи рабочих настроек сайта! Попробуйте открыть веб-сайт, если попытка окажется неудачной - обратитесь к хостинг-провайдеру для проведения BackUp'a сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		echo "<span class='success_class'>Изменения сохранены.</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
}
$varObj=new siteEngineStatus();
$varObj->saveStatus();
?>