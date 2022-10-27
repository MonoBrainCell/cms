<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_guestbook_recordsList extends ErrorsManager{
	const NAVIGATION_FILE="../functional/guestbook/recordsList.fbd.csv";
	const CONTENT_TPL_PATH="functional_departments/guestbook/dummyes/recordsList_mainContent.fbd.tpl";
	const ELEM_ROW_TPL_PATH="functional_departments/guestbook/dummyes/recordsList_elements_row.fbd.tpl";
	const ELEM_TPL_PATH="functional_departments/guestbook/dummyes/recordsList_element.fbd.tpl";
	
	const NUMERAL_ANCHOR_TPL="<a href='office/index.php?dep=guestbook&trunk=recordsList.php&group={PAGE_NUMBER}'>{PAGE_NUMBER}</a>";
	const CURRENT_PAGE_TPL="<span>{PAGE_NUMBER}</span>";
	const ELEMS_ON_PAGE=50;
	
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
			$this->content="<div id='main_content'><h1>Файл recordsList.fbd.csv, расположенный в functional/guestbook повреждён или отстутствует.</h1></div>";
			return false;
		}
		else if (empty($navigation)===true){
			$this->content="<div id='main_content'><h1>Никто из посетителей не добавлял записи в гостевую книгу.</h1>
			<a class='main_functions' href='office/index.php?dep=guestbook&branch=guestbookTune.php&tOT=block'>Настроить отображение блока записей</a>
			<a class='main_functions' href='office/index.php?dep=guestbook&branch=guestbookTune.php&tOT=item'>Настроить отображение одной записи</a>
			</div>";
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
			$numeralAddon=self::genNumeralNavigation($group,count($navigation));
			$navigation=array_reverse($navigation);
			for ($a=$numeralAddon['startPosition'],$c=1;$a<$numeralAddon['endPosition'];$a++){
				$structureElems=explode(";;",rtrim($navigation[$a]));
				$tempString.=str_replace("{REC_NUMBER}",$structureElems[0],$elemTemplate);
				$tempString=str_replace("{GUEST_NAME}",$structureElems[1],$tempString);
				$tempString=str_replace("{ADDED_DATE}",$structureElems[2],$tempString);
				$tempString=str_replace("{GUEST_EMAIL}",$structureElems[3],$tempString);

				if ($c%3==0 || $a+1==$numeralAddon['endPosition']){
					$tempContent.=str_replace("{REPLACEMENT_PART}",$tempString,$rowTemplate);
					$tempString="";
				}
				$c++;
			}
			$this->content=str_replace("{NUMERAL_NAVIGATION}",$numeralAddon['numeralNavigation'],$contentTemplate);
			$this->content=str_replace("{REPLACEMENT_PART}","<h1>Список добавленных в гостевую книгу записей</h1>"."<div class='pages_navigation first_block'>{$numeralAddon['numeralNavigation']}</div>".$tempContent,$this->content);
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