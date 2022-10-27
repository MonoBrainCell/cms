<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_design_htmlDeveloper {
	const SETTINGS_FILE="../settings.fbd.php";
	const DESIGN_DEV_CONTEINER="functional_departments/design/dummyes/redactDummyHtml_conteiner.fbd.tpl";
	const DESIGN_PATH_PREFIX="../design/{SELECTED_DESIGN}/dummy.fbd.html";

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
		$content=file_get_contents($htmlFile);
		self::genContent($content);
		
	}

	private function genContent($сontent){
		$mainTemp=file_get_contents(self::DESIGN_DEV_CONTEINER);
		$entityConvert=new maskHTMLEntity(true);
		$this->htmlContent=str_replace("{HTML_CODE}",$entityConvert->maskEngine($сontent),$mainTemp);
	}
	
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>