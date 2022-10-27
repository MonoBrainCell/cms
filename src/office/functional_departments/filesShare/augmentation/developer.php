<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_filesShare_developer {
	const WIDGET_FILE="../functional/filesShare/filesList.fbd.csv";
	const WIDGET_DEV_CONTEINER="functional_departments/filesShare/dummyes/filesListRedact_totalBlock.fbd.tpl";
	const WIDGET_DEV_ELEM="functional_departments/filesShare/dummyes/filesListRedact_elem.fbd.tpl";
	const WIDGET_BIG_LIST_PATH="../functional/filesShare/content/";
	private $htmlContent;
	public function __construct(){
		$GETInf=new manipulateGETInf();
		$fId=$GETInf->searchGETElem("fId");
		if ($fId===false){
			$this->htmlContent="<div id='main_content'><h1>Не был передан основной параметр для редактирования списка файлов. Для доступа к функционалу пользуйтесь навигацией в административной части.</h1></div>";
			return false;
		}
		if (file_exists(self::WIDGET_FILE)===true)
			$widgets=file(self::WIDGET_FILE);
		else if(file_exists(self::WIDGET_FILE)===false && $fId=="new"){
			$widgets=array();
		}
		else{
			$this->htmlContent="<div id='main_content'><h1>Файл с реестром списков файлов для скачивания отсутствует</h1></div>";
			return false;
		}
		if ((empty($widgets)===true || strlen(trim($widgets[0]))<1) && $fId!="new"){
			$this->htmlContent="<div id='main_content'><h1>Файл с реестром списков файлов для скачивания повреждён</h1></div>";
			return false;
		}
		if (file_exists(self::WIDGET_DEV_CONTEINER)===false || file_exists(self::WIDGET_DEV_ELEM)===false){
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		if ($fId!="new"){
			for($a=0,$b=count($widgets);$a<$b;$a++){
				$innerArr=explode(";;",trim($widgets[$a]));
				if ($innerArr[0]==$fId){
					$params=$innerArr;
					break;
				}
			}
			if (isset($params)===false){
				$this->htmlContent="<div id='main_content'><h1>Такого списка не существует</h1></div>";
				return false;
			}
			
		}
		else 
			$params=array("new","","");
		self::genContent($params);
		
	}

	private function genContent($settings){
		$mainTemp=file_get_contents(self::WIDGET_DEV_CONTEINER);
		$this->htmlContent=str_replace("{FL_ID}",$settings[0],$mainTemp);
		$this->htmlContent=str_replace("{FL_NAME}",$settings[1],$this->htmlContent);
		
		if ($settings[0]=="new")
			$this->htmlContent=str_replace("{VARIABLE_PART}","",$this->htmlContent);
		else {
			$variableContent=self::genVariablePart($settings[0],$settings[2]);
			$this->htmlContent=str_replace("{VARIABLE_PART}",$variableContent,$this->htmlContent);
		}
		$this->htmlContent=str_replace("{H1}","<h1>Редактирование списка файлов</h1>",$this->htmlContent);
	}
	
	private function genVariablePart($elemId,$string){
		if ($string=="codeIns"){
			if (file_exists(self::WIDGET_BIG_LIST_PATH .$elemId.".fbd.csv")===true)
				$string=file_get_contents(self::WIDGET_BIG_LIST_PATH .$elemId.".fbd.csv");
			else {
				$this->htmlContent="<div id='main_content'><h1>Не найден файл с контентом списка файлов. Проверьте наличие файла ".$elemId.".fbd.csv в папке functional/filesShare/content</h1></div>";
				return false;
			}
		}
		$imagesArr=explode(",,",$string);
		$elemTemp=file_get_contents(self::WIDGET_DEV_ELEM);
		$newString="";
		if (empty($imagesArr)===false){
			for ($a=0,$b=count($imagesArr);$a<$b;$a++){
				$elem=explode("::",$imagesArr[$a]);
				$newString.=str_replace("{FILE_NAME}",$elem[1],$elemTemp);
				$newString=str_replace("{FILE_BASENAME}",$elem[0],$newString);
			}
		}
		else {
			$elem=explode("::",$string);
			$newString.=str_replace("{FILE_NAME}",$elem[1],$elemTemp);
			$newString=str_replace("{FILE_BASENAME}",$elem[0],$newString);
		}
		return $newString;
	}
	
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>