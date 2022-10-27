<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_widgets_developerOfTune extends ErrorsManager{
	const MAIN_NAVIGATION_TPL_FILE="../functional/widgets/dummyes/widgetsTpls.fbd.tpl";
	const TUNE_CONTEINER_TPL_PATH="functional_departments/widgets/dummyes/tuneWidgets_conteiner.fbd.tpl";
	private $htmlContent;
	
	public function __construct(){
		if (file_exists(self::TUNE_CONTEINER_TPL_PATH)===false) {
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		if (file_exists(self::TUNE_CONTEINER_TPL_PATH)===false) {
			$this->htmlContent="<div id='main_content'><h1>Файл ". self::TUNE_CONTEINER_TPL_PATH .", повреждён или отстутствует.</h1></div>";
			return false;
		}
		$mainTpl=file_get_contents(self::TUNE_CONTEINER_TPL_PATH);
		$this->htmlContent=self::genElemsOfTune($mainTpl);
	}
	
	private function genElemsOfTune($template){
		$tplsContent=file_get_contents(self::MAIN_NAVIGATION_TPL_FILE);	
		return self::replaceKeywords($tplsContent,$template);
	}
	private function replaceKeywords($elem,$template){
		$entityConvert=new maskHTMLEntity(true);
		$totalString=str_replace("{WW_WRAPPER}",$entityConvert->maskEngine($elem),$template);
		return $totalString;
	}
	
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>