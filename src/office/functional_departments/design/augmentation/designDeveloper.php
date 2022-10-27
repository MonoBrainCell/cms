<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_design_designDeveloper {
	const SETTINGS_FILE="../settings.fbd.php";
	const DESIGN_DEV_CONTEINER="functional_departments/design/dummyes/selectDesign_conteiner.fbd.tpl";
	const DESIGN_PATH_PREFIX="../design/";
	const DESIGN_PATTERN_NAME="dummy.fbd.html";

	private $htmlContent;
	public function __construct(){
		if (file_exists(self::SETTINGS_FILE)===true)
			 include self::SETTINGS_FILE;
		else{
			$this->htmlContent="<div id='main_content'><h1>Файл с основными настройками сайта не найден. Проверьте наличие файла settings.fbd.php в корневной директории сайта.</h1></div>";
			return false;
		}
		$htmlFile=str_replace("{SELECTED_DESIGN}",$selectedDesign,self::DESIGN_PATH_PREFIX);
		if (file_exists($htmlFile)===false){
			$this->htmlContent="<div id='main_content'><h1>Не найден файл шаблона выбранного дизайна</h1></div>";
			return false;
		}
		if (file_exists(self::DESIGN_DEV_CONTEINER)===false){
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		self::genContent($selectedDesign);
		
	}

	private function genContent($selectedDesign){
		$designsPath=glob(self::DESIGN_PATH_PREFIX ."*");
		$options=array();
		for ($a=0,$b=count($designsPath);$a<$b;$a++){
			if (is_dir($designsPath[$a])===true && file_exists($designsPath[$a]."/".self::DESIGN_PATTERN_NAME)===true){
				$designName=basename($designsPath[$a]);
				if ($designName!=$selectedDesign)
					$options[]="<option value='{$designName}'>{$designName}</option>";
			}
		}
		array_unshift($options,"<option value='{$selectedDesign}'>{$selectedDesign}</option>");
		$content=implode("\r\n",$options);
		$mainTemp=file_get_contents(self::DESIGN_DEV_CONTEINER);
		$this->htmlContent=str_replace("{DESIGNS}",$content,$mainTemp);
	}
	
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>