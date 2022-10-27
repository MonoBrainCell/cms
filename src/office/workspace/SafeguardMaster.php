<?php
class Wspc__SafeguardMaster extends ErrorsManager{
	const FILE_OF_USERS_KEYS="gatekeeper/users.fbd.xml";
	
	private $clientDatas;
	private $intrLogin;
	private $intrPassw;
	private $maEnc;
	private $curDate;
	
	function __construct($mEncrypt){
		$this->maEnc=$mEncrypt;
		if (file_exists(self::FILE_OF_USERS_KEYS)===false)
			parent::hideErrorByMessage();
		$datas=new DOMDocument();
		if ($datas->load(self::FILE_OF_USERS_KEYS)===false)
			parent::hideErrorByMessage();
		$this->clientDatas=new DOMXPath($datas);
	}
	public function receiveClientDatas(){
		if (isset($this->clientDatas)===true){
			return $this->clientDatas;
		}
		else {
			return false;
		}
	}
	
	public function cursoryInspection($login,$passw){
		if (isset($login)===false || strlen(trim($login))<5 || strlen(trim($login))>15){
			return false;
		}
		if (preg_match("/[а-яА-Я\`\~\$\%\^\&\*\{\}\[\]\(\)\=\+\,\.\?\'\"\:\;\№\<\>\/]/",$login)==1){
			return false;
		}
		if (isset($passw)===false || strlen(trim($passw))<6 || strlen(trim($passw))>25){
			return false;
		}
		if (preg_match("/[а-яА-Я\`\~\$\%\^\&\*\{\}\[\]\(\)\=\+\,\.\?\'\"\:\;\№\<\>\/]/",$passw)==1){
			return false;
		}	
		$this->intrLogin=trim(strip_tags($login));
		$this->intrPassw=trim(strip_tags($passw));
		return true;
	}
	
	public function scrutiny(){
		$paswXML=$this->clientDatas->query("/data/users/user[@login='".$this->intrLogin ."']/@password");
		if ($paswXML->length===0)
			return false;
		$existingPasw=$this->maEnc->extractSymbsFromString($paswXML->item(0)->nodeValue,7);
		$this->intrPassw=sha1($this->intrPassw);
		if ($existingPasw!=$this->intrPassw)
			return false;
		self::affordSafe_conduct();
		return true;
	}
	
	private function affordSafe_conduct(){
		$tempArr=array('name'=>$this->intrLogin);
		$client=$_SERVER['HTTP_USER_AGENT'];
		$client=$this->maEnc->genHashOfString($client,"2");
		$client=$this->maEnc->embedSymbsToString($client,"num+let+specials",5);
		$tempArr['client']=$client;
		$tempStr=serialize($tempArr); 
		$tempStr=$this->maEnc->cryptDecryptDatas($tempStr,'Session Datas',"1"); 
		$_SESSION['master']=$tempStr;
	}
	
	public function refreshFileDatas($type,$params=array()){
		if ($type=="new password"){
			if (self::cursoryInspection($this->clientDatas['login'],$params[0])===false)
				return "wrong symbs";
			if ($params[0]!=$params[1])
				return "pasw confirm failed";
			$this->clientDatas['pasw']=$this->maEnc->genHashOfString($params[0],"2");
			$this->clientDatas['pasw']=$this->maEnc->embedSymbsToString($this->clientDatas['pasw'],"16s_u",7);
		}
		else if ($type=="new personal datas"){
			if (self::cursoryInspection($params[0],$this->clientDatas['pasw'])===false)
				return "wrong symbs";
			$this->clientDatas['login']=$params[0];
		}
		else 
			return false;

		$newContent="<?php\r\n\$login='".$this->clientDatas['login']."';\r\n".
		"\$password='".$this->clientDatas['pasw']."';\r\n?>";
		if (@file_put_contents(self::FILE_OF_USERS_KEYS,$newContent)===false){
			parent::hideErrorByMessage();
		}
		return "success";
	}
}
?>