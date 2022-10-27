<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_ad_developer {
	const NAVIGATION_FILE="../navigation.fbd.xml";
	const BANNERS_FILE="../functional/ad/bannersList.fbd.csv";
	const BANNER_DEV_CONTEINER="functional_departments/ad/dummyes/bannersRedact_conteiner.fbd.tpl";
	const BANNER_DEV_CODE="functional_departments/ad/dummyes/bannersRedact_code.fbd.tpl";
	const BANNER_DEV_CONSTR="functional_departments/ad/dummyes/bannersRedact_constructor.fbd.tpl";
	const BANNER_CODE_FILE_PREFIX="../functional/ad/content/{ID}.fbd.tpl";
	private $htmlContent;
	public function __construct(){
		$GETInf=new manipulateGETInf();
		$bId=$GETInf->searchGETElem("banId");
		if ($bId===false){
			$this->htmlContent="<div id='main_content'><h1>Не был передан основной параметр для редактирования баннера. Для доступа к функционалу пользуйтесь навигацией в административной части.</h1></div>";
			return false;
		}
		if (file_exists(self::BANNERS_FILE)===true)
			$banners=file(self::BANNERS_FILE);
		else if(file_exists(self::BANNERS_FILE)===false && $bId=="new"){
			$banners=array();
		}
		else{
			$this->htmlContent="<div id='main_content'><h1>Файл со списком баннеров отсутствует</h1></div>";
			return false;
		}
		if ((empty($banners)===true || strlen(trim($banners[0]))<1) && $bId!="new"){
			$this->htmlContent="<div id='main_content'><h1>Файл со списком баннеров повреждён</h1></div>";
			return false;
		}
		if (file_exists(self::BANNER_DEV_CONTEINER)===false || file_exists(self::BANNER_DEV_CODE)===false || file_exists(self::BANNER_DEV_CONSTR)===false){
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		if (file_exists(self::NAVIGATION_FILE)===false){
			$this->htmlContent="<div id='main_content'><h1>Файл со списком страниц сайта отсутствует </h1></div>";
			return false;
		}
		if ($bId!="new"){
			for($a=0,$b=count($banners);$a<$b;$a++){
				$innerArr=explode(";;",trim($banners[$a]));
				if ($innerArr[0]==$bId){
					$params=$innerArr;
					break;
				}
			}
			if (isset($params)===false){
				$this->htmlContent="<div id='main_content'><h1>Такого баннера не существует</h1></div>";
				return false;
			}
		}
		else 
			$params=array("new","","codeIns","through");
		self::genContent($params);
		
	}

	private function genContent($settings){
		$mainTemp=file_get_contents(self::BANNER_DEV_CONTEINER);
		$this->htmlContent=str_replace("{BAN_ID}",$settings[0],$mainTemp);
		$this->htmlContent=str_replace("{BAN_NAME}",$settings[1],$this->htmlContent);
		if ($settings[2]=="codeIns"){
			$this->htmlContent=str_replace("{CODE_CHECKED}"," checked='checked'",$this->htmlContent);
			$this->htmlContent=str_replace("{CONSTR_CHECKED}","",$this->htmlContent);
			$this->htmlContent=str_replace("{VARIABLE_PART}",self::genVariableContent(array($settings[0],"")),$this->htmlContent);
		}
		else {
			$this->htmlContent=str_replace("{CODE_CHECKED}","",$this->htmlContent);
			$this->htmlContent=str_replace("{CONSTR_CHECKED}"," checked='checked'",$this->htmlContent);
			$this->htmlContent=str_replace("{VARIABLE_PART}",self::genVariableContent(array($settings[0],$settings[2])),$this->htmlContent);
		}
		if ($settings[3]=="through")
			$this->htmlContent=self::genPagesList($this->htmlContent);
		else
			$this->htmlContent=self::genPagesList($this->htmlContent,$settings[3]);
	}
	
	private function genVariableContent($settings){
		$entityConvert=new maskHTMLEntity(true);
		if ($settings[1]==="")
			$variableContent=file_get_contents(self::BANNER_DEV_CODE);
		else 
			$variableContent=file_get_contents(self::BANNER_DEV_CONSTR);
		if ($settings[0]=="new")
			return str_replace("{BAN_CODE}","",$variableContent);
		else if ($settings[0]!="new" && $settings[1]===""){
			$fileWithCodePath=str_replace("{ID}",$settings[0],self::BANNER_CODE_FILE_PREFIX);
			if (file_exists($fileWithCodePath)===false)
				return str_replace("{BAN_CODE}","",$variableContent);
			else
				return str_replace("{BAN_CODE}",$entityConvert->maskEngine(file_get_contents($fileWithCodePath)),$variableContent);
		}
		$constructorSets=explode(",,",$settings[1]);
		$string=str_replace("{IMG_ADDR}",$constructorSets[2],$variableContent);
		$string=str_replace("{TITLE_HINT}",$constructorSets[0],$string);
		$string=str_replace("{HINT}",$constructorSets[1],$string);
		$string=str_replace("{TARGET_PAGE}",$constructorSets[3],$string);
		return $string;
	}
	
	private function genPagesList($template,$markedPage=""){
		$navigation=file(self::NAVIGATION_FILE);
		$navigation=new DOMDocument();
		$navigation->formatOutput=true;
		$navigation->preserveWhiteSpace=false;
		$navigation->load(self::NAVIGATION_FILE);
		$xPathDoc=new DOMXPath($navigation);
		$pagesXP=$xPathDoc->query("/navigation/node()[name()='main' or name()='advanced']/page");
		$selectMarker=" selected='selected'";
		if ($markedPage===""){
			$string="<option value='through'{$selectMarker}>Все страницы</option>\r\n";
			$string.="<option value='start'>Главная</option>\r\n";
			$pages="";
		}
		else {
			$pages=explode(",",$markedPage);
			if (in_array("start",$pages,true)===true){
				$string="<option value='through'>Все страницы</option>\r\n";
				$string.="<option value='start'{$selectMarker}>Главная</option>\r\n";
			}
			else {
				$string="<option value='through'>Все страницы</option>\r\n";
				$string.="<option value='start'>Главная</option>\r\n";
			}
		}
		if ($pagesXP->length===0){
			$returnedCont=str_replace("{PAGES_LIST}",$string,$template); 
			$returnedCont=str_replace("{COUNT_PAGES}",2,$returnedCont);
		}
		else {
			$returnedCont=str_replace("{PAGES_LIST}",$string.self::genPagesList1($pagesXP,$pages),$template); 
			$returnedCont=str_replace("{COUNT_PAGES}",$pagesXP->length +2,$returnedCont);
		}
		return $returnedCont;
	}
	
	private function genPagesList1($XMLPiece,$selectedMarkers,$tpl="&mdash;&gt;",$marker=" selected='selected'"){
		$str="";
		for($a=0;$a<$XMLPiece->length;$a++){
			if (is_array($selectedMarkers)===true && in_array($XMLPiece->item($a)->attributes->getNamedItem('alias')->nodeValue,$selectedMarkers,true)===true)
				$mark=$marker;
			else 
				$mark="";
			$str.="<option value='".$XMLPiece->item($a)->attributes->getNamedItem('alias')->nodeValue ."'{$mark}>".$tpl.$XMLPiece->item($a)->attributes->getNamedItem('name')->nodeValue."</option>\r\n";
			if ($XMLPiece->item($a)->hasChildNodes()===true)
				$str.=self::genPagesList1($XMLPiece->item($a)->childNodes,$selectedMarkers,"&ndash;".$tpl);
		}
		return $str;
	}
	
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>