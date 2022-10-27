<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_feedback_keeper extends ErrorsManager {
	const CONTENT_SAVE_PATH="../functional/feedback/content/fbContent.fbd.tpl";
	const HEADERS_SAVE_PATH="../functional/feedback/content/fbHeaders.fbd.tpl";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к редактированию формы</span>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";

	public function __construct() {}
	
	public function startKeeperEngine(){
		if (isset($_POST['submit_redaction'][0])===false || isset($_POST['body_code'][0])===false || isset($_POST['headers_code'][0])===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (mb_strlen(trim($_POST['body_code'][0]))>0)
			self::reloadCodeFile($_POST['body_code'][0],self::CONTENT_SAVE_PATH);
		else {
			echo "<span class='error_class'>Вы оставили пустым поле <q>Html-код формы обратной связи</q>!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		
		if (mb_strlen(trim($_POST['headers_code'][0]))>0)	
			self::reloadCodeFile($_POST['headers_code'][0],self::HEADERS_SAVE_PATH);
		else 
			self::reloadCodeFile("",self::HEADERS_SAVE_PATH);
		echo "<span class='success_class'>Изменения в форме сохранены.</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
	
	private function reloadCodeFile($fileCode,$path) {
		$entityConvert=new maskHTMLEntity(false);
		$fileCode=$entityConvert->maskEngine($fileCode);
		if (@file_put_contents($path,$fileCode)===false){
			echo "<span class='error_class'>Произошёл сбой при записи кода формы! Проверьте права данные файлам в папке functional/feedback/content на возможность их перезаписи.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
}
?>