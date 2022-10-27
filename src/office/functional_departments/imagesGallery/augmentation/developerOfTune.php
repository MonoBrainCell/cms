<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_imagesGallery_developerOfTune extends ErrorsManager{
	const VIDEO_INSERT_TPL_FILE="../functional/imagesGallery/dummyes/imagesGalleryTpls.fbd.tpl";
	const TUNE_CONTEINER_TPL_PATH="functional_departments/imagesGallery/dummyes/tuneImagesGallery_conteiner.fbd.tpl";
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
		$tplsContent=file_get_contents(self::VIDEO_INSERT_TPL_FILE);
		$separator="|--|";
		$array=explode($separator,$tplsContent);
		
		return self::replaceKeywords($array,$template);
	}
	private function replaceKeywords($arrayElem,$template){
		$entityConvert=new maskHTMLEntity(true);
		$totalString=str_replace("{BROWSER_HEADERS}",$entityConvert->maskEngine($arrayElem[0]),$template);
		$totalString=str_replace("{SEPARATE_CODE}",$entityConvert->maskEngine($arrayElem[1]),$totalString);
		$totalString=str_replace("{BUILT-IN_CODE}",$entityConvert->maskEngine($arrayElem[2]),$totalString);
		return $totalString;
	}
	
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>