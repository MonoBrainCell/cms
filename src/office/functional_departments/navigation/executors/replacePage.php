<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class replaceNavigationElement {
	const NAVIGATION_FILE="../navigation.fbd.xml";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к редактированию</span>\r\n";
	private $attentionAlias=array("main","advanced");
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
		self::convertSomeAlias();
		$file=new DOMDocument();
		$file->formatOutput=true;
		$file->preserveWhiteSpace=false;
		$file->load(self::NAVIGATION_FILE);
		$xPathDoc=new DOMXPath($file);
		$ced=$xPathDoc->query("/navigation//page[@alias='{$_POST['startElem']}']/parent::*");
		$ted=$xPathDoc->query("/navigation//page[@alias='{$_POST['targetElem']}']/parent::*");
		if ($ced->length===0 || $ted->length===0){
			echo "<span class='error_class'>Одна из указанных страниц не найдена. Возможно страница была удалена. Обновите административный раздел и повторите попытку.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if ($ced->item(0)->nodeName != $ted->item(0)->nodeName
		||
		($ced->item(0)->nodeName == $ted->item(0)->nodeName
		&&
		in_array($ced->item(0)->nodeName,$this->attentionAlias)===false
		&& $ced->item(0)->attributes->getNamedItem('alias')->nodeValue != $ted->item(0)->attributes->getNamedItem('alias')->nodeValue)
		){
			echo "<span class='error_class'>Указанные страницы не являются соседними (т.е. принадлежащими одной родительской странице или разделу). Изменение положения страницы в навигации возможно только для соседних страниц.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$currentElem=$xPathDoc->query("/navigation//page[@alias='{$_POST['startElem']}']");
		$deletedElem=$ced->item(0)->removeChild($currentElem->item(0));
		if ($_POST['type']=="before")
			$targetElem=$xPathDoc->query("/navigation//page[@alias='{$_POST['targetElem']}']");
		else if ($_POST['type']=="after")
			$targetElem=$xPathDoc->query("/navigation//page[@alias='{$_POST['targetElem']}']/following-sibling::*[1]");
		if ($targetElem->length===0)
			$ced->item(0)->insertBefore($deletedElem);
		else
			$ced->item(0)->insertBefore($deletedElem,$targetElem->item(0));
		if (@$file->save(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Ошибка перезаписи файла ". self::NAVIGATION_FILE .". Проверьте права доступа к этому файлу.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		echo "<span class='success_class'>Перемещение произведено успешно. Вы можете продолжить работу с навигацией.</span>\r\n". self::NAVIGATE_BUTTONS;
		exit;
	}
	private function convertSomeAlias(){
		$specialAlias=array("news_php","guestbook_php");
		if (in_array($_POST['startElem'],$specialAlias)===true)
			$_POST['startElem']=str_replace("_php",".php",$_POST['startElem']);
		if (in_array($_POST['targetElem'],$specialAlias)===true)
			$_POST['targetElem']=str_replace("_php",".php",$_POST['targetElem']);
	}
}
$tempObj=new replaceNavigationElement();
?>