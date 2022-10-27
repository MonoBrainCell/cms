<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class Shr__listOfFiles extends ErrorsManager{
	private $manageButtons="<span id='ajax_close_button'>Вернуться к редактированию страницы</span>\r\n";
	private $pathToDir="";
	private $ajaxWindowTmpl="<div class='ajax_window'>{DIRECTORY_CONTENT}</div>";
	private $fileViewElem="<div class='file_row'>{FILE_PATH}</div>";
	private $pathPrefix="../";
	
	private $listOfFiles=array();
	public $filesListHTML="";
	public function __construct($pathToDir,$windTpl="",$viewTpl="",$manageButtons="",$pathPrefix=""){
		$this->pathToDir=$pathToDir; 
		if ($windTpl!="")
			$this->ajaxWindowTmpl=$windTpl;
		if ($viewTpl!="")
			$this->fileViewElem=$viewTpl;
		if ($manageButtons!="")
			$this->manageButtons=$manageButtons;
		if ($pathPrefix!="")
			$this->pathPrefix=$pathPrefix;
		if (file_exists($this->pathPrefix . $this->pathToDir)===false || is_dir($this->pathPrefix . $this->pathToDir)===false){
			echo "<span class='error_class'>Запрашиваемая директория не найдена.</span>". $this->manageButtons;
			exit;
		}
		$listOfFiles=glob($this->pathPrefix . $this->pathToDir ."/*",GLOB_NOSORT);
		
		if (empty($listOfFiles)===true){
			echo "<span class='error_class'>Запрашиваемая директория: ".$this->pathToDir ." пуста.</span>". $this->manageButtons;
			exit;
		}
		else if($listOfFiles===false){
			echo "<span class='error_class'>Произошла ошибка при проверке содержимого директории: ".$this->pathToDir .".</span>". $this->manageButtons;
			exit;
		}
		else{
			for ($a=0,$b=count($listOfFiles);$a<$b;$a++){
				if (is_file($listOfFiles[$a])===true){
					$this->listOfFiles[$a]=$this->pathToDir ."/". basename($listOfFiles[$a]);
				}
			}
		}
		self::generateHtml();
	}
	
	
	private function generateHtml(){
		$elems="";
		for($a=0,$b=count($this->listOfFiles);$a<$b;$a++){
			$elems.=str_replace("{FILE_PATH}",iconv("WINDOWS-1251","UTF-8",$this->listOfFiles[$a]),$this->fileViewElem);
		}
		$this->filesListHTML=str_replace("{DIRECTORY_CONTENT}",$elems,$this->ajaxWindowTmpl).$this->manageButtons;
	}
	
}
?>