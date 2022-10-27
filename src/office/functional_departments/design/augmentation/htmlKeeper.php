<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
/*[а-яА-Яa-zA-Z0-9\,\!\+\-\_\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/] - полный запрет символов (удалять для разрешения)*/
class App_design_htmlKeeper extends ErrorsManager {
	const DESIGN_PATH_PREFIX="../design/{SELECTED_DESIGN}/dummy.fbd.html";
	const SETTINGS_FILE="../settings.fbd.php";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к редактированию html-кода</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=design&trunk=designManager.php'>Вернуться к функционалу дизайна</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	
	private $dataCheckSettings;
	public function __construct() {}
	
	public function startKeeperEngine(){
		if (file_exists(self::SETTINGS_FILE)===true)
			 include self::SETTINGS_FILE;
		else{
			echo "<span class='error_class'>Файл с основными настройками сайта не найден. Проверьте наличие файла settings.fbd.php в корневной директории сайта.</span>";
			return false;
		}
		if (isset($_POST['html_code'][0])===false || isset($_POST['submit_redaction'][0])===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$htmlFile=str_replace("{SELECTED_DESIGN}",$selectedDesign,self::DESIGN_PATH_PREFIX);
		self::reloadCodeFile($_POST['html_code'][0],$htmlFile);
		echo "<span class='success_class'>Html-код шаблона сохранен.</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
	
	private function reloadCodeFile($fileCode,$path) {
		$entityConvert=new maskHTMLEntity(false);
		$fileCode=$entityConvert->maskEngine($fileCode);
		if (@file_put_contents($path,$fileCode)===false){
			echo "<span class='error_class'>Произошёл сбой при записи кода шаблона выбранного дизайна! Проверьте права данные файлам в папке {$path} на возможность их перезаписи.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
}
?>