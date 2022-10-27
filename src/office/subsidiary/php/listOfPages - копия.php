<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class Shr__listOfPages {
	const NAVIGATION_FILE="../navigation.fbd.csv";
	private $manageButtons="<span id='ajax_close_button'>Вернуться к редактированию страницы</span>\r\n";
	private $ajaxWindowTmpl="<div class='ajax_window'>{DIRECTORY_CONTENT}</div>";
	private $fileViewElem="<div class='file_row'>{FILE_PATH}</div>";
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
		$navigation=file(self::NAVIGATION_FILE);
		for($a=0,$b=count($navigation);$a<$b;$a++){
			$elem=explode(";;",rtrim($navigation[$a]));
			if ($elem[0]=="start"){
				$this->pagesListHTML .=str_replace("{PAGE_NAME}","Главная (Стартовая)",$this->fileViewElem);
				$this->pagesListHTML=str_replace("{PATH}","/",$this->pagesListHTML);
			}
			else{
				$this->pagesListHTML .=str_replace("{PAGE_NAME}",$elem[2],$this->fileViewElem);
				if ($elem[4]=="1"){
					$this->pagesListHTML=str_replace("{PATH}",$elem[1],$this->pagesListHTML);
				}
				else {
					$this->pagesListHTML=str_replace("{PATH}","page/".$elem[0].".".$elem[1],$this->pagesListHTML);
				}
			}
		}
		$this->pagesListHTML=str_replace("{DIRECTORY_CONTENT}",$this->pagesListHTML,$this->ajaxWindowTmpl).$this->manageButtons;
	}
}
?>