<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_feedback_developer {
	const FEEDACK_DEV_CONTEINER="functional_departments/feedback/dummyes/redactFeedback_conteiner.fbd.tpl";
	const FEEDBACK_CONTENT_PATH_PREFIX="../functional/feedback/content/";
	const FEEDBACK_HEADERS_PREFIX="fbHeaders.fbd.tpl";
	const FEEDBACK_BODY_PREFIX="fbContent.fbd.tpl";
	private $htmlContent;
	public function __construct(){
		if (file_exists(self::FEEDACK_DEV_CONTEINER)===false){
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		self::genContent();
		
	}

	private function genContent(){
		$mainTemp=file_get_contents(self::FEEDACK_DEV_CONTEINER);
		$entityConvert=new maskHTMLEntity(true);
		$this->htmlContent=$entityConvert->maskEngine($mainTemp);
		if (file_exists(self::FEEDBACK_CONTENT_PATH_PREFIX . self::FEEDBACK_HEADERS_PREFIX)===true)
			$this->htmlContent=str_replace("{HEADERS_CODE}",file_get_contents(self::FEEDBACK_CONTENT_PATH_PREFIX . self::FEEDBACK_HEADERS_PREFIX),$this->htmlContent);
		else{
			$this->htmlContent="<div id='main_content'><h1>Не найден файл с рабочими заголовками для браузера. Проверьте его наличие по указанному пути functional/feedback/content/". self::FEEDBACK_HEADERS_PREFIX ."</h1></div>";
			return false;
		}
		if (file_exists(self::FEEDBACK_CONTENT_PATH_PREFIX . self::FEEDBACK_BODY_PREFIX)===true)
			$this->htmlContent=str_replace("{BODY_CODE}",file_get_contents(self::FEEDBACK_CONTENT_PATH_PREFIX . self::FEEDBACK_BODY_PREFIX),$this->htmlContent);
		else{
			$this->htmlContent="<div id='main_content'><h1>Не найден файл с html-кодом формы обратной связи. Проверьте его наличие по указанному пути functional/feedback/content/". self::FEEDBACK_BODY_PREFIX ."</h1></div>";
			return false;
		}
	}
	
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>