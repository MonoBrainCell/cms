<?php
// Программное Обеспечение для управления сайтом 'Adept Illusion'
// Данное ПО является открытым для его изменения и использования
class Pre__navigation extends ErrorsManager{
	const NAVIGATION_FILE="navigation.fbd.xml";
	const MAIN_NAVIGATION_TPL_FILE="functional/navigation/dummyes/mainNavigationTpls.fbd.tpl";
	const ADV_NAVIGATION_TPL_FILE="functional/navigation/dummyes/advancedNavigationTpls.fbd.tpl";
	const RELELEM_NAVIGATION_TPL_FILE="functional/navigation/dummyes/relElemNavigationTpls.fbd.tpl";
	const LEVEL1_SEPARATOR="|--|";
	const LEVEL2_SEPARATOR="|-|";
	const ENCLAVE_DEFAULT_POSTFIX="?gId=";
	const ENCLAVE_DEFAULT_NUMBER=1;
	const SITE_PAGES_ADDR_PREFIX="page/";
	
	private $advancedRestring=array("/\{_ADVANCED_NAVIGATION_\}/u","{_ADVANCED_NAVIGATION_}");
	private $pathRestring=array("/\{_PATH_CONTENT_\}/u","{_PATH_CONTENT_}");
	private $relativesRestring=array("/\{_RELATIVES_INSERT_\}/u","{_RELATIVES_INSERT_}");
	
	private $pageId;
	private $navigationTypes=array("main","advanced");
	private $mainNavigation; private $advancedNavigation;
	private $pageAccessory="main";
	private $relationElems="";
	private $levelsQ=3;
	
	public function __construct($id){
		if (file_exists(self::NAVIGATION_FILE)===false){
			$this->mainNavigation=self::genPseudoXMLLength();
			$this->advancedNavigation=self::genPseudoXMLLength();
			$this->id=false;
			return false;
		}
		$file=new DOMDocument();
		$file->formatOutput=true;
		$file->preserveWhiteSpace=false;
		$file->load(self::NAVIGATION_FILE);
		$xPathDoc=new DOMXPath($file);
		$checkAncestor=$xPathDoc->query("/navigation//page[@alias='{$id}']/ancestor::main");
		if ($id!=="start"){
			if ($checkAncestor->length!==0){
				$this->pageId=$id;
				$this->pageAccessory=$checkAncestor->item(0)->tagName;
			}
			else
				$this->pageId=false;
		}
		else 
			$this->pageId=false;
		unset($checkAncestor);			
		for ($a=0,$b=count($this->navigationTypes);$a<$b;$a++){
			$spVarName=$this->navigationTypes[$a]."Navigation";
			$this->{$spVarName}=$xPathDoc->query("/navigation/".$this->navigationTypes[$a]."/page");
		}
	}
	public function getMainNavigation($html){
		if (file_exists(self::MAIN_NAVIGATION_TPL_FILE)===false){
			$html['html']=str_replace("{_MAIN_NAVIGATION_}","",$html['html']);
			return $html;
		}
		$tmpCont=file_get_contents(self::MAIN_NAVIGATION_TPL_FILE);
		$levels=array();
		list($wrapperBlock,$levels[1],$levels[2],$levels[3])=explode(self::LEVEL1_SEPARATOR,$tmpCont);
		unset($tmpCont);
		for($a=1,$b=count($levels);$a<=$b;$a++){
			$var=explode(self::LEVEL2_SEPARATOR,$levels[$a]);
			unset($levels[$a]);
			$levels[$a]=$var;
		}
		unset($var);
		$string="";
		if ($this->mainNavigation->length!==0){
			$string="";
			for ($a=0;$a<$this->mainNavigation->length;$a++){
				$string.=self::genNavigationElems($this->mainNavigation->item($a),$levels,1,$this->levelsQ);
			}
		}
		if (empty($string)===false)
			$string=str_replace("{CONTAINER_CONTENT}",$string,$wrapperBlock);
		$html['html']=str_replace("{_MAIN_NAVIGATION_}",$string,$html['html']);
		return $html;
	}
	
	public function getAdvNavigation($html){
		if (self::checkStringInCont($this->advancedRestring[0],$html['html'])===false)
			return $html;
		if (file_exists(self::ADV_NAVIGATION_TPL_FILE)===false){
			$html['html']=str_replace($this->advancedRestring[1],"",$html['html']);
			return $html;
		}
		$tmpCont=file_get_contents(self::ADV_NAVIGATION_TPL_FILE);
		list($block,$anchor)=explode(self::LEVEL1_SEPARATOR,$tmpCont);
		$string="";
		if ($this->advancedNavigation->length!==0){
			for ($a=0;$a<$this->advancedNavigation->length;$a++){
				$tpl=array(1=>array("",$anchor));
				$string.=self::genNavigationElems($this->advancedNavigation->item($a),$tpl,1);
			}
		}
		if (empty($string)===false)
			$string=str_replace("{CONTAINER_CONTENT}",$string,$block);
		$html['html']=str_replace($this->advancedRestring[1],$string,$html['html']);
		
		return $html;
	}
	
	public function getPathFromMain($html){
		if (self::checkStringInCont($this->pathRestring[0],$html['html'])===false)
			return $html;
		if (empty($this->relationElems)===true && file_exists(self::RELELEM_NAVIGATION_TPL_FILE)===false){
			$html['html']=str_replace($this->pathRestring[1],"",$html['html']);
			return $html;
		}
		else if (empty($this->relationElems)===true && file_exists(self::RELELEM_NAVIGATION_TPL_FILE)===true){
			$this->relationElems=file_get_contents(self::RELELEM_NAVIGATION_TPL_FILE);
		}
		list($block,$anchor,,)=explode(self::LEVEL1_SEPARATOR,$this->relationElems);
		$string=str_replace("{ANCHOR_HREF}","/",$anchor);
		$string=str_replace("{ANCHOR_NAME}","Главная",$string);
		if ($this->pageId!==false){
			$dir=$this->pageAccessory ."Navigation";
			$XMLfragment=$this->{$dir};
			$doc=$XMLfragment->item(0)->ownerDocument;
			$xPath=new DOMXPath($doc);
			$findElem=$xPath->query("/navigation//page[@alias='".$this->pageId ."']");
			$tpl=array(1=>array("",$anchor));
			if ($this->pageAccessory==="advanced")
				$string.=self::genNavigationElems($findElem->item(0),$tpl,1);
			else {
				$findParent=$xPath->query("/navigation//page[@alias='".$this->pageId ."']/ancestor::*");
				$arr=array();
				for($a=0;$a<$findParent->length;$a++){
					if ($findParent->item($a)->tagName==="main" || $findParent->item($a)->tagName==="navigation")
						continue;
					$string.=self::genNavigationElems($findParent->item($a),$tpl,1);
				}
			}
		}
		$string=str_replace("{CONTAINER_CONTENT}",$string,$block);
		$html['html']=str_replace($this->pathRestring[1],$string,$html['html']);
		
		return $html;
	}
	
	public function getRelatives($html){
		if (self::checkStringInCont($this->relativesRestring[0],$html['html'])===false)
			return $html;
		if (empty($this->relationElems)===true && file_exists(self::RELELEM_NAVIGATION_TPL_FILE)===false){
			$html['html']=str_replace($this->relativesRestring[1],"",$html['html']);
			return $html;
		}
		else if (empty($this->relationElems)===true && file_exists(self::RELELEM_NAVIGATION_TPL_FILE)===true){
			$this->relationElems=file_get_contents(self::RELELEM_NAVIGATION_TPL_FILE);
		}
		list(,,$block,$anchor)=explode(self::LEVEL1_SEPARATOR,$this->relationElems);
		$string="";
		if ($this->pageAccessory==="advanced" || $this->pageId===false){}
		else {
			$dir=$this->pageAccessory ."Navigation";
			$XMLfragment=$this->{$dir};
			$doc=$XMLfragment->item(0)->ownerDocument;
			$xPath=new DOMXPath($doc);
			$findChild=$xPath->query("/navigation//page[@alias='".$this->pageId ."']/page");
			if ($findChild->length===0){}
			else {
				$tpl=array(1=>array("",$anchor));
				for ($a=0;$a<$findChild->length;$a++){	
					$string.=self::genNavigationElems($findChild->item($a),$tpl,1);
				}
				
			}
		}
		if (empty($string)===false)
			$string=str_replace("{CONTAINER_CONTENT}",$string,$block);
		$html['html']=str_replace($this->relativesRestring[1],$string,$html['html']);
		
		return $html;
	}
	
	private function genNavigationElems($XMLPiece,$tpl,$depth,$depthLimit=1){
		$str="";
		if ($depthLimit<$depth)
			return $str;
		else if ($depthLimit===$depth
		||
		($depthLimit>$depth && $XMLPiece->hasChildNodes()===false)
		){
			if ($XMLPiece->attributes->getNamedItem("enclave")->nodeValue==="1")
				$anchorHref=$XMLPiece->attributes->getNamedItem("alias")->nodeValue .self::ENCLAVE_DEFAULT_POSTFIX .self::ENCLAVE_DEFAULT_NUMBER;
			else
				$anchorHref=self::SITE_PAGES_ADDR_PREFIX.$XMLPiece->attributes->getNamedItem("alias")->nodeValue;
			$str.=str_replace("{ANCHOR_HREF}",$anchorHref,$tpl[$depth][1]);
			$str=str_replace("{ANCHOR_NAME}",$XMLPiece->attributes->getNamedItem("name")->nodeValue,$str);
		}
		else {
			$str.=str_replace("{ANCHOR_NAME}",$XMLPiece->attributes->getNamedItem("name")->nodeValue,$tpl[$depth][0]);
			$str1="";
			for ($a=0;$a<$XMLPiece->childNodes->length;$a++){
				$str1.=self::genNavigationElems($XMLPiece->childNodes->item($a),$tpl,$depth+1,$depthLimit);
			}
			$str=str_replace("{CONTAINER_CONTENT}",$str1,$str);
		}
		return $str;
	}
	
	private function checkStringInCont($pregString,$content){
		if (preg_match($pregString,$content)===0 || preg_match($pregString,$content)===false)
			return false;
		else 
			return true;
	}
}
?>