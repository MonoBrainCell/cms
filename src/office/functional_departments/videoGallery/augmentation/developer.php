<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_videoGallery_developer {
	const WIDGET_FILE="../functional/videoGallery/videosList.fbd.csv";
	const WIDGET_DEV_CONTEINER="functional_departments/videoGallery/dummyes/redactVideo_conteiner.fbd.tpl";
	const WIDGET_DEV_VIEW="functional_departments/videoGallery/dummyes/redactVideo_viewVideo.fbd.tpl";
	const WIDGET_DEV_CODE="functional_departments/videoGallery/dummyes/redactVideo_codeInsert.fbd.tpl";
	private $htmlContent;
	public function __construct(){
		$GETInf=new manipulateGETInf();
		$vId=$GETInf->searchGETElem("vId");
		if ($vId===false){
			$this->htmlContent="<div id='main_content'><h1>Не был передан основной параметр для редактирования видео-вставки. Для доступа к функционалу пользуйтесь навигацией в административной части.</h1></div>";
			return false;
		}
		if (file_exists(self::WIDGET_FILE)===true)
			$widgets=file(self::WIDGET_FILE);
		else if(file_exists(self::WIDGET_FILE)===false && $vId=="new"){
			$widgets=array();
		}
		else{
			$this->htmlContent="<div id='main_content'><h1>Файл со списком видео-вставок отсутствует</h1></div>";
			return false;
		}
		if ((empty($widgets)===true || strlen(trim($widgets[0]))<1) && $vId!="new"){
			$this->htmlContent="<div id='main_content'><h1>Файл со списком видео-вставок повреждён</h1></div>";
			return false;
		}
		if (file_exists(self::WIDGET_DEV_CONTEINER)===false || file_exists(self::WIDGET_DEV_VIEW)===false || file_exists(self::WIDGET_DEV_CODE)===false){
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		if ($vId!="new"){
			for($a=0,$b=count($widgets);$a<$b;$a++){
				$innerArr=explode(";;",trim($widgets[$a]));
				if ($innerArr[0]==$vId){
					$params=$innerArr;
					break;
				}
			}
			if (isset($params)===false){
				$this->htmlContent="<div id='main_content'><h1>Такой видео-вставки не существует</h1></div>";
				return false;
			}
			
		}
		else 
			$params=array("new","","","");
		self::genContent($params);
		
	}

	private function genContent($settings){
		$mainTemp=file_get_contents(self::WIDGET_DEV_CONTEINER);
		$this->htmlContent=str_replace("{VIDEO_ID}",$settings[0],$mainTemp);
		$this->htmlContent=str_replace("{VIDEO_NAME}",$settings[1],$this->htmlContent);
		$this->htmlContent=str_replace("{VIDEO_WIDTH}",$settings[2],$this->htmlContent);
		$this->htmlContent=str_replace("{VIDEO_HEIGHT}",$settings[3],$this->htmlContent);
		if ($settings[0]!="new"){
			$this->htmlContent=str_replace("{VARIABLE_PART}",file_get_contents(self::WIDGET_DEV_VIEW),$this->htmlContent);
			$this->htmlContent=str_replace("{VIDEO_SOURCE}",$settings[4],$this->htmlContent);
		}
		else
			$this->htmlContent=str_replace("{VARIABLE_PART}",file_get_contents(self::WIDGET_DEV_CODE),$this->htmlContent);
	}
	
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>