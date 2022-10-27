<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class relinkNavigationElement {
	const NAVIGATION_FILE="../navigation.fbd.xml";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к редактированию</span>\r\n";
	const PAGE_WASTE_PREFIX="page_number_";
	const FORBIDDEN_ALIAS="start";
	const SEPARATES_ALIAS="advanced";
	private $attentionAlias=array("main","advanced");
	
	public function __construct(){
		if (file_exists(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Не найден файл: '". self::NAVIGATION_FILE ."'. Проверьте его наличие в корневой папке сайта.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		if (isset($_POST['startElem'])===false || isset($_POST['targetElem'])===false){
			echo "<span class='error_class'>Не были переданы все данные для адекватного функционирования механизма перемещения элемента.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		if (in_array($_POST['startElem'],$this->attentionAlias)===true
		||
		$_POST['startElem']==self::FORBIDDEN_ALIAS
		||
		$_POST['targetElem']==self::FORBIDDEN_ALIAS
		){
			echo "<span class='error_class'>Были переданы неверные данные для адекватного функционирования механизма перемещения элемента.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		$file=new DOMDocument();
		$file->formatOutput=true;
		$file->preserveWhiteSpace=false;
		$file->load(self::NAVIGATION_FILE);
		self::convertAlias();
		if (in_array($_POST['targetElem'],$this->attentionAlias)===true){
			$tmpVar=self::transferBetweenSections($file);
			$spInf=$tmpVar[0];
			$file=$tmpVar[1];
		}
		else
			$file=self::transferWithinSections($file);
		if (@$file->save(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Ошибка перезаписи файла ". self::NAVIGATION_FILE .". Проверьте права доступа к этому файлу.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		if (isset($spInf)===true && $spInf!==true)
			$idVal=$spInf;
		else
			$idVal="usual";
		echo "<span class='success_class' id='{$idVal}'>Перемещение произведено успешно. Вы можете продолжить работу с навигацией.</span>\r\n". self::NAVIGATE_BUTTONS;
		exit;
	}
	private function transferBetweenSections($xml){
		$xPathDoc=new DOMXPath($xml);
		$queryXP=$xPathDoc->query("/navigation/{$_POST['targetElem']}/page[@alias='{$_POST['startElem']}']");
		if ($queryXP->length!==0)
			return array("self_chapter",$xml);
		$queryXP=$xPathDoc->query("/navigation/{$_POST['targetElem']}//page[@alias='{$_POST['startElem']}']");
		if ($queryXP->length===0)
			$spInf="to_another_chapter";
		else
			$spInf=true;
		$queryXP=$xPathDoc->query("/navigation//page[@alias='{$_POST['startElem']}']");
		$parent=$xPathDoc->query("/navigation//page[@alias='{$_POST['startElem']}']/parent::*");
		if ($_POST['targetElem']!=self::SEPARATES_ALIAS){
			$deletedElem=$parent->item(0)->removeChild($queryXP->item(0));
			$parent=$xPathDoc->query("/navigation/".$_POST['targetElem']);
			$parent->item(0)->insertBefore($deletedElem);
			return array($spInf,$xml);
		}
		else {
			$checkChild=$xPathDoc->query("/navigation//page[@alias='{$_POST['startElem']}']/child::page");
			$children=array();
			if ($checkChild->length!==0){
				for ($a=0;$a<$checkChild->length;$a++){
					$children[]=$queryXP->item(0)->removeChild($checkChild->item($a));
				}
			}
			$deletedElem=$parent->item(0)->removeChild($queryXP->item(0));
			if (empty($children)===false){
				for ($a=0,$b=count($children);$a<$b;$a++){
					$parent->item(0)->insertBefore($children[$a]);
				}
			}
			$parent=$xPathDoc->query("/navigation/".$_POST['targetElem']);
			$parent->item(0)->insertBefore($deletedElem);
			return array($spInf,$xml);
		}	
	}
	
	private function transferWithinSections($xml){
		$xPathDoc=new DOMXPath($xml);
		$checkReverseAffinity=$xPathDoc->query("/navigation//page[@alias='{$_POST['startElem']}']//page[@alias='{$_POST['targetElem']}']");
		if ($checkReverseAffinity->length!==0){
			echo "<span class='error_class'>Были переданы неверные данные для адекватного функционирования механизма перемещения элемента.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		unset($checkReverseAffinity);
		$navDir1=$xPathDoc->query("/navigation//page[@alias='{$_POST['startElem']}']/ancestor::main");
		$navDir2=$xPathDoc->query("/navigation//page[@alias='{$_POST['targetElem']}']/ancestor::main");
		if ($navDir1->length===0 || $navDir2->length===0){
			echo "<span class='error_class'>Были переданы неверные данные для адекватного функционирования механизма перемещения элемента.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		unset($navDir1);unset($navDir2);
		$queryXP=$xPathDoc->query("/navigation//page[@alias='{$_POST['startElem']}']");
		$parent=$xPathDoc->query("/navigation//page[@alias='{$_POST['startElem']}']/parent::*");
		$deletedElem=$parent->item(0)->removeChild($queryXP->item(0));
		$parent=$xPathDoc->query("/navigation//page[@alias='{$_POST['targetElem']}']");
		$parent->item(0)->insertBefore($deletedElem);
		return $xml;
	}
	private function convertAlias(){
		$specialAlias=array("news_php","guestbook_php");
		$_POST['startElem']=str_replace(self::PAGE_WASTE_PREFIX,"",$_POST['startElem']);
		$_POST['targetElem']=str_replace(self::PAGE_WASTE_PREFIX,"",$_POST['targetElem']);
		if (in_array($_POST['startElem'],$specialAlias)===true)
			$_POST['startElem']=str_replace("_php",".php",$_POST['startElem']);
		if (in_array($_POST['targetElem'],$specialAlias)===true)
			$_POST['targetElem']=str_replace("_php",".php",$_POST['targetElem']);
	}
}
$tempObj=new relinkNavigationElement();
?>