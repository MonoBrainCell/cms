<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_imagesGallery_developer {
	const WIDGET_FILE="../functional/imagesGallery/gallerysList.fbd.csv";
	const WIDGET_DEV_CONTEINER="functional_departments/imagesGallery/dummyes/galleryRedact_totalBlock.fbd.tpl";
	const WIDGET_DEV_ELEM="functional_departments/imagesGallery/dummyes/galleryRedact_elem.fbd.tpl";
	const WIDGET_BIG_LIST_PATH="../functional/imagesGallery/content/";
	private $htmlContent;
	public function __construct(){
		$GETInf=new manipulateGETInf();
		$gId=$GETInf->searchGETElem("gId");
		if ($gId===false){
			$this->htmlContent="<div id='main_content'><h1>Не был передан основной параметр для редактирования галереи изображений. Для доступа к функционалу пользуйтесь навигацией в административной части.</h1></div>";
			return false;
		}
		if (file_exists(self::WIDGET_FILE)===true)
			$widgets=file(self::WIDGET_FILE);
		else if(file_exists(self::WIDGET_FILE)===false && $gId=="new"){
			$widgets=array();
		}
		else{
			$this->htmlContent="<div id='main_content'><h1>Файл со списком галерей изображений отсутствует</h1></div>";
			return false;
		}
		if ((empty($widgets)===true || strlen(trim($widgets[0]))<1) && $gId!="new"){
			$this->htmlContent="<div id='main_content'><h1>Файл со списком галерей изображений повреждён</h1></div>";
			return false;
		}
		if (file_exists(self::WIDGET_DEV_CONTEINER)===false || file_exists(self::WIDGET_DEV_ELEM)===false){
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		if ($gId!="new"){
			for($a=0,$b=count($widgets);$a<$b;$a++){
				$innerArr=explode(";;",trim($widgets[$a]));
				if ($innerArr[0]==$gId){
					$params=$innerArr;
					break;
				}
			}
			if (isset($params)===false){
				$this->htmlContent="<div id='main_content'><h1>Такой галереи не существует</h1></div>";
				return false;
			}
			
		}
		else 
			$params=array("new","","separate","");
		self::genContent($params);
		
	}

	private function genContent($settings){
		$mainTemp=file_get_contents(self::WIDGET_DEV_CONTEINER);
		$this->htmlContent=str_replace("{GALLERY_ID}",$settings[0],$mainTemp);
		$this->htmlContent=str_replace("{GALLERY_NAME}",$settings[1],$this->htmlContent);
		if ($settings[2]=="separate"){
			$this->htmlContent=str_replace("{SEPARATE_CHECKED_MARKER}"," checked='checked'",$this->htmlContent);
			$this->htmlContent=str_replace("{BUILT-IN_CHECKED_MARKER}","",$this->htmlContent);
		}
		else {
			$this->htmlContent=str_replace("{SEPARATE_CHECKED_MARKER}","",$this->htmlContent);
			$this->htmlContent=str_replace("{BUILT-IN_CHECKED_MARKER}"," checked='checked'",$this->htmlContent);
		}
		if ($settings[0]=="new")
			$this->htmlContent=str_replace("{VARIABLE_PART}","",$this->htmlContent);
		else {
			$variableContent=self::genVariablePart($settings[0],$settings[3]);
			$this->htmlContent=str_replace("{VARIABLE_PART}",$variableContent,$this->htmlContent);
		}
		$this->htmlContent=str_replace("{H1}","<h1>Редактирование галереи</h1>",$this->htmlContent);
	}
	
	private function genVariablePart($elemId,$string){
		if ($string=="codeIns"){
			if (file_exists(self::WIDGET_BIG_LIST_PATH .$elemId.".fbd.csv")===true)
				$string=file_get_contents(self::WIDGET_BIG_LIST_PATH .$elemId.".fbd.csv");
			else {
				$this->htmlContent="<div id='main_content'><h1>Не найден файл с контентом галереи. Проверьте наличие файла ".$elemId.".fbd.csv в папке functional/galleryImages/content</h1></div>";
				return false;
			}
		}
		$imagesArr=explode(",,",$string);
		$elemTemp=file_get_contents(self::WIDGET_DEV_ELEM);
		$newString="";
		if (empty($imagesArr)===false){
			for ($a=0,$b=count($imagesArr);$a<$b;$a++){
				$elem=explode("::",$imagesArr[$a]);
				if ($elem[2]!="0")
					$newString.=str_replace("{GROUP_NAME}",$elem[2],$elemTemp);
				else
					$newString.=str_replace("{GROUP_NAME}","",$elemTemp);
				
				if ($elem[1]!="0"){
					$newString=str_replace("{PREVIEW_IMG_SRC}",$elem[1],$newString);
					$newString=str_replace("{PP_PREVIEW_MARKER}"," rel='prettyPhoto[album0]'",$newString);
				}
				else {
					$newString=str_replace("{PREVIEW_IMG_SRC}","",$newString);
					$newString=str_replace("{PP_PREVIEW_MARKER}","",$newString);
				}
				
				if ($elem[0]!="0"){
					$newString=str_replace("{ORIGINAL_IMG_SRC}",$elem[0],$newString);
					$newString=str_replace("{PP_ORIGINAL_MARKER}"," rel='prettyPhoto[album0]'",$newString);
				}
				else {
					$newString=str_replace("{ORIGINAL_IMG_SRC}","",$newString);
					$newString=str_replace("{PP_ORIGINAL_MARKER}","",$newString);
				}	
			}
		}
		else {
			$elem=explode("::",$string);
			if ($elem[2]!="0")
				$newString.=str_replace("{GROUP_NAME}",$elem[2],$elemTemp);
			else
				$newString.=str_replace("{GROUP_NAME}","",$elemTemp);
				
			if ($elem[1]!="0"){
				$newString=str_replace("{PREVIEW_IMG_SRC}",$elem[1],$newString);
				$newString=str_replace("{PP_PREVIEW_MARKER}"," rel='prettyPhoto[album0]'",$newString);
			}
			else {
				$newString=str_replace("{PREVIEW_IMG_SRC}","",$newString);
				$newString=str_replace("{PP_PREVIEW_MARKER}","",$newString);
			}
			
			if ($elem[0]!="0"){
				$newString=str_replace("{ORIGINAL_IMG_SRC}",$elem[0],$newString);
				$newString=str_replace("{PP_ORGINAL_MARKER}"," rel='prettyPhoto[album0]'",$newString);
			}
			else {
				$newString=str_replace("{ORIGINAL_IMG_SRC}","",$newString);
				$newString=str_replace("{PP_ORIGINAL_MARKER}","",$newString);
			}
		}
		return $newString;
	}
	
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>