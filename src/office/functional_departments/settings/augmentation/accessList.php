<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_settings_accessList extends ErrorsManager{
	const ACCESS_FILE="gatekeeper/users.fbd.xml";
	const CONTENT_TPL_PATH="functional_departments/settings/dummyes/accessList_mainContent.fbd.tpl";
	const ELEM_ROW_TPL_PATH="functional_departments/settings/dummyes/accessList_elements_row.fbd.tpl";
	const ELEM_TPL_PATH="functional_departments/settings/dummyes/accessList_element.fbd.tpl";
	const NOT_MAIN_ACCESS_BUTS="
		<a class='managment_buttons redact_access_type' href='office/index.php?dep=settings&branch=redactAccessType.php&login={PAGE_NAME}' title='Редактировать тип доступа'>&nbsp;</a>
		<span class='managment_buttons remove_datas' id='page_number_{PAGE_NAME}' title='Удалить учетную запись'>&nbsp;</span>
	";
	
	const NUMERAL_ANCHOR_TPL="<a href='office/index.php?dep=settings&trunk=accessList.php&group={PAGE_NUMBER}'>{PAGE_NUMBER}</a>";
	const CURRENT_PAGE_TPL="<span>{PAGE_NUMBER}</span>";
	const ELEMS_ON_PAGE=50;
	const LIMITER=5;
	
	private $content;
	
	public function __construct(){
		$accessDesc=array("sa"=>"Управляющий","a"=>"Администратор","v"=>"Посетитель");
		if (file_exists(self::ACCESS_FILE)===true){
			$file=new DOMDocument();
			$file->load(self::ACCESS_FILE);
		}
		else
			$file=false;
		if ($file!==false){
			$xPathDoc=new DOMXPath($file);
			$accessXML=$xPathDoc->query("/data/users/user");
		}
		if (file_exists(self::CONTENT_TPL_PATH)===true)
			$contentTemplate=file_get_contents(self::CONTENT_TPL_PATH);
		else
			$contentTemplate=false;
			
		if (file_exists(self::ELEM_ROW_TPL_PATH)===true)
			$rowTemplate=file_get_contents(self::ELEM_ROW_TPL_PATH);
		else
			$rowTemplate=false;
		
		if (file_exists(self::ELEM_TPL_PATH)===true)
			$elemTemplate=file_get_contents(self::ELEM_TPL_PATH);
		else
			$elemTemplate=false;
		if ($file===false || $accessXML->length===0){
			$this->content="<div id='main_content'><h1>Файл users.fbd.xml, расположенный по пути: office/gatekeeper/ повреждён или отстутствует.</h1></div>";
			return false;
		}
		else if ($contentTemplate===false || $rowTemplate===false || $elemTemplate===false){
			$this->content="<div id='main_content'><h1>html-шаблон для отображения контента не найден</h1></div>";
			return false;
		}
		else {
			$tempContent="";
			$tempString="";
			
			$GETInf=new manipulateGETInf();
			$group=$GETInf->searchGETElem("group");
			if ($group===false)
				$group=1;
			$numeralAddon=self::genNumeralNavigation($group,$accessXML->length);
			
			for ($a=$numeralAddon['startPosition'],$c=1;$a<$numeralAddon['endPosition'];$a++){
				if ($accessXML->item($a)->attributes->getNamedItem("type")->nodeValue=="sa")
					$tempString.=str_replace("{NOT_MAIN_ACCESS_BUTS}","",$elemTemplate);
				else
					$tempString.=str_replace("{NOT_MAIN_ACCESS_BUTS}",self::NOT_MAIN_ACCESS_BUTS,$elemTemplate);
				$tempString=str_replace("{PAGE_NAME}",$accessXML->item($a)->attributes->getNamedItem("login")->nodeValue,$tempString);
				$tempString=str_replace("{ACCESS_TYPE}",$accessDesc[$accessXML->item($a)->attributes->getNamedItem("type")->nodeValue],$tempString);
				$c++;
				if ($c%3==0 || $a+1==$numeralAddon['endPosition']){
					$tempContent.=str_replace("{REPLACEMENT_PART}",$tempString,$rowTemplate);
					$tempString="";
				}
			}
			$this->content=str_replace("{NUMERAL_NAVIGATION}",$numeralAddon['numeralNavigation'],$contentTemplate);
			$this->content=str_replace("{REPLACEMENT_PART}","<h1>Список созданных аккаунтов</h1>"."<div class='pages_navigation first_block'>{$numeralAddon['numeralNavigation']}</div>".$tempContent,$this->content);
			if ($accessXML->length>=self::LIMITER)
			$this->content=str_replace("{ADD_NEW_BUTTON}","<a class='main_functions' href='office/index.php?dep=settings&branch=accessList.php#' style='color:#999;border-color:#999;' title='Функция заблокирована, т.к. достигнуто максимальное количество Учетных записей'>Создать учетную запись</a>",$this->content);
		else
			$this->content=str_replace("{ADD_NEW_BUTTON}","<a class='main_functions' href='office/index.php?dep=settings&branch=createAccess.php'>Создать учетную запись</a>",$this->content);
		}
	}
	
	private function genNumeralNavigation($pageN,$totalNumber){
		$numeralNavigation="";
		$lastPage=ceil($totalNumber / self::ELEMS_ON_PAGE); 
		if ($pageN>$lastPage)
			$pageN=$lastPage;
		for ($a=1;$a<=$lastPage;$a++){
			if ($a!=$pageN)
				$numeralNavigation.=str_replace("{PAGE_NUMBER}",$a,self::NUMERAL_ANCHOR_TPL);
			else
				$numeralNavigation.=str_replace("{PAGE_NUMBER}",$a,self::CURRENT_PAGE_TPL);
		}
		$startPosition=($pageN-1) * self::ELEMS_ON_PAGE;
		if ($pageN!=$lastPage)
			$endPosition=$pageN * self::ELEMS_ON_PAGE;
		else 
			$endPosition=$totalNumber;
		return array("startPosition"=>$startPosition,"endPosition"=>$endPosition,"numeralNavigation"=>$numeralNavigation);
	}
	
	public function getHtmlPiece() {
		return $this->content;
	}
}
?>