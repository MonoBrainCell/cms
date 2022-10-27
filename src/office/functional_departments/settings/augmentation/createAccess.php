<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_settings_createAccess extends ErrorsManager{
	const ACCESS_FILE="gatekeeper/users.fbd.xml";
	const CONTENT_TPL_PATH="functional_departments/settings/dummyes/createAccess.fbd.tpl";
	const OPTION_TEMPLATE="\r\n<option value='{VALUE}'>{VALUE_TEXT}</option>";
	const AVAILABLE_ACCESS_NUM=4;
	
	private $content;
	
	public function __construct(){
		$accessDesc=array("a"=>"Администратор","v"=>"Посетитель");
		$accessNums=array("a"=>3,"v"=>1);
		if (file_exists(self::ACCESS_FILE)===true){
			$file=new DOMDocument();
			$file->load(self::ACCESS_FILE);
		}
		else
			$file=false;
		if ($file!==false){
			$xPathDoc=new DOMXPath($file);
			$accessXML=$xPathDoc->query("/data/users/user[@type!='sa']");
		}
		if (file_exists(self::CONTENT_TPL_PATH)===true)
			$contentTemplate=file_get_contents(self::CONTENT_TPL_PATH);
		else
			$contentTemplate=false;
		if ($file===false || $accessXML->length===0){
			$this->content="<div id='main_content'><h1>Файл users.fbd.xml, расположенный по пути: office/gatekeeper/ повреждён или отстутствует.</h1></div>";
			return false;
		}
		if ($accessXML->length>=self::AVAILABLE_ACCESS_NUM){
			$this->content="<div id='main_content'><h1>Невозможно создать учетную запись. Достигнут предел максимального количества учетных записей администраторов сайта.</h1></div>";
			return false;
		}
		$options="";
		foreach ($accessNums as $type=>$num){
			$accessXML=$xPathDoc->query("/data/users/user[@type='{$type}']");
			if ($accessXML->length<$num){
				$options.=str_replace("{VALUE}",$type,self::OPTION_TEMPLATE);
				$options=str_replace("{VALUE_TEXT}",$accessDesc[$type],$options);
			}
		}
		$this->content=str_replace("{OPTIONS}",$options,$contentTemplate);		
	}
	
	public function getHtmlPiece() {
		return $this->content;
	}
}
?>