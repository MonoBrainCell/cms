<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_ad_bannersList extends ErrorsManager{
	const NAVIGATION_FILE="../functional/ad/bannersList.fbd.csv";
	const CONTENT_TPL_PATH="functional_departments/ad/dummyes/bannersList_mainContent.fbd.tpl";
	const ELEM_ROW_TPL_PATH="functional_departments/ad/dummyes/bannersList_elements_row.fbd.tpl";
	const ELEM_TPL_PATH="functional_departments/ad/dummyes/bannersList_element.fbd.tpl";
	
	const NUMERAL_ANCHOR_TPL="<a href='office/index.php?dep=ad&branch=bannersList.php&group={PAGE_NUMBER}'>{PAGE_NUMBER}</a>";
	const CURRENT_PAGE_TPL="<span>{PAGE_NUMBER}</span>";
	const ELEMS_ON_PAGE=20;
	private $content;
	
	public function __construct(){
		if (file_exists(self::NAVIGATION_FILE)===true)
			$navigation=file(self::NAVIGATION_FILE);
		else
			$navigation=false;
			
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
		if ($navigation===false){
			$this->content="<div id='main_content'><h1>Файл: ". self::NAVIGATION_FILE ." повреждён или отстутствует.</h1></div>";
				return false;
		}
		else if ($contentTemplate===false || $rowTemplate===false || $elemTemplate===false){
			$this->content="<div id='main_content'><h1>html-шаблон для отображения контента не найден</h1></div>";
			return false;
		}
		else {
			if (empty($navigation)===true || strlen(trim($navigation[0]))<1){
				$this->content="<div id='main_content'><h1>Вы не создали ни одного баннера.</h1><a class='main_functions' href='office/index.php?dep=ad&branch=redactBanner.php&banId=new'>Создать баннер</a></div>";
				return false;
			}
			$tempContent="";
			$tempString="";
			
			$GETInf=new manipulateGETInf();
			$group=$GETInf->searchGETElem("group");
			if ($group===false)
				$group=1;
			$numeralAddon=self::genNumeralNavigation($group,count($navigation));
			
			for ($a=$numeralAddon['startPosition'],$c=1;$a<$numeralAddon['endPosition'];$a++){
				$structureElems=explode(";;",rtrim($navigation[$a]));
				$tempString.=str_replace("{BAN_NAME}",$structureElems[1],$elemTemplate);
				$tempString=str_replace("{BAN_NUMBER}",$structureElems[0],$tempString);
				if ($structureElems[3]!="through")
					$tempString=str_replace("{BAN_TYPE}","Локальный баннер",$tempString);
				else
					$tempString=str_replace("{BAN_TYPE}","Глобальный баннер",$tempString);
				if ($c%3==0 || $a+1==$numeralAddon['endPosition']){
					$tempContent.=str_replace("{REPLACEMENT_PART}",$tempString,$rowTemplate);
					$tempString="";
				}
				$c++;
			}
		}
		$this->content=str_replace("{NUMERAL_NAVIGATION}",$numeralAddon['numeralNavigation'],$contentTemplate);
		$this->content=str_replace("{REPLACEMENT_PART}","<h1>Список созданных баннеров</h1>"."<div class='pages_navigation first_block'>{$numeralAddon['numeralNavigation']}</div>".$tempContent,$this->content);
		$this->content=str_replace("{ADD_NEW_BUTTON}","<a class='main_functions' href='office/index.php?dep=ad&branch=redactBanner.php&banId=new'>Создать баннер</a>",$this->content);	
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