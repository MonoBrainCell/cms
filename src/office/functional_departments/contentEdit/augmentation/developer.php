<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_contentEdit_developer extends ErrorsManager {
	const NAVIGATION_FILE="../navigation.fbd.xml";
	const CONTENT_TPL_PATH="functional_departments/contentEdit/dummyes/redactContent_mainContent.fbd.tpl";
	const VAR_TPL_PATH="functional_departments/contentEdit/dummyes/redactContent_variablePart.fbd.tpl";
	const CONTENT_STORAGE_PATH="../content/";
	private $htmlContent;
	private $pid;
	private $mainTemplate;
	private $varTemplate;
	private $xPathDoc;
	private $navigationLayers="all";
	
	public function __construct(){
		$GETInf=new manipulateGETInf();
		$this->pid=$GETInf->searchGETElem("pageid");
		if ($this->pid=="start")
			$navigation="none";
		else if ($this->pid!="start" && file_exists(self::NAVIGATION_FILE)===true){
			$navigation=new DOMDocument();
			$navigation->formatOutput=true;
			$navigation->preserveWhiteSpace=false;
			$navigation->load(self::NAVIGATION_FILE);
			$this->xPathDoc=new DOMXPath($navigation);
		}
		else 
			$navigation=false;
		if (file_exists(self::CONTENT_TPL_PATH)===true)
			$this->mainTemplate=file_get_contents(self::CONTENT_TPL_PATH);
		else
			$this->mainTemplate=false;
		if ($this->pid=="start")
			$this->varTemplate="none";
		else if ($this->pid!="start" && file_exists(self::VAR_TPL_PATH)===true)
			$this->varTemplate=file_get_contents(self::VAR_TPL_PATH);
		else
			$this->varTemplate=false;
		if ($navigation===false || empty($navigation)===true){
			$this->htmlContent="<div id='main_content'><h1>Файл navigation.fbd.xml, расположенный в корневой папке сайта повреждён или отстутствует.</h1></div>";
			return false;
		}
		if ($this->mainTemplate===false || $this->varTemplate===false) {
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		
		if ($this->pid!==false && file_exists(self::CONTENT_STORAGE_PATH .$this->pid .".php")===false){
			$this->htmlContent="<div id='main_content'><h1>Не найден файл с содержанием страницы: ". self::CONTENT_STORAGE_PATH .$this->pid .".php</h1></div>";
			return false;
		}
		if ($this->pid!==false){
			include(self::CONTENT_STORAGE_PATH .$this->pid .".php");
			$hiddenId=$this->pid;
		}
		else {
			$pageTitle="";$pageH1="";$pageDesc="";$pageKWords="";$content="";
			$hiddenId="new";
		}
		$this->htmlContent=str_replace("{HIDDEN_ID}",$hiddenId,$this->mainTemplate);
		
		$this->htmlContent=str_replace("{TITLE}",$pageTitle,$this->htmlContent);
		
		$this->htmlContent=str_replace("{KEYWORDS}",$pageKWords,$this->htmlContent);
		
		$this->htmlContent=str_replace("{DESCRIPTION}",$pageDesc,$this->htmlContent);
		
		$this->htmlContent=str_replace("{H1}",$pageH1,$this->htmlContent);
		
		$this->htmlContent=str_replace("{CONTENT}",$content,$this->htmlContent);
		
		if ($hiddenId=="new")
			$variablePart=self::addVariablePart();
		else 
			$variablePart="";
		
		$this->htmlContent=str_replace("{VARIABLE_PART}",$variablePart,$this->htmlContent);
		
	}
	
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
	
	private function addVariablePart(){
		$totalString="";$options=""; $pageAlias=""; $pageName="";$parent=false;
		$XMLPiece=$this->xPathDoc->query("/navigation/main/page");
		$totalString=str_replace("{PAGE_NAME}",$pageName,$this->varTemplate);
		$totalString=str_replace("{ALIAS}",$pageAlias,$totalString);
		$returnOptions=self::genOptions($XMLPiece);
		$totalString=str_replace("{COUNT_PAGES}",$returnOptions[1],$totalString);
		$totalString=str_replace("{PAGES_LIST}",$returnOptions[0],$totalString);			
		return $totalString;
	}
	
	private function genOptions($XMLPiece){
		if ($XMLPiece->length===0)
			return array("<option value='-1'>Дополнительное меню</option>\r\n<option value='0'>Главное меню</option>\r\n",2);
		else {
			$count=$this->xPathDoc->query("/navigation/main//page")->length;
			return (array("<option value='-1'>Дополнительное меню</option>\r\n<option value='0'>Главное меню</option>\r\n".self::genPagesList($XMLPiece),$count+2));
			}
		}
	private function genPagesList($XMLPiece,$tpl="&mdash;&gt;"){
		$str="";
		for($a=0;$a<$XMLPiece->length;$a++){
			$str.="<option value='".$XMLPiece->item($a)->attributes->getNamedItem('alias')->nodeValue ."'>".$tpl.$XMLPiece->item($a)->attributes->getNamedItem('name')->nodeValue."</option>\r\n";
			if ($XMLPiece->item($a)->hasChildNodes()===true)
				$str.=self::genPagesList($XMLPiece->item($a)->childNodes,"&ndash;".$tpl);
		}
		return $str;
	}
}
?>