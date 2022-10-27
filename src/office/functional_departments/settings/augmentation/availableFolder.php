<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_settings_availableFolder{
	const MANAGMENT_MAP_PATH="functional_departments/settings/settings/filesManagmentMap.fbd.xml";
	const ACCESS_FILE="gatekeeper/users.fbd.xml";
	
	public function __construct(){ }
	public function checkId(){
		$accesses=array("sa"=>2,"a"=>1,"v"=>0);
		$encryptMaster=new Wspc__MasterEncryption();
		if (isset($_SESSION['master'])===false)
			return 0;
		$tempInf=$encryptMaster->cryptDecryptDatas($_SESSION['master'],'Session Datas',"2");
		$tempInf=stripslashes($tempInf);
		if (@unserialize($tempInf)===false)
			return 0;
		$tempArr=@unserialize($tempInf);
		if (isset($tempArr['name'])===false || isset($tempArr['client'])===false)
			return 0;
		
		if (file_exists(self::ACCESS_FILE)===true){
			$file=new DOMDocument();
			$file->load(self::ACCESS_FILE);
			$xPathDoc=new DOMXPath($file);
			$accessXML=$xPathDoc->query("/data/users/user[@login='{$tempArr['name']}']/@type");
			if ($accessXML->length===0)
				return 0;
			if (array_key_exists($accessXML->item(0)->nodeValue,$accesses)===false)
				return 0;
			return $accesses[$accessXML->item(0)->nodeValue];
		}
		else 
			return 0;
	}
	public function checkXML($access,$dir=false){
		if ($access===0)
			return false;
		if (file_exists(self::MANAGMENT_MAP_PATH)===true){
			$file=new DOMDocument();
			$file->formatOutput=true;
			$file->preserveWhiteSpace=false;
			$file->load(self::MANAGMENT_MAP_PATH);
			$xPathDoc=new DOMXPath($file);
			if ($dir===false)
				$accessXML=$xPathDoc->query("/directories/group[dir[@access<='{$access}']]");
			else
				$accessXML=$xPathDoc->query("/directories/group/dir[@access<='{$access}' and @path='{$dir}']");
			if ($accessXML->length===0)
				return false;
			return $accessXML;
		}
		else 
			return false;
	}
}
?>