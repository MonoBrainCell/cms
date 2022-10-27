<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_settings_loadFiles {
	const FORM_TEMPLATE_PATH="functional_departments/settings/dummyes/loadFiles_block.fbd.tpl";
	const OPTGROUP_TEMP="<optgroup label='{GROUP_NAME}'>{OPTIONS}</optgroup>";
	const MAX_FILES_QUANTITY=10;
	private $htmlContent;

	public function __construct(){
		if (file_exists(self::FORM_TEMPLATE_PATH)===false){
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		if (isset($_POST['controlElem'])===true){
			$varObj=new App_settings_filesManager();
			exit;
		}
		$GETInf=new manipulateGETInf();
		$msg=$GETInf->searchGETElem("msg");
		$initObj=new App_settings_availableFolder();
		$access=$initObj->checkId();
		if ($access===0){
			$this->htmlContent="<div id='main_content'><h1>Вы не имеете необходимого уровня доступа к системе загрузки файлов.</h1></div>";
			return false;
		}
		$accessXML=$initObj->checkXML($access);
		if ($accessXML===false){
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		
		switch ($msg){
			case "params_failed":// не соответствует число параметров (опред. директории и файла)
				$errMsg="<p><span class='pay_attention'>Произошёл сбой загрузки данных: были переданы не все данные или данные были переданы с ошибкой</span>. Обновите страницу в браузере и повторите попытку</p>";
			break;
			
			case "failed_some_files":// не все файлы загрузились
				$errMsg="<p>Были загружены не все файлы";
				if (isset($_SESSION['failedFiles'])===true){
					$ffl=array();
					for ($a=0,$b=count($_SESSION['failedFiles']);$a<$b;$a++){
						$ffl[]=$_SESSION['failedFiles'][$a];
					}
					$fflStr=implode(", ",$ffl);
					$errMsg.=" :<span class='pay_attention'>{$fflStr}</span></p>";
					unset($_SESSION['failedFiles']);
				}
				else 
					$errMsg.="</p>";
			break;
			case "success":// загрузка прошла успешно
				$errMsg="<p><span class='pay_attention'>Все файлы были загружены успешно</span></p>";
			break;
			default:
				$errMsg="";
		}
		
		$this->htmlContent=file_get_contents(self::FORM_TEMPLATE_PATH);
		$this->htmlContent=str_replace("{ERRORS_MESSAGE}",$errMsg,$this->htmlContent);
		$this->htmlContent=str_replace("{MAX_FILES_QUANTITY}",self::MAX_FILES_QUANTITY,$this->htmlContent);
		$this->htmlContent=str_replace("{MAX_FILESIZE}",ini_get("upload_max_filesize"),$this->htmlContent);
		$this->htmlContent=str_replace("{DIRECTORIES}",self::generateOptions($accessXML,$access),$this->htmlContent);
	}
	private function generateOptions($xPathChunk,$access){
		$str="";
		for ($a=0;$a<$xPathChunk->length;$a++){
			$str.=self::OPTGROUP_TEMP;
			$str=str_replace("{GROUP_NAME}",$xPathChunk->item($a)->attributes->getNamedItem("name")->nodeValue,$str);
			$childrens=$xPathChunk->item($a)->childNodes;
			$opts="";
			for ($b=0;$b<$childrens->length;$b++){
				if ($childrens->item($b)->attributes->getNamedItem("access")->nodeValue<=$access)
					$opts.="<option value='".
					$childrens->item($b)->attributes->getNamedItem("path")->nodeValue
					."'>".
					$childrens->item($b)->attributes->getNamedItem("name")->nodeValue
					."</option>\r\n";
			}
			$str=str_replace("{OPTIONS}",$opts,$str);
		}
		return $str;
	}
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>