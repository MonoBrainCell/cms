<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class Spl__pageOutput extends ErrorsManager{
	private $pattern=false;
	private $settings=false;
	private $htmlDoc;
	private $mods=array();
	private $headerTags=false;

	public function returnHtml(){
		if ($this->pattern!==false)
			$this->htmlDoc=$this->pattern;
		else
			parent::hideErrorByMessage();
		self::treatHtmlAreas($this->settings);
		//---
		$this->htmlDoc=self::genHtmlString("SCRIPTS_WORKSPACE",$this->headerTags,$this->htmlDoc);
		return $this->htmlDoc;
	}

	private function returnModContent($elem){
		if ($elem['parent']=="none"){
			self::collectHeaderTags($elem);
			$tempObj=new $elem['name']();
			return $tempObj->getHtmlPiece();
		}
		else {
			$parent=$elem['parent'];
			self::collectHeaderTags($elem);
			if (isset($this->mods[$parent])===true)
				return $this->mods[$parent]->{$elem['name']}();
			else {
				$this->mods[$parent]=new $parent();
				return $this->mods[$parent]->{$elem['name']}();
			}
		}
	}

	private function returnEmbeddedContent($elem){
		if ($elem['content']!="none"){
			$matcher1=false;
			if (preg_match("/\{include\:([a-zA-Z\.\-\_\/]*)\}/",$elem['content'],$matcher1)==0)
				$content=$elem['content'];
			else {
				if (file_exists($matcher1[1])===false){
					$content="";
				}
				else {
					$content=str_replace("{include:".$matcher1[1]."}",file_get_contents($matcher1[1]),$elem['content']);
				}
			}
		}
		else {
			$content="";
		}
		self::collectHeaderTags($elem);
		return $content;
	}

	private function rewriteInnerHeadersVar($elem){
		$matcher1=false;
		if (preg_match("/\{include\:([a-zA-Z\.\-\_\/]*)\}/",$elem,$matcher1)==0)
			$this->headerTags .=$elem;
		else {
			if (file_exists($matcher1[1])===false){
				$this->headerTags .="";
			}
			else {
				$this->headerTags .=str_replace("{include:".$matcher1[1]."}",file_get_contents($matcher1[1]),$elem);
			}
		}
	}
	
	private function collectHeaderTags($elem){
		if ($elem['headerTags']!='none'){
			self::rewriteInnerHeadersVar($elem['headerTags']);
			unset($elem['headerTags']);
		}
	}
	
	private function treatHtmlAreas($areasList,$areasExceptions=array()){
		foreach ($areasList as $areaName=>$areaSets){
			$exept="no";
			for ($z=0,$y=count($areasExceptions);$z<$y;$z++){
				if ($areaName==$areasExceptions[$z]){
					$exept="yes";
					break;
				}
			}
			if ($exept!="yes"){
				if (is_array($areaSets)===true){
					$content="";
					if (isset($areaSets['patternChunk'])===true){//---
						$patternChunk=$areaSets['patternChunk'];
						unset($areaSets['patternChunk']);
					}
					else
						$patternChunk="{REPLACEMENT_PART}";
					if ($areaSets['type']=='involvement'){
						$content=self::returnModContent($areasList[$areaName]);
						$content=str_replace("{REPLACEMENT_PART}",$content,$patternChunk);
					}
					else if ($areaSets['type']=='embed'){
						$content=self::returnEmbeddedContent($areasList[$areaName]);
						$content=str_replace("{REPLACEMENT_PART}",$content,$patternChunk);
					}
					else {
						$content="";
					}
					$this->htmlDoc=self::genHtmlString($areaName,$content,$this->htmlDoc);
				}
				else {
					$this->htmlDoc=self::genHtmlString($areaName,"",$this->htmlDoc);
				}
			}	
		}
	}
	
	private function genHtmlString($strPat,$content,$pattern){
		$strPat="{%".strtoupper($strPat)."%}";
		return str_replace($strPat,$content,$pattern);
	}
	
	function __construct($settings){
		if (is_array($settings)===true){
			if (file_exists("adminTemplate/main_dummy.html")===true)
				$this->pattern = file_get_contents("adminTemplate/main_dummy.html");
			$this->settings=$settings;
		}
	}
}
?>