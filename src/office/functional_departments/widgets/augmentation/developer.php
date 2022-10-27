<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_widgets_developer {
	const WIDGET_FILE="../functional/widgets/widgetsList.fbd.csv";
	const WIDGET_DEV_CONTEINER="functional_departments/widgets/dummyes/redactWidget_conteiner.fbd.tpl";
	const WIDGET_CONTENT_PATH_PREFIX="../functional/widgets/content/";
	const WIDGET_HEADERS_PREFIX="wsHeaders/{ID}.fbd.tpl";
	const WIDGET_BODY_PREFIX="wsContent/{ID}.fbd.tpl";
	private $htmlContent;
	public function __construct(){
		$GETInf=new manipulateGETInf();
		$wId=$GETInf->searchGETElem("wId");
		if ($wId===false){
			$this->htmlContent="<div id='main_content'><h1>Не был передан основной параметр для редактирования виджета. Для доступа к функционалу пользуйтесь навигацией в административной части.</h1></div>";
			return false;
		}
		if (file_exists(self::WIDGET_FILE)===true)
			$widgets=file(self::WIDGET_FILE);
		else if(file_exists(self::WIDGET_FILE)===false && $wId=="new"){
			$widgets=array();
		}
		else{
			$this->htmlContent="<div id='main_content'><h1>Файл со списком виджетов отсутствует</h1></div>";
			return false;
		}
		if ((empty($widgets)===true || strlen(trim($widgets[0]))<1) && $wId!="new"){
			$this->htmlContent="<div id='main_content'><h1>Файл со списком виджетов повреждён</h1></div>";
			return false;
		}
		if (file_exists(self::WIDGET_DEV_CONTEINER)===false){
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		if ($wId!="new"){
			for($a=0,$b=count($widgets);$a<$b;$a++){
				$innerArr=explode(";;",trim($widgets[$a]));
				if ($innerArr[0]==$wId){
					$params=$innerArr;
					break;
				}
			}
			if (isset($params)===false){
				$this->htmlContent="<div id='main_content'><h1>Такого виджета не существует</h1></div>";
				return false;
			}
		}
		else 
			$params=array("new","");
		self::genContent($params);
		
	}

	private function genContent($settings){
		$mainTemp=file_get_contents(self::WIDGET_DEV_CONTEINER);
		$this->htmlContent=str_replace("{WIDGET_ID}",$settings[0],$mainTemp);
		$this->htmlContent=str_replace("{WID_NAME}",$settings[1],$this->htmlContent);
		$headersFilePath=str_replace("{ID}",$settings[0],self::WIDGET_CONTENT_PATH_PREFIX . self::WIDGET_HEADERS_PREFIX);
		$bodyFilePath=str_replace("{ID}",$settings[0],self::WIDGET_CONTENT_PATH_PREFIX . self::WIDGET_BODY_PREFIX);
		$entityConvert=new maskHTMLEntity(true);
		if (file_exists($headersFilePath)===true)
			$this->htmlContent=str_replace("{HEADERS_CODE}",$entityConvert->maskEngine(file_get_contents($headersFilePath)),$this->htmlContent);
		else
			$this->htmlContent=str_replace("{HEADERS_CODE}","",$this->htmlContent);
		if (file_exists($bodyFilePath)===true)
			$this->htmlContent=str_replace("{BODY_CODE}",$entityConvert->maskEngine(file_get_contents($bodyFilePath)),$this->htmlContent);
		else
			$this->htmlContent=str_replace("{BODY_CODE}","",$this->htmlContent);
	}
	
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>