<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
/*[а-яА-Яa-zA-Z0-9\,\!\+\-\_\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/] - полный запрет символов (удалять для разрешения)*/
class App_design_designKeeper extends ErrorsManager {
	const DESIGN_PATH_PREFIX="../design/{SELECTED_DESIGN}/dummy.fbd.html";
	const SETTINGS_FILE="../settings.fbd.php";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к выбору дизайна</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=design&trunk=designManager.php'>Вернуться к функционалу дизайна</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	
	private $dataCheckSettings;
	public function __construct() {}
	
	public function startKeeperEngine(){
		if (file_exists(self::SETTINGS_FILE)===true)
			 include self::SETTINGS_FILE;
		else{
			echo "<span class='error_class'>Файл с основными настройками сайта не найден. Проверьте наличие файла settings.fbd.php в корневной директории сайта.</span>";
			return false;
		}
		if (isset($_POST['design'][0])===false || isset($_POST['submit_redaction'][0])===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$filePath=str_replace("{SELECTED_DESIGN}",$_POST['design'][0],self::DESIGN_PATH_PREFIX);
		if (file_exists($filePath)===false){
			echo "<span class='error_class'>Дизайн выбранный Вами не найден!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$content="<?php\r\n".
		"// Программное Обеспечение для управления сайтом 'Adept Illusion'\r\n".
		"// Данное ПО является открытым для его изменения и использования\r\n".
		"\r\n".
		"\$siteEngineSwitcher='".$siteEngineSwitcher."';\r\n".
		"\$selectedDesign='".$_POST['design'][0]."';\r\n".
		"\$functional='".$functional."';\r\n".
		"\r\n?>";
		self::reloadCodeFile($content,self::SETTINGS_FILE);
		echo "<span class='success_class'>Изменение дизайна сайта сохранено.</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
	
	private function reloadCodeFile($fileCode,$path) {
		if (@file_put_contents($path,$fileCode)===false){
			echo "<span class='error_class'>Произошёл сбой при записи кода шаблона выбранного дизайна! Проверьте права данные файлам в папке {$path} на возможность их перезаписи.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
}
?>