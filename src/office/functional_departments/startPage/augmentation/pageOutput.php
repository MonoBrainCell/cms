<?php
class App_StartPage_pageOutput extends ErrorsManager{
	//public $pathPrefix="";
	private $patternSets=false;
	private $modulesSets=false;
	private $pluginsSets=false;
	private $pageId=false;
	private $htmlDoc;
	private $headerTags=false;

	private function decompSets($string){
		if (unserialize(stripslashes($string))===false){
			parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"Failed in Decompression Settings");
			parent::hideErrorByMessage();
		}
		else {
			return unserialize(stripslashes($string));
		}
	}

	public function returnPageId(){//Не нужен??? Может для старта и нужен???
		if (isset($_GET['pName'])===false)
			return 'start';
		else {
			$menuCont=file_get_contents("modules/navigation/pages.fbd.csv");
			$menuArr=self::decompSets($menuCont);
			foreach ($menuArr as $num=>$value){
				if ($value['alias']==$_GET['pName'])
					return $num;
			}
			include("404.html");
			exit;
		}
	}

	public function returnHtml(){
		if (isset($this->patternSets['htmlFile'])===true && is_string($this->patternSets['htmlFile'])===true){
			if (file_exists($this->patternSets['htmlFile'])===false){
				parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"Pattern file is not exists here: {$this->patternSets['htmlFile']}");
				parent::hideErrorByMessage();
			}
			else {
				$this->htmlDoc=file_get_contents($this->patternSets['htmlFile']);
				unset($this->patternSets['htmlFile']);
			}
		}
		else {
			parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"Information Cell of Pattern is broken");
			parent::hideErrorByMessage();
		}
		if (isset($this->patternSets['cssFiles'])===true && empty($this->patternSets['cssFiles'])===false){
			if (is_array($this->patternSets['cssFiles'])===true){
				for ($a=0,$b=count($this->patternSets['cssFiles']);$a<$b;$a++){
					if (file_exists($this->patternSets['cssFiles'][$a])!==false){
						$this->headerTags['link'][]="<link type='text/css' rel='stylesheet' href='{$this->patternSets['cssFiles'][$a]}' />";
					}
					else {
						parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"CSS file is not exists here: {$this->patternSets['cssFiles'][$a]}");
					}
				}
			}
			else {
				parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"Wrong format of Information Cell of Css Style");
			}
		}
		else {
			parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"Information Cell of Css Style not exists");
		}
		unset($this->patternSets['cssFiles']);
		$this->patternSets=self::treatHtmlAreas($this->patternSets,array("_head_string_","_content_part_"));
		self::genMainContent("_content_part_");
		//---
		self::genHeadContent($this->patternSets['_head_string_']);
		return $this->htmlDoc;
	}

	private function returnModContent($name){
		if (array_key_exists($name,$this->modulesSets)===false){
			parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"{$name} - Unregistered Involved Addition");
			return false;
		}
		else {
			if ($this->modulesSets[$name]['type']=="through" || ($this->modulesSets[$name]['type']=="local" && array_search($this->pageId,$this->modulesSets[$name]['forPages'],TRUE)!==false)){
				if (isset($this->modulesSets[$name]['parentClass'])===false){
					$this->modulesSets[$name]=self::collectHeaderTags($this->modulesSets[$name]);
					$tempObj=new $name();
					return $tempObj->getHtmlPiece();
				}
				else {
					$parent=$this->modulesSets[$name]['parentClass'];
					$this->modulesSets[$name]=self::collectHeaderTags($this->modulesSets[$name]);
					if (isset($this->baseObjects[$parent])===true)
						return $this->baseObjects[$parent]->{$name}();
					else {
						$this->baseObjects[$parent]=new $parent();
						return $this->baseObjects[$parent]->{$name}();
					}
				}
			}
			else
				return false;
		}
	}

	private function returnPlugContent($name){
		if (array_key_exists($name,$this->pluginsSets)===false){
			parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"{$name} - Unregistered Embedded Addition");
			return false;
		}
		else {
			if ($this->pluginsSets[$name]['contentInput']!='empty'){
				$varObj=new Wspc_getPluginContent($this->pluginsSets[$name]['contentInput']);
				$this->pluginsSets[$name]=self::collectHeaderTags($this->pluginsSets[$name]);
				return $varObj->returnVar;//--
			}
			else
				return false;
		}
	}

	private function rewriteInnerHeadersVar($elem){
		for ($a=0,$b=count($elem);$a<$b;$a++){
			$matcher=false;$matcher1=false;
			if (preg_match("/\<([a-zA-Z]*)/",$elem[$a],$matcher)==1){
				if (array_key_exists($matcher[1],$this->headerTags)===false){
					parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"{$matcher[1]} - Inadmissible tag for Header Information");
				}
				else {
					if (array_search($elem[$a],$this->headerTags[$matcher[1]],TRUE)===false){
						if (preg_match("/\{include\:([a-zA-Z\.\-\_\/]*)\}/",$elem[$a],$matcher1)==0){
							$this->headerTags[$matcher[1]][]=$elem[$a];
						}
						else {
							if (file_exists($matcher1[1])===false){
								parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"File with Header Information content not exists here:{$matcher1[1]}");
							}
							else {
								$this->headerTags[$matcher[1]][]=str_replace("{include:".$matcher1[1]."}",file_get_contents($matcher1[1]),$elem[$a]);
							}
						}
					}
				}
			}
			else {
				parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"{$elem[$a]} - Unknown Header Information Content");
			}
		}
		return $elem;
	}
	
	private function collectHeaderTags($elem){
		if ($elem['headerInform']=='none' && is_string($elem['headerInform'])===true){
			unset($elem['headerInform']);
		}
		else if (is_array($elem['headerInform'])===true){
			$elem['headerInform']=self::rewriteInnerHeadersVar($elem['headerInform']);
			unset($elem['headerInform']);
		}
		else {
			parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"{$elem['headerInform']} - Unknown Header Settings");
		}
		return $elem;
	}
	
	private function genHeadContent($headerArea){
		$temporaryArr=array();
		if (is_string($headerArea)===true && $headerArea=='empty'){
			foreach($this->headerTags as $value){
				$temporaryArr[]=implode("\r\n",$value);
			}
			$content=implode("\r\n",$temporaryArr);
			$this->htmlDoc=self::genHtmlString('_head_string_',$content,$this->htmlDoc);
		}
		else if (is_array($headerArea)===true){
			self::rewriteInnerHeadersVar($headerArea);
			foreach($this->headerTags as $value){
				$temporaryArr[]=implode("\r\n",$value);
			}
			$content=implode("\r\n",$temporaryArr);
			$this->htmlDoc=self::genHtmlString('_head_string_',$content,$this->htmlDoc);
		}
		else {
			parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"{$headerArea} - Unknown cell of Pattern Settings");
			$this->htmlDoc=self::genHtmlString('_head_string_',"",$this->htmlDoc);
		}
	}
	
	private function genMainContent($contentArea){
		$this->htmlDoc=self::genHtmlString($contentArea,"{BEFORE_H1}\r\n{H1_CONTENT}\r\n{AFTER_H1}\r\n{PAGE_CONTENT}\r\n{AFTER_CONTENT}\r\n",$this->htmlDoc);
		$this->patternSets[$contentArea]=self::treatHtmlAreas($this->patternSets[$contentArea],array("h1_content","page_content"));
		if (file_exists("content/{$this->pageId}-h_cont.fbd.dat")===false){
			parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"File with Operation Content Information is not exists here:content/{$this->pageId}-h_cont.fbd.dat");
			parent::hideErrorByMessage();
		}
		else {
			if (isset($this->patternSets[$contentArea]['h1_content']['patternChunk'])===true){
				$patternChunk=$this->patternSets[$contentArea]['h1_content']['patternChunk'];
				unset($this->patternSets[$contentArea]['h1_content']['patternChunk']);
			}
			else {
				$patternChunk="{REPLACEMENT_PART}";
			}
			$pageHeaderInf=unserialize(file_get_contents("content/{$this->pageId}-h_cont.fbd.dat"));
			$pageHeaderInf['h1']=str_replace("{REPLACEMENT_PART}",$pageHeaderInf['h1'],$patternChunk);
		}
		if (file_exists("content/{$this->pageId}-main.fbd.dat")===false){
			parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"File with Main Page Content not exists here:content/{$this->pageId}-main.fbd.txt");
			parent::hideErrorByMessage();
		}
		else {
			if (isset($this->patternSets[$contentArea]['page_content']['patternChunk'])===true){
				$patternChunk=$this->patternSets[$contentArea]['page_content']['patternChunk'];
				unset($this->patternSets[$contentArea]['page_content']['patternChunk']);
			}
			else {
				$patternChunk="{REPLACEMENT_PART}";
			}
			$pageMain=file_get_contents("content/{$this->pageId}-main.fbd.dat");
			$pageMain=str_replace("{REPLACEMENT_PART}",$pageMain,$patternChunk);
		}
		foreach ($this->modulesSets as $key=>$value){
			if ($value['type']=='eventer'){
				if (isset($value['parentClass'])===false){
					$tempObj=new $key($pageMain);
					$chkCont=$tempObj->getHtmlPiece();
					unset($tempObj);
					//$chkCont=new $key($pageMain);
				}
				else {
					$parent=$value['parentClass'];
					if (isset($this->baseObjects[$parent])===true)
						$chkCont=$this->baseObjects[$parent]->{$key}($pageMain);
					else {
						$this->baseObjects[$parent]=new $parent();
						$chkCont=$this->baseObjects[$parent]->{$key}($pageMain);
					}
				}
				if ($chkCont!==false){
					$pageMain=$chkCont;
					$this->modulesSets[$key]=self::collectHeaderTags($this->modulesSets[$key]);
				}
			}
		}
		$this->htmlDoc=self::genHtmlString("page_content",$pageMain,$this->htmlDoc);
		if (isset($pageHeaderInf['h1'])===false || (isset($pageHeaderInf['h1'])===true && is_string($pageHeaderInf['h1'])===false)){
			parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"Can't find h1 tag content here:content/{$this->pageId}-h_cont.fbd.dat");
			parent::hideErrorByMessage();
		}
		else {
			$this->htmlDoc=self::genHtmlString("h1_content",$pageHeaderInf['h1'],$this->htmlDoc);
		}
		if (isset($pageHeaderInf['headers'])===false || (isset($pageHeaderInf['headers'])===true && is_array($pageHeaderInf['headers'])===false)){
			parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"Tags for page headers undefined:content/{$this->pageId}-h_cont.fbd.dat");
		}
		else {
			$keys=array();
			foreach($this->headerTags as $key=>$value){
				if (isset($pageHeaderInf['headers'][$key])===true && is_array(($pageHeaderInf['headers'][$key]))===true){
					// $value=array_merge($value,$pageHeaderInf['headers'][$key]);
					$this->headerTags[$key]=array_merge($value,$pageHeaderInf['headers'][$key]);
				}
			}
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
				if (is_string($areaSets)===true && $areaSets=='empty')
					$this->htmlDoc=self::genHtmlString($areaName,"",$this->htmlDoc);
				else if (is_array($areaSets)===true){
					$content="";
					if (isset($areaSets['patternChunk'])===true){//---
						$patternChunk=$areaSets['patternChunk'];
						unset($areasList[$areaName]['patternChunk']);
					}
					else
						$patternChunk="{REPLACEMENT_PART}";
					for ($a=0,$b=count($areaSets);$a<$b;$a++){
						if ($areaSets[$a]['type']=='involvedAddition'){
							$prepareCont=self::returnModContent($areaSets[$a]['name']);
							$prepareCont=str_replace("{REPLACEMENT_PART}",$prepareCont,$patternChunk);
						}
						else if ($areaSets[$a]['type']=='embeddedAddition'){
							$prepareCont=self::returnPlugContent($areaSets[$a]['name']);
							$prepareCont=str_replace("{REPLACEMENT_PART}",$prepareCont,$patternChunk);
						}
						else {
							parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"{$areaSets[$a]['name']} - Undetectable type of Advanced Content");
							$prepareCont="";
						}
						$content.=$prepareCont;
					}
					$this->htmlDoc=self::genHtmlString($areaName,$content,$this->htmlDoc);
				}
				else {
					parent::addErrorLog(__FILE__,__CLASS__,__LINE__,"{$areaSets} - Undetectable cell of Pattern Settings");
					$this->htmlDoc=self::genHtmlString($areaName,"",$this->htmlDoc);
				}
			}	
		}
		
		return $areasList;
	}
	
	private function genHtmlString($strPat,$content,$pattern){
		$strPat="{".strtoupper($strPat)."}";
		return str_replace($strPat,$content,$pattern);
	}
	
	function __construct($settings){//Изменить для работы в условиях boggarta
		$allSets=self::decompSets($settings);
		if ($allSets['siteEnabled']!="on"){
			if (file_exists("specials/site_off.html")===true)
			echo file_get_contents("specials/site_off.html");
		else
			echo "<h1>Наш веб-сайт закрыт на реконструкцию. Мы обязательно вернёмся в ближайшее время.</h1>";
		exit;
		}
		$this->patternSets = $allSets['pattern'];
		$this->modulesSets = $allSets['involvedAdditions'];
		$this->pluginsSets = $allSets['embeddedAdditions'];
		$this->pageId = self::returnPageId();
		$this->headerTags=array(
		'meta'=>array(
		"<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>"),
		'base'=>array(),
		'title'=>array(),
		'link'=>array(),
		'script'=>array(),
		'style'=>array()
		);
	}
}
?>