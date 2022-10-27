<?php
class Wspc__Gatekeeper extends ErrorsManager{
	const DOMAIN_FILE="information_base/domain.txt";
	const SITEMAP_REWRITER="functional_departments/settings/executors/rewriteSitemap.php";

	private $masterOfEncrypt;
	private $masterOfSafeguards;
	public $login;
	public $typeOfAccess;
	function __construct(){
		session_start();
		$this->masterOfEncrypt=new Wspc__MasterEncryption();
		$this->masterOfSafeguards=new Wspc__SafeguardMaster($this->masterOfEncrypt);
		if (isset($_GET['logout'])===true){
			unset($_SESSION['master']);
		}
	}
	public function checkAuth(){
		if (isset($_SESSION['master'])===false)
			self::readdressWith(true);
		$tempInf=$this->masterOfEncrypt->cryptDecryptDatas($_SESSION['master'],'Session Datas',"2");
		$tempInf=stripslashes($tempInf);
		if (@unserialize($tempInf)===false)
			self::readdressWith("failed_id_datas");
		$tempArr=unserialize($tempInf);
		if (isset($tempArr['name'])===false || isset($tempArr['client'])===false)
			self::readdressWith("output_produced");
		$tempArr['client']=$this->masterOfEncrypt->extractSymbsFromString($tempArr['client'],5);
		$currentClient=$this->masterOfEncrypt->genHashOfString($_SERVER['HTTP_USER_AGENT'],"2");
		if ($tempArr['client']!=$currentClient)
			self::readdressWith("output_produced");
		$this->login=$tempArr['name'];
		$baseData=$this->masterOfSafeguards->receiveClientDatas();
		if ($baseData===false)
			self::readdressWith("failed_id_datas");
		$checkLogin=$baseData->query("/data/users/user[@login='".$this->login ."']/@type");
		if ($checkLogin->length===0)
			self::readdressWith("output_produced");
		$access=$checkLogin->item(0)->nodeValue;
		$accessNum=self::determineAccess($access);
		$lids=array();
		if ($accessNum>1){
			return $lids;
		}
		$lidsXML=$baseData->query("/data/restrictions/*[@permission>".$accessNum."]");
		for ($a=0;$a<$lidsXML->length;$a++){
			$lids[$a]=self::determineFilePath(
				$lidsXML->item($a)->nodeName,
				$lidsXML->item($a)->attributes->getNamedItem("dep")->nodeValue,
				$lidsXML->item($a)->attributes->getNamedItem("name")->nodeValue
			);
			if ($lids[$a]===false){
				unset($lids[$a]);
			}
		}
		return array_values($lids);
	}
	public function checkAdmission($login,$passw){
		if ($this->masterOfSafeguards->cursoryInspection($login,$passw)===false)
			self::readdressWith("attempt_to_enter_failed");
		else if ($this->masterOfSafeguards->cursoryInspection($login,$passw)==='blocked')
			self::readdressWith("enter_to_admin_blocked");
		if ($this->masterOfSafeguards->scrutiny()===false)
			self::readdressWith("attempt_to_enter_failed");
		else {
			if($this->checkDomainName()===false){
				if(file_exists(self::SITEMAP_REWRITER)===true){
					include self::SITEMAP_REWRITER;
				}
			}
			self::readdressWith();
		}
	}
	private function readdressWith($message=false){
		if ($message===false) {
			echo "<meta http-equiv='refresh' content='0; url=index.php'>";
			exit;
		}
		else if ($message===true){
			echo "<meta http-equiv='refresh' content='0; url=enter.php'>";
			exit;
		}
		else {
			echo "<meta http-equiv='refresh' content='0; url=enter.php?message={$message}'>";
			exit;
		}
	}
	private function determineAccess($type){
		$types=array("sa"=>2,"a"=>1,"v"=>0);
		if (array_key_exists($type,$types)===true)
			return $types[$type];
		else
			return 0;
	}
	private function determineFilePath($tagName,$dep,$fileName){
		$path="functional_departments/{$dep}/";
		if ($tagName=="exe")
			return $path."executors/{$fileName}";
		else if ($tagName=="page")
			return $path."ramification/{$fileName}";
		else  if ($tagName=="main")
			return $path."{$fileName}";
		else
			return false;
	}
	
	private function checkDomainName() {
		if(file_exists(self::DOMAIN_FILE)===false)
			return false;
		$current_domain=file_get_contents(self::DOMAIN_FILE);
		
		if($_SERVER['HTTP_HOST']!=trim($current_domain))
			return false;
		else
			return true;
	}
}
?>