<?php
class Wspc__MasterEncryption extends ErrorsManager{
	private $numericCharacter=array(0,1,2,3,4,5,6,7,8,9);
	private $firstSixLetters=array("a","b","c","d","e","f");
	private $lastLetters=array("g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	private $specialChars=array("@","#","-","_","+","!");
	private $cryptKey=array();
	private $cypherType="aes-256-cfb8";
	private $iv_num=16;
	private $vector;
	const KEY_OF_CRYPTING="gatekeeper/abcqwerty.fbd.dat";
	public function __construct(){
		if (file_exists(self::KEY_OF_CRYPTING)===false){
			parent::hideErrorByMessage();
		}
		else {
			$content=trim(file_get_contents(self::KEY_OF_CRYPTING));
			if (strlen($content)<48){
				parent::hideErrorByMessage();
			}
			else {
				$keysArr=str_split($content,32);
				if (strlen($keysArr[0])!=32 || strlen($keysArr[1])!=16){
					parent::hideErrorByMessage();
				}
				else {
					$this->cryptKey=$keysArr;
				}
				$tmpVar=str_split($content,$this->iv_num);
				$this->vector=$tmpVar[0];
			}
		}
	}
	
	private function returnCasesArray($arrayOfLeters,$case){
		$newArrayOfLetters=array();
		switch ($case){
			case "only capital":
				for ($a=0,$b=count($arrayOfLeters);$a<$b;$a++){
					$newArrayOfLetters[]=strtoupper($arrayOfLeters[$a]);
				}
				return $newArrayOfLetters;
			break;
			case "all":
				for ($a=0,$b=count($arrayOfLeters);$a<$b;$a++){
					$newArrayOfLetters[]=$arrayOfLeters[$a];
					$newArrayOfLetters[]=strtoupper($arrayOfLeters[$a]);
				}
				return $newArrayOfLetters;
			break;
			default: return array();
		}
	}
	
	private function returnArrayOfCharacters($characters){
		switch ($characters){
			case "numeric":
				return $this->numericCharacter;
			break;
			case "16s_l":
				return array_merge($this->numericCharacter,$this->firstSixLetters);
			break;
			case "16s_u":
				return array_merge($this->numericCharacter,self::returnCasesArray($this->firstSixLetters,"only capital"));
			break;
			case "16s":
				return array_merge($this->numericCharacter,self::returnCasesArray($this->firstSixLetters,"all"));
			break;
			case "num+let_l":
				return array_merge($this->numericCharacter,$this->firstSixLetters,$this->lastLetters);
			break;
			case "num+let_u":
				return array_merge($this->numericCharacter,self::returnCasesArray($this->firstSixLetters,"only capital"),self::returnCasesArray($this->lastLetters,"capital"));
			break;
			case "num+let":
				return array_merge($this->numericCharacter,self::returnCasesArray($this->firstSixLetters,"all"),self::returnCasesArray($this->lastLetters,"all"));
			break;
			case "num+let+specials_l":
				return array_merge($this->numericCharacter,$this->firstSixLetters,$this->lastLetters,$this->specialChars);
			break;
			case "num+let+specials_u":
				return array_merge($this->numericCharacter,self::returnCasesArray($this->firstSixLetters,"only capital"),self::returnCasesArray($this->lastLetters,"capital"),$this->specialChars);
			break;
			case "num+let+specials":
				return array_merge($this->numericCharacter,self::returnCasesArray($this->firstSixLetters,"all"),self::returnCasesArray($this->lastLetters,"all"),$this->specialChars);
			break;
			default: return array();
		}
	}
	
	private function cryptDatas($key,$text) {
		return base64_encode(openssl_encrypt($text,$this->cypherType,$key,0,$this->vector));
	}
	private function decryptDatas($key,$text) {
		return openssl_decrypt(base64_decode($text),$this->cypherType,$key,0,$this->vector);
	}	
	
	public function genRandomString($amount,$characters){
		$string="";
		$charactersArray=self::returnArrayOfCharacters($characters);
		for ($a=0;$a<$amount;$a++){
			$randKey=array_rand($charactersArray);
			$string.=$charactersArray[$randKey];
		}
		return $string;
	}
	
	public function genHashOfString($string,$typeOfHash){
		if ($typeOfHash=="1"){
			return md5($string);
		}
		else if ($typeOfHash=="2"){
			return sha1($string);
		}
		else {
			return false;
		}
	}
	
	public function embedSymbsToString($string,$typeOfSymbs,$freq){
		$stringArray=str_split($string);
		$lenghtOfString=count($stringArray);
		$amount=floor($lenghtOfString/(int)$freq);
		$charactersArray=self::genRandomString($amount,$typeOfSymbs);
		$newString="";
		for ($a=0,$b=0;$a<$lenghtOfString;$a++){
			$newString.=$stringArray[$a];
			if (($a+1)%$freq==0 && $a!=0){
				$newString.=$charactersArray[$b];
				$b++;
			}
		}
		return $newString;
	}
	public function extractSymbsFromString($string,$freq) {
		$extractString="";
		$stringArray=str_split($string);
		$extractFreq=$freq+1;
		for ($a=0,$b=count($stringArray);$a<$b;$a++){
			if (($a+1)%$extractFreq==0 && $a!=0){
				continue;
			}
			else {
				$extractString.=$stringArray[$a];
			}
		}
		return $extractString;
	}
	
	public function cryptDecryptDatas($text,$type,$initVar) {
		if ($initVar=="1"){
			if ($type=="Session Datas"){
				return self::cryptDatas($this->cryptKey[1],$text);
			}
			else if ($type=="Users File Datas"){
				return self::cryptDatas($this->cryptKey[0],$text);
			}
			else {
				return false;
			}
		}
		else if ($initVar=="2"){
			if ($type=="Session Datas"){
				return self::decryptDatas($this->cryptKey[1],$text);
			}
			else if ($type=="Users File Datas"){
				return self::decryptDatas($this->cryptKey[0],$text);
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}
}
?>