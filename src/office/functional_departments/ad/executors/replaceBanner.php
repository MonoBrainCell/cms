<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class replaceBannersElement {
	const NAVIGATION_FILE="../functional/ad/bannersList.fbd.csv";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к редактированию</span>\r\n";
	public function __construct(){
		if (file_exists(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Не найден файл: '". self::NAVIGATION_FILE ."'. Проверьте его наличие в корневой папке сайта.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		if (
		isset($_POST['type'])===false
		||
		(isset($_POST['type'])===true && $_POST['type']!="before" && $_POST['type']!="after")
		||
		isset($_POST['startElem'])===false
		||
		isset($_POST['targetElem'])===false
		){
			echo "<span class='error_class'>Не были переданы все данные для адекватного функционирования механизма перемещения элемента.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		$navigation=file(self::NAVIGATION_FILE);
		$navigationArr=array();
		for ($a=0,$b=count($navigation);$a<$b;$a++){
			$tempArr=explode(";;",$navigation[$a]);
			$navigationArr[$tempArr[0]]=$navigation[$a];
		}
		if ($_POST['type']=="before")
			$navigationArr[$_POST['targetElem']]=$navigationArr[$_POST['startElem']].$navigationArr[$_POST['targetElem']];
		else if ($_POST['type']=="after")
			$navigationArr[$_POST['targetElem']].=$navigationArr[$_POST['startElem']];
		unset($navigationArr[$_POST['startElem']]);
		$string=implode("",$navigationArr);
		if (@file_put_contents(self::NAVIGATION_FILE,$string)===false){
			echo "<span class='error_class'>Ошибка перезаписи файла ". self::NAVIGATION_FILE .". Проверьте права доступа к этому файлу.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		echo "<span class='success_class'>Перемещение произведено успешно. Вы можете продолжить работу с навигацией.</span>\r\n". self::NAVIGATE_BUTTONS;
		exit;
	}
}
$tempObj=new replaceBannersElement();
?>