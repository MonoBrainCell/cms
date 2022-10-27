<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_settings_redactAccessType extends ErrorsManager{
	const ACCESS_FILE="gatekeeper/users.fbd.xml";
	const CONTENT_TPL_PATH="functional_departments/settings/dummyes/redactAccessType.fbd.tpl";
	const OPTION_TEMPLATE="\r\n<option value='{VALUE}'{SELECTED}>{VALUE_TEXT}</option>";
	const AVAILABLE_ACCESS_NUM=4;
	private $content;
	
	public function __construct(){
		$accessDesc=array("a"=>"Администратор","v"=>"Посетитель");
		if (file_exists(self::ACCESS_FILE)===true){
			$file=new DOMDocument();
			$file->load(self::ACCESS_FILE);
		}
		else
			$file=false;
		$GETInf=new manipulateGETInf();
		$login=$GETInf->searchGETElem("login");
		if ($login===false){
			$this->content="<div id='main_content'><h1>Произошла системная ошибка передачи данных для обработки. Попробуйте повторить попытку использования функционала.</h1></div>";
			return false;
		}
		if (file_exists(self::CONTENT_TPL_PATH)===true)
			$contentTemplate=file_get_contents(self::CONTENT_TPL_PATH);
		else
			$contentTemplate=false;
		if ($file!==false){
			$xPathDoc=new DOMXPath($file);
			$accessXML=$xPathDoc->query("/data/users/user[@login='{$login}']/@type");
		}
		if (file_exists(self::CONTENT_TPL_PATH)===true)
			$contentTemplate=file_get_contents(self::CONTENT_TPL_PATH);
		else
			$contentTemplate=false;
		if ($file===false || $accessXML->length===0){
			$this->content="<div id='main_content'><h1>Файл users.fbd.xml, расположенный по пути: office/gatekeeper/ повреждён или отстутствует.</h1></div>";
			return false;
		}
		if ($accessXML->item(0)->nodeValue=="sa"){
			$this->content="<div id='main_content'><h1>Нельзя изменять тип доступа учетной записи Управляющего сайтом.</h1></div>";
			return false;
		}
		$accessXML1=$xPathDoc->query("/data/users/user[@type!='sa']");
		if ($accessXML1->length>=self::AVAILABLE_ACCESS_NUM){
			$this->content="<div id='main_content'><h1>Доступы распределены по максимально возможному количеству учетных записей. Чтобы иметь возможность изменения типа доступа требуется удалить любую учетную запись.</h1></div>";
			return false;
		}
		$this->content=str_replace("{LOGIN}",$login,$contentTemplate);
		$this->content=str_replace("{ACCESS_TYPE}",$accessDesc[$accessXML->item(0)->nodeValue],$this->content);
		$options="";
		$accessXML1=$xPathDoc->query("/data/users/user[@type='a']");
		if ($accessXML->item(0)->nodeValue=="a"
		||
		($accessXML->item(0)->nodeValue!="a" && $accessXML1->length<3)
		||
		($accessXML->item(0)->nodeValue=="a" && $accessXML1->length==3)
		){
			$options.=str_replace("{VALUE_TEXT}",$accessDesc["a"],self::OPTION_TEMPLATE);
			$options=str_replace("{VALUE}","a",$options);
			if ($accessXML->item(0)->nodeValue=="a")
				$options=str_replace("{SELECTED}"," selected='selected'",$options);
			else
				$options=str_replace("{SELECTED}","",$options);
		}
		
		$accessXML1=$xPathDoc->query("/data/users/user[@type='v']");
		if ($accessXML->item(0)->nodeValue=="v"
		||
		($accessXML->item(0)->nodeValue!="v" && $accessXML1->length<1)
		||
		($accessXML->item(0)->nodeValue=="v" && $accessXML1->length==1)
		){
			$options.=str_replace("{VALUE_TEXT}",$accessDesc["v"],self::OPTION_TEMPLATE);
			$options=str_replace("{VALUE}","v",$options);
			if ($accessXML->item(0)->nodeValue=="v")
				$options=str_replace("{SELECTED}"," selected='selected'",$options);
			else
				$options=str_replace("{SELECTED}","",$options);
		}
		$this->content=str_replace("{OPTIONS}",$options,$this->content);
	}
	
	public function getHtmlPiece() {
		return $this->content;
	}
}
?>