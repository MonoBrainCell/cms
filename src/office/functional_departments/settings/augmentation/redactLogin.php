<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_settings_redactLogin extends ErrorsManager{
	const ACCESS_FILE="gatekeeper/users.fbd.xml";
	const CONTENT_TPL_PATH="functional_departments/settings/dummyes/redactLogin.fbd.tpl";
	
	private $content;
	
	public function __construct(){
		$accessDesc=array("sa"=>"Управляющий","a"=>"Администратор","v"=>"Посетитель");
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
			$accessXML=$xPathDoc->query("/data/users/user[@login='{$login}']");
		}
		if (file_exists(self::CONTENT_TPL_PATH)===true)
			$contentTemplate=file_get_contents(self::CONTENT_TPL_PATH);
		else
			$contentTemplate=false;
		if ($file===false || $accessXML->length===0){
			$this->content="<div id='main_content'><h1>Файл users.fbd.xml, расположенный по пути: office/gatekeeper/ повреждён или отстутствует.</h1></div>";
			return false;
		}
		$this->content=str_replace("{LOGIN}",$login,$contentTemplate);
		if (array_key_exists($accessXML->item(0)->attributes->getNamedItem("type")->nodeValue,$accessDesc)===true)
			$type=$accessDesc[$accessXML->item(0)->attributes->getNamedItem("type")->nodeValue];
		else 
			$type=$accessDesc["v"];
		$this->content=str_replace("{ACCESS_TYPE}",$type,$this->content);
		
	}
	
	public function getHtmlPiece() {
		return $this->content;
	}
}
?>