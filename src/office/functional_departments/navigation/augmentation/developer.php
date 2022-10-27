<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_navigation_developer extends ErrorsManager{
	const NAVIGATION_FILE="../navigation.fbd.xml";
	const NAV_ELEM_TPL_PATH="functional_departments/navigation/dummyes/navigationRedact_elem.fbd.tpl";
	const NAV_CONTEINER_TPL_PATH="functional_departments/navigation/dummyes/navigationRedact_conteiner.fbd.tpl";
	const SITE_PAGE_PREFIX="page/";
	
	private $htmlContent;
	
	public function __construct(){
		$GETInf=new manipulateGETInf();
		$nType=$GETInf->searchGETElem("nType");
		if ($nType===false) {
			$this->htmlContent="<div id='main_content'><h1>Не был передан обязательный идентифицирующий параметр. Скорее всего Вы попытались перейти к странице не через навигацию по административной части сайта.</h1></div>";
			return false;
		}
		if (file_exists(self::NAVIGATION_FILE)===false){
			$this->htmlContent="<div id='main_content'><h1>Файл navigation.fbd.csv, расположенный в корневой папке сайта повреждён или отстутствует.</h1></div>";
			return false;
		}
		if (file_exists(self::NAV_ELEM_TPL_PATH)===false || file_exists(self::NAV_CONTEINER_TPL_PATH)===false) {
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		$file=new DOMDocument();
		$file->formatOutput=true;
		$file->preserveWhiteSpace=false;
		$file->load(self::NAVIGATION_FILE);
		$xPathDoc=new DOMXPath($file);
		$checkTag=$xPathDoc->query("/navigation/{$nType}");
		if ($checkTag->length===0){
			$this->htmlContent="<div id='main_content'><h1>Произошёл сбой при интерпретации информации об имеющихся страницах. </h1><p>Скорее всего данная проблема была вызвана ошибкой записи. <br> Обратитесь к разработчику за помощью или к Хостинг-провайдеру для BackUp'a сайта.</p></div>";
			return false;
		}
		$mainTpl=file_get_contents(self::NAV_CONTEINER_TPL_PATH);
		$content=str_replace("{NAVIGATION_ID}",$nType,$mainTpl);
		$innerContent=self::genNavigationWrapper($file,$nType);
		$this->htmlContent=str_replace("{VARIABLE_PART}",$innerContent,$content);
	}
	private function genNavigationWrapper($file,$type){
		$xPathDoc=new DOMXPath($file);
		$checkChild=$xPathDoc->query("/navigation/{$type}/*");
		if ($checkChild->length===0)
			return "";
		if ($type=="advanced")
			return self::genNavigationElems($checkChild);
		else if ($type=="main")
			return self::genNavigationElems($checkChild,false);
	}
	
	private function genNavigationElems($XMLPiece,$depthLimit=true,$tpl=false){
		if ($tpl===false)
			$tpl=file_get_contents(self::NAV_ELEM_TPL_PATH);
		$str="";
		if ($depthLimit===true){$linkMarker=" no_link";}
		else {$linkMarker="";}
		for($a=0;$a<$XMLPiece->length;$a++){
			$str.=str_replace("{NO_LINK_MARKER}",$linkMarker,$tpl);
			if ($XMLPiece->item($a)->attributes->getNamedItem("enclave")->nodeValue!=="0"){
				$rewriteMarker=" no_rewrite";
				$aliasPrefix="";
				$pageId=str_replace(".php","_php",$XMLPiece->item($a)->attributes->getNamedItem("alias")->nodeValue);
			}
			else {
				$rewriteMarker="";
				$aliasPrefix=self::SITE_PAGE_PREFIX;
				$pageId=$XMLPiece->item($a)->attributes->getNamedItem("alias")->nodeValue;
			}
			$str=str_replace("{PAGE_ID}",$pageId,$str);
			$str=str_replace("{PAGE_ALIAS}",$XMLPiece->item($a)->attributes->getNamedItem("alias")->nodeValue,$str);
			$str=str_replace("{PAGE_ADDRESS}",$aliasPrefix.$XMLPiece->item($a)->attributes->getNamedItem("alias")->nodeValue,$str);
			$str=str_replace("{PAGE_NAME}",$XMLPiece->item($a)->attributes->getNamedItem("name")->nodeValue,$str);
			$str=str_replace("{NOT_REWRITE_MARK}",$rewriteMarker,$str);
			if ($depthLimit===true)
				$str=str_replace("{VARIABLE_PART}","",$str);
			else {
				if ($XMLPiece->item($a)->hasChildNodes()===false)
					$str=str_replace("{VARIABLE_PART}","",$str);
				else
					$str=str_replace("{VARIABLE_PART}",self::genNavigationElems($XMLPiece->item($a)->childNodes,$depthLimit,$tpl),$str);
			}
		}
		return $str;
	}
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>