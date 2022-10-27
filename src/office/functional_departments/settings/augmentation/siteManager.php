<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_settings_siteManager {
	const NAVIGATION_FILE="../navigation.fbd.xml";
	const FILE_OF_USERS_KEYS="../settings.fbd.php";
	const SETTINGS_CONTEINER="functional_departments/settings/dummyes/siteManager_conteiner.fbd.tpl";
	const SETTINGS_GROUP="functional_departments/settings/dummyes/siteManager_group.fbd.tpl";
	const SETTINGS_ELEMENT="functional_departments/settings/dummyes/siteManager_elem.fbd.tpl";
	const SETTINGS_ENCLAVE_ELEMENT="functional_departments/settings/dummyes/siteManager_addForEnclave.fbd.tpl";
	private $htmlContent;
	public function __construct(){
		$GETInf=new manipulateGETInf();
		$wId=$GETInf->searchGETElem("wId");
		if (file_exists(self::NAVIGATION_FILE)===false){
			$this->htmlContent="<div id='main_content'><h1>Файл со списком страниц сайта отсутствует. Обязательно закажите Back Up сайта.</h1></div>";
			return false;
		}
		if (file_exists(self::FILE_OF_USERS_KEYS)===false){
			$this->htmlContent="<div id='main_content'><h1>Файл с авторизационными данными отсутствует. Обязательно закажите Back Up сайта.</h1></div>";
			return false;
		}
		if (file_exists(self::SETTINGS_CONTEINER)===false || file_exists(self::SETTINGS_GROUP)===false || file_exists(self::SETTINGS_ELEMENT)===false || file_exists(self::SETTINGS_ENCLAVE_ELEMENT)===false){
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		include self::FILE_OF_USERS_KEYS;
		if ($siteEngineSwitcher=="on"){
			$this->htmlContent=str_replace("{SITE_ENGINE_ON}",' checked="checked"',file_get_contents(self::SETTINGS_CONTEINER));
			$this->htmlContent=str_replace("{SITE_ENGINE_OFF}",'',$this->htmlContent);
		}
		else {
			$this->htmlContent=str_replace("{SITE_ENGINE_ON}",'',file_get_contents(self::SETTINGS_CONTEINER));
			$this->htmlContent=str_replace("{SITE_ENGINE_OFF}",' checked="checked"',$this->htmlContent);
		}
		
		self::genContent(self::repackSettings($functional));
	}

	private function genContent($settings){
		$content="";
		foreach ($settings as $type=>$data){
			if ($type!="enclaveEngines")
				$content .=self::genGroup($data,$type."_");
			else
				$content .=self::genEnclave($data,$type."_");
		}
		$this->htmlContent=str_replace("{VARIABLE_PART}",$content,$this->htmlContent);
	}
	
	private function genGroup($settings,$type){
		$tmp=file_get_contents(self::SETTINGS_GROUP);
		$groups="";
		for ($a=0,$b=count($settings);$a<$b;$a++){
			$groups.=str_replace("{GROUP_NAME}",$settings[$a]['name'],$tmp);
			$typePostfix=str_replace("Pre__","",$settings[$a]['className'])."_";
			$groups=str_replace("{VARIABLE_PART}",self::genElements($settings[$a]['ramification'],$type.$typePostfix),$groups);
		}
		
		return $groups;
	}
	
	private function genElements($settings,$typePrefix){
		$tmp=file_get_contents(self::SETTINGS_ELEMENT);
		$elements="";
		for ($a=0,$b=count($settings);$a<$b;$a++){
			$elements.=str_replace("{FUNC_NAME}",$settings[$a]['name'],$tmp);
			$elements=str_replace("{FUNC_ALIAS}",$typePrefix.$settings[$a]['methodName'],$elements);
			if (isset($settings[$a]['switcherFixed'])===true && $settings[$a]['engineSwitcher']=='off'){
				$elements=str_replace("{DISABLED_ON}",' disabled="disabled"',$elements);
				$elements=str_replace("{DISABLED_OFF}",'',$elements);
			}
			else if (isset($settings[$a]['switcherFixed'])===true && $settings[$a]['engineSwitcher']=='on'){
				$elements=str_replace("{DISABLED_ON}",'',$elements);
				$elements=str_replace("{DISABLED_OFF}",' disabled="disabled"',$elements);
			}
			else {
				$elements=str_replace("{DISABLED_ON}",'',$elements);
				$elements=str_replace("{DISABLED_OFF}",'',$elements);
			}
			if ($settings[$a]['engineSwitcher']=='on'){
				$elements=str_replace("{SELECTED_ON}",' checked="checked"',$elements);
				$elements=str_replace("{SELECTED_OFF}",'',$elements);
			}
			else {
				$elements=str_replace("{SELECTED_ON}",'',$elements);
				$elements=str_replace("{SELECTED_OFF}",' checked="checked"',$elements);
			}
			
		}
		return $elements;
	}
	
	private function genEnclave($settings,$typePrefix){
		$tmp=file_get_contents(self::SETTINGS_ENCLAVE_ELEMENT);
		$string="";
		for ($a=0,$b=count($settings);$a<$b;$a++){
			$string.=str_replace("{FUNC_NAME}",$settings[$a]['name'],$tmp);
			$typePostfix=str_replace('.php','',$settings[$a]['engineName']);
			$string=str_replace("{FUNC_ALIAS}",$typePrefix.$typePostfix,$string);
			
			if (isset($settings[$a]['switcherFixed'])===true && $settings[$a]['engineSwitcher']=='off'){
				$string=str_replace("{DISABLED_ON}",' disabled="disabled"',$string);
				$string=str_replace("{DISABLED_OFF}",'',$string);
			}
			else if (isset($settings[$a]['switcherFixed'])===true && $settings[$a]['engineSwitcher']=='on'){
				$string=str_replace("{DISABLED_ON}",'',$string);
				$string=str_replace("{DISABLED_OFF}",' disabled="disabled"',$string);
			}
			else {
				$string=str_replace("{DISABLED_ON}",'',$string);
				$string=str_replace("{DISABLED_OFF}",'',$string);
			}
			
			if ($settings[$a]['engineSwitcher']=='on'){
				$string=str_replace("{SELECTED_ON}",' checked="checked"',$string);
				$string=str_replace("{SELECTED_OFF}",'',$string);
			}
			else {
				$string=str_replace("{SELECTED_ON}",'',$string);
				$string=str_replace("{SELECTED_OFF}",' checked="checked"',$string);
			}
		}
		return $string;
	}
	
	private function filterNavigationToEnclave($navigationArray){
		$enclaves=array();
		for ($a=0,$b=count($navigationArray);$a<$b;$a++){
			$elem=explode(";;",$navigationArray[$a]);
			if ($elem[4]=="1"){
				$enclaves[]=$elem[1];
			}
		}
		return $enclaves;
	}
	
	private function repackSettings($string){
		return unserialize(stripslashes($string));
	}
	
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>