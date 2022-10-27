<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_contentEdit_pagesList extends ErrorsManager{
	const NAVIGATION_FILE="../navigation.fbd.xml";
	const CONTENT_TPL_PATH="functional_departments/contentEdit/dummyes/pagesList_mainContent.fbd.tpl";
	const ELEM_ROW_TPL_PATH="functional_departments/contentEdit/dummyes/pagesList_elements_row.fbd.tpl";
	const ELEM_TPL_PATH="functional_departments/contentEdit/dummyes/pagesList_element.fbd.tpl";
	
	const NUMERAL_ANCHOR_TPL="<a href='office/index.php?dep=contentEdit&trunk=pagesList.php&group={PAGE_NUMBER}'>{PAGE_NUMBER}</a>";
	const CURRENT_PAGE_TPL="<span>{PAGE_NUMBER}</span>";
	const ELEMS_ON_PAGE=50;
	
	private $content;
	
	public function __construct(){
		if (file_exists(self::NAVIGATION_FILE)===true){
			$navigation=new DOMDocument();
			$navigation->formatOutput=true;
			$navigation->preserveWhiteSpace=false;
			$navigation->load(self::NAVIGATION_FILE);
			$xPathDoc=new DOMXPath($navigation);
		}
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
		if ($navigation===false || empty($navigation)===true){
			$this->content="<div id='main_content'><h1>Файл navigation.fbd.xml, расположенный в корневой папке сайта повреждён или отстутствует.</h1></div>";
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
			$checkPages=$xPathDoc->query("/navigation//page[@enclave='0']");
			$numeralAddon=self::genNumeralNavigation($group,$checkPages->length);
			for ($a=$numeralAddon['startPosition'];$a<$numeralAddon['endPosition'];$a++){
				if ($checkPages->item($a)->attributes->getNamedItem('alias')->nodeValue=='start'){
					$tempString.=str_replace("{PAGE_ADDRESS}","/",$elemTemplate);
					$tempString=str_replace("{ALIAS}","start",$tempString);
					$tempString=str_replace("{PAGE_NAME}","Главная",$tempString);
				}
				else {
					$tempString.=str_replace("{PAGE_ADDRESS}","page/".$checkPages->item($a)->attributes->getNamedItem('alias')->nodeValue,$elemTemplate);
					$tempString=str_replace("{ALIAS}",$checkPages->item($a)->attributes->getNamedItem('alias')->nodeValue,$tempString);
					$tempString=str_replace("{PAGE_NAME}",$checkPages->item($a)->attributes->getNamedItem('name')->nodeValue,$tempString);
				}
				if (($a+1)%3==0 || $a+1==$numeralAddon['endPosition']){
					$tempContent.=str_replace("{REPLACEMENT_PART}",$tempString,$rowTemplate);
					$tempString="";
				}
			}
			$this->content=str_replace("{NUMERAL_NAVIGATION}",$numeralAddon['numeralNavigation'],$contentTemplate);
			$this->content=str_replace("{REPLACEMENT_PART}","<h1>Список созданных страниц</h1>"."<div class='pages_navigation first_block'>{$numeralAddon['numeralNavigation']}</div>".$tempContent,$this->content);
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