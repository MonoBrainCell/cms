<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_videoGallery_videosList extends ErrorsManager{
	const NAVIGATION_FILE="../functional/videoGallery/videosList.fbd.csv";
	const CONTENT_TPL_PATH="functional_departments/videoGallery/dummyes/videosList_mainContent.fbd.tpl";
	const ELEM_ROW_TPL_PATH="functional_departments/videoGallery/dummyes/videosList_elements_row.fbd.tpl";
	const ELEM_TPL_PATH="functional_departments/videoGallery/dummyes/videosList_element.fbd.tpl";
	const LIMITER=100;
	
	const NUMERAL_ANCHOR_TPL="<a href='office/index.php?dep=videoGallery&trunk=videosList.php&group={PAGE_NUMBER}'>{PAGE_NUMBER}</a>";
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
		if ($navigation===false)
			$this->content="<h1>Файл: ". self::NAVIGATION_FILE ." повреждён или отстутствует.</h1>";
		else if ($contentTemplate===false || $rowTemplate===false || $elemTemplate===false){
			$this->content="<h1>html-шаблон для отображения контента не найден</h1>";
			return false;
		}
		else {
			if (empty($navigation)===true || strlen(trim($navigation[0]))<1){
				$this->content="<div id='main_content'><h1>Вы не добавили ни одного видео.</h1>
				<a class='main_functions' href='office/index.php?dep=videoGallery&branch=redactVideo.php&vId=new'>Добавить видео</a>
				<a class='main_functions' href='office/index.php?dep=videoGallery&branch=tuneVideoGallery.php'>Настроить видео-вкладки</a>
				<ul>
				<li>С помощью этого функционала можно добавлять на сайт видео (загруженные в видео-хостинги) для просмотра посетителями</li>
				<li>Любое из добавленных видео можно вставлять только в основной контент</li>
				<li>Добавление производится путём вставки специальной строки типа:<br><span style='color:#b00;'>{*VIDEO_INSERT-номер_видео*}</span><br>в основной контент</li>
				<li>Выше указанную строку можно увидеть в блоке каждого виджета.</li>
				</ul>
				</div>";
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
				$tempString.=str_replace("{VIDEO_NAME}",$structureElems[1],$elemTemplate);
				$tempString=str_replace("{VIDEO_NUMBER}",$structureElems[0],$tempString);
				$tempString=str_replace("{VIDEO_INS_CODE}","{*VIDEO_INSERT-{$structureElems[0]}*}",$tempString);
				$tempString=str_replace("{VIDEO_WIDTH}",$structureElems[2],$tempString);
				$tempString=str_replace("{VIDEO_HEIGHT}",$structureElems[3],$tempString);
				if ($c%3==0 || $a+1==$numeralAddon['endPosition']){
					$tempContent.=str_replace("{REPLACEMENT_PART}",$tempString,$rowTemplate);
					$tempString="";
				}
				$c++;
			}
		}
		$this->content=str_replace("{NUMERAL_NAVIGATION}",$numeralAddon['numeralNavigation'],$contentTemplate);
		$this->content=str_replace("{REPLACEMENT_PART}","<h1>Список добавленных видео</h1>"."<div class='pages_navigation first_block'>{$numeralAddon['numeralNavigation']}</div>".$tempContent,$this->content);
		if (count($navigation)>=self::LIMITER)
			$this->content=str_replace("{ADD_NEW_BUTTON}","<a class='main_functions' href='office/index.php?dep=videoGallery&trunk=videosList.php#' style='color:#999;border-color:#999;' title='Функция заблокирована, т.к. достигнуто максимальное количество видео (". self::LIMITER .")'>Добавить видео</a>",$this->content);
		else
			$this->content=str_replace("{ADD_NEW_BUTTON}","<a class='main_functions' href='office/index.php?dep=videoGallery&branch=redactVideo.php&vId=new'>Добавить видео</a>",$this->content);
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