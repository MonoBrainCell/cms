<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class Shr__listOfPages {
	const NAVIGATION_FILE="../navigation.fbd.xml";
	private $manageButtons="<span id='ajax_close_button'>Вернуться к редактированию страницы</span>\r\n";
	private $ajaxWindowTmpl="<div class='ajax_window'>{DIRECTORY_CONTENT}</div>";
	private $fileViewElem="<div class='file_row'>{PATH}</div>";
	public $pagesListHTML="";
	public function __construct($windTpl="",$viewTpl="",$manageButtons=""){
		if (file_exists(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Файл навигации не найден.</span>". $this->manageButtons;
			exit;
		}
		if ($windTpl!="")
			$this->ajaxWindowTmpl=$windTpl;
		if ($viewTpl!="")
			$this->fileViewElem=$viewTpl;
		if ($manageButtons!="")
			$this->manageButtons=$manageButtons;
		
		$file=new DOMDocument();
		$file->formatOutput=true;
		$file->preserveWhiteSpace=false;
		$file->load(self::NAVIGATION_FILE);
		$xPathDoc=new DOMXPath($file);
		$pages=$xPathDoc->query("/navigation//page");
		if ($pages->length===0){
			echo "<span class='error_class'>Произошёл сбой при интерпретации информации об имеющихся страницах. <br>Скорее всего данная проблема была вызвана ошибкой записи. <br> Обратитесь к разработчику за помощью или к Хостинг-провайдеру для BackUp'a сайта.</span>". $this->manageButtons;
			return false;
		}
		for ($a=0;$a<$pages->length;$a++){
			$name=$pages->item($a)->attributes->getNamedItem("name")->nodeValue;
			$enclaveStatus=$pages->item($a)->attributes->getNamedItem("enclave")->nodeValue;
			$alias=$pages->item($a)->attributes->getNamedItem("alias")->nodeValue;
			
			if ($enclaveStatus==="0" && $alias!="start"){
				$alias="/page/".$alias;
			}
			else if($alias=="start"){
				$alias="/";
				$name="Главная (Стартовая)";
			}
			else {
				$alias="/".$alias;
			}
			$this->pagesListHTML .=str_replace("{PAGE_NAME}",$name,$this->fileViewElem);
			$this->pagesListHTML=str_replace("{PATH}",$alias,$this->pagesListHTML);
		}
		
		$this->pagesListHTML=str_replace("{DIRECTORY_CONTENT}",$this->pagesListHTML,$this->ajaxWindowTmpl).$this->manageButtons;
	}
	
	
}
?>