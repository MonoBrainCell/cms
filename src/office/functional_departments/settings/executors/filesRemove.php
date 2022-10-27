<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class filesRemove {
	const NAVIGATE_BUTTONS="<span class='abort_rename_files' id='ajax_close_button'>Вернуться к выбору файла</span>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	public function __construct(){
		if (isset($_POST['directory'])===false || isset($_POST['file_name'])===false){
			echo "<span class='error_class'>Были переданы не все данные для адекватной работы данного функционала. Обновите страницы и повторите попытку.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$initObj=new App_settings_availableFolder();
		$access=$initObj->checkId();
		if ($access===0){
			echo "<span class='error_class'>Вы не имеете необходимого уровня доступа к системе загрузки файлов.</span>".self::NAVIGATE_BUTTONS;
			return false;
		}
		if ($initObj->checkXML($access,$_POST['directory'])===false){
			echo "<span class='error_class'>Вы попытались изменить содержание директории, к которой у Вас недостаточно прав доступа.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (unlink($_POST['directory'].$_POST['file_name'])===false){
			echo "<span class='error_class'>Произошла ошибка в ходе выполнения данного функционала. Попробуйте обновить страницу и повторить попытку.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}

		echo "<span class='success_class'>Изменения внесены успешно.</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
}
$varObj=new filesRemove();
?>