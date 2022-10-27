<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class widgetsTextRedact{
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к редактированию</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=widgets&trunk=widgetsList.php'>Вернуться к списку виджетов</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	const MAIN_NAVIGATION_TPL_FILE="../functional/widgets/dummyes/widgetsTpls.fbd.tpl";
	public function __construct(){
		if(isset($_POST['submit_changes'][0])===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (isset($_POST["block_wrapper"][0])===false)
			$string="";
		else
			$string=$_POST["block_wrapper"][0];
		$entityConvert=new maskHTMLEntity(false);
		$string=$entityConvert->maskEngine($string);
		if (@file_put_contents(self::MAIN_NAVIGATION_TPL_FILE,$string)===false){
			echo "<span class='error_class'>Ошибка перезаписи файла ". self::MAIN_NAVIGATION_TPL_FILE .". Проверьте права доступа к этому файлу.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		echo "<span class='success_class'>Изменение произведено успешно.</span>\r\n". self::NAVIGATE_BUTTONS;
		exit;
	}
}
$varObj=new widgetsTextRedact();
?>
