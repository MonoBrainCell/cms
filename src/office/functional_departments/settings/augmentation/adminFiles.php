<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_settings_adminFiles {
	const FORM_TEMPLATE_PATH="functional_departments/settings/dummyes/adminFiles_block.fbd.tpl";
	const ELEM_TEMPLATE_PATH="functional_departments/settings/dummyes/adminFiles_elem.fbd.tpl";
	const OPTGROUP_TEMP="<optgroup label='{GROUP_NAME}'>{OPTIONS}</optgroup>";
	const SIMPLE_DIRECTORY="../files/media/images/";
	private $htmlContent;

	public function __construct(){
		if (file_exists(self::FORM_TEMPLATE_PATH)===false){
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		if (isset($_POST['directory_view'])===true)
			$curDir=$_POST['directory_view'];
		else
			$curDir=self::SIMPLE_DIRECTORY;

		$initObj=new App_settings_availableFolder();
		$access=$initObj->checkId();
		if ($access===0){
			$this->htmlContent="<div id='main_content'><h1>Вы не имеете необходимого уровня доступа к системе загрузки файлов.</h1></div>";
			return false;
		}
		$accessXML=$initObj->checkXML($access);
		if ($accessXML===false){
			$this->htmlContent="<div id='main_content'><h1>Произошёл сбой загрузки данных: были переданы не все данные или данные были переданы с ошибкой.</h1></div>";
			return false;
		}
		
		$this->htmlContent=file_get_contents(self::FORM_TEMPLATE_PATH);
		$inf=self::generateOptions($accessXML,$curDir,$access);
		$this->htmlContent=str_replace("{DIRECTORY}",$curDir,$this->htmlContent);
		$this->htmlContent=str_replace("{DIRECTORIES}",$inf["all"],$this->htmlContent);
		$this->htmlContent=str_replace("{DIRECTORY_NAME}",$inf["current"],$this->htmlContent);
		$this->htmlContent=str_replace("{FILES_ELEMENT}",self::generateDirContent($curDir),$this->htmlContent);
	}
	private function generateOptions($xPathChunk,$curDir,$access){
		$str="";
		for ($a=0;$a<$xPathChunk->length;$a++){
			$str.=self::OPTGROUP_TEMP;
			$str=str_replace("{GROUP_NAME}",$xPathChunk->item($a)->attributes->getNamedItem("name")->nodeValue,$str);
			$childrens=$xPathChunk->item($a)->childNodes;
			$opts="";
			for ($b=0;$b<$childrens->length;$b++){
				if ($childrens->item($b)->attributes->getNamedItem("path")->nodeValue==$curDir)
					$curDir=$childrens->item($b)->attributes->getNamedItem("name")->nodeValue;
				if ($childrens->item($b)->attributes->getNamedItem("access")->nodeValue<=$access)
					$opts.="<option value='".
					$childrens->item($b)->attributes->getNamedItem("path")->nodeValue
					."'>".
					$childrens->item($b)->attributes->getNamedItem("name")->nodeValue
					."</option>\r\n";
			}
			$str=str_replace("{OPTIONS}",$opts,$str);
		}
		$inf=array("current"=>$curDir,"all"=>$str);
		return $inf;
	}
	private function generateDirContent($curDir){
		if (file_exists(self::ELEM_TEMPLATE_PATH)===false)
			return "";
		else
			$elemTmp=file_get_contents(self::ELEM_TEMPLATE_PATH);
		$files=glob($curDir."*",GLOB_NOSORT);
		if (empty($files)===true)
			return "<p>Запрашиваемая директория: пуста.</p>";
		else if($files===false)
			return "<p>Произошла ошибка при проверке содержимого директории.</p>";
		else{
			$str="";
			for ($a=0,$b=count($files);$a<$b;$a++){
				if (is_file($files[$a])===true){
					$str.=str_replace("{FILE_NAME}",basename($files[$a]),$elemTmp);
					$str=str_replace("{FILE_ADDRESS}",$files[$a],$str);
				}
			}
			return $str;
		}
	}
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>