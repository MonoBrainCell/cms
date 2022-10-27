<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class Pre__guestbook extends ErrorsManager{
	const CURR_ALIAS="guestbook.php";
	//---
	const PATH_TO_DUMMIES="design/";
	const DUMMY_FILE="dummy.fbd.html";
	const GB_DUMMY_FILE="guestbook_dummy.fbd.html";
	const SETTINGS_FILE="settings.fbd.php";
	const CONTENT_PATH="functional/guestbook/content/";
	const ENGINE_OFF_FILE="off.fbd.html";
	const ENGINE_OFF_HTML="<h1>Сайт закрыт на реконструкцию</h1>";
	const E404_FILE="404.html";
	const E404_HTML="<h1>Данная страница недоступна.</h1><p>Вернитесь на <a href='{DOMEN_NAME}'>главную</a></p>";
	const EMPTY_BOOK_MESSAGE="<h2>Ещё никто не оставлял здесь записей.</h2>";
	//---
	const PAGE_ADDRESS_POSTFIX="?gId=";
	const GB_LIST_FILE="functional/guestbook/recordsList.fbd.csv";
	const ELEMS_ON_PAGE=10;
	const GB_BLOCK_TMP="functional/guestbook/dummyes/guestbookBlockTpls.fbd.tpl";
	const GB_RECORD_TMP="functional/guestbook/dummyes/guestbookRecordTpls.fbd.tpl";
	const LEVEL1_SEPARATOR="|--|";
	const TEXTAREA_MASK="[textfield]";
	
	private $output=array();
	private $recordsList=array();
	
	public function __construct($num){
		$sets=self::initSets();
		
		for ($a=0,$b=count($sets['enclaveEngines']);$a<$b;$a++){
			if ($sets['enclaveEngines'][$a]['engineName']==self::CURR_ALIAS && $sets['enclaveEngines'][$a]['engineSwitcher']=="on"){
				$engOn=true;
			}
		}
		if (isset($engOn)===false){
			if (file_exists(self::E404_FILE)===true)
				echo file_get_contents(self::E404_FILE);
			else 
				echo str_replace("{DOMEN_NAME}","http://{$_SERVER['HTTP_HOST']}/",self::E404_HTML);
			header("http/1.1 404 Not Found");
			exit;
		}
		
		$this->recordsList=file(self::GB_LIST_FILE);
		
		self::genContent($num);
		self::genEmbientContent(self::CURR_ALIAS,$sets['templateSearch']);
		$this->output['html']=str_replace("{_HEAD_STRING_}",implode("\r\n",$this->output['headers']),$this->output['html']);
	}
	
	private function initSets(){
		if (file_exists(self::SETTINGS_FILE)===false)
			parent::hideErrorByMessage();
		if (file_exists(self::GB_RECORD_TMP)===false || file_exists(self::GB_BLOCK_TMP)===false || file_exists(self::GB_LIST_FILE)===false)
			parent::hideErrorByMessage();
		include self::SETTINGS_FILE;
		if (isset($siteEngineSwitcher)===true && isset($selectedDesign)===true && isset($functional)===true){
			if ($siteEngineSwitcher=="off"){
				if (file_exists(self::PATH_TO_DUMMIES .$selectedDesign."/".self::ENGINE_OFF_FILE)===true)
					echo file_get_contents(self::PATH_TO_DUMMIES .$selectedDesign."/".self::ENGINE_OFF_FILE);
				else
					echo self::ENGINE_OFF_HTML;
				exit;
			}
			else if (file_exists(self::PATH_TO_DUMMIES .$selectedDesign."/".self::DUMMY_FILE)===false
			&&
			file_exists(self::PATH_TO_DUMMIES .$selectedDesign."/".self::GB_DUMMY_FILE)===false)
				parent::hideErrorByMessage();
			else {
				$functional=stripslashes($functional);
				$functionalArr=@unserialize($functional);
				if ($functionalArr===false)
					parent::hideErrorByMessage();
				if (file_exists(self::PATH_TO_DUMMIES .$selectedDesign."/".self::GB_DUMMY_FILE)===true)
					$this->output['html']=file_get_contents(self::PATH_TO_DUMMIES .$selectedDesign."/".self::GB_DUMMY_FILE);
				else
					$this->output['html']=file_get_contents(self::PATH_TO_DUMMIES .$selectedDesign."/".self::DUMMY_FILE);
				$this->output['headers']=array();
				$baseTag=new Wspc__baseTagGen();
				$this->output['html']=str_replace("{_BASE_TAG_}",$baseTag->getHtmlPiece(),$this->output['html']);
				return $functionalArr;
			}
		}
		else
			parent::hideErrorByMessage();
	}
	
	private function walkOutGenFunctional($id,$sets,$content){
		for ($a=0,$b=count($sets);$a<$b;$a++){
			$mod=new $sets[$a]['className']($id);
			for ($c=0,$d=count($sets[$a]['ramification']);$c<$d;$c++){
				if ($sets[$a]['ramification'][$c]['engineSwitcher']=="on") {
					$content=$mod->{$sets[$a]['ramification'][$c]['methodName']}($content);
				}
			}
		}
		
		return $content;
	}
	
	private function genContent($num){
		$content=self::genRecordsList($num);
		
		$this->output['html']=str_replace("{_TITLE_}","Гостевая книга",$this->output['html']);
		$this->output['html']=str_replace("{_DESCRIPTION_}","Гостевая книга. Здесь Вы можете оставить отзыв о нашей работе и работе нашего сайта.",$this->output['html']);
		$this->output['html']=str_replace("{_KEYWORDS_}","Гостевая книга, оставить отзыв",$this->output['html']);
		$this->output['html']=str_replace("{_HEADER_1_}","Гостевая книга. Страница ".$num,$this->output['html']);
		$this->output['html']=str_replace("{_CONTENT_}",$content,$this->output['html']);
	}
	
	private function genRecordsList($num){
		$records_list=array_reverse($this->recordsList);
		$navTmps=array();
		$tmp=file_get_contents(self::GB_BLOCK_TMP);
		list($headers,$wrapper,$navTmps['wrapper'],$navTmps['anchor'],$navTmps['current'],$form)=array_pad(explode(self::LEVEL1_SEPARATOR,$tmp),6,false);
		if (isset($headers)===false || isset($wrapper)===false || isset($form)===false || isset($navTmps['wrapper'])===false || isset($navTmps['anchor'])===false || isset($navTmps['current'])===false){
			$content="";
			return $content;
		}
		$tmp=file_get_contents(self::GB_RECORD_TMP);
		list($headers1,$selfItem)=array_pad(explode(self::LEVEL1_SEPARATOR,$tmp),2,false);
		if (isset($headers1)===false || isset($selfItem)===false){
			$content="";
			return $content;
		}
		if (empty($records_list)===false && mb_strlen(trim($records_list[0]))>0){
			$numeralAddon=self::genNumeralNavigation($num,count($records_list),$navTmps);
			$content="";
			for ($a=$numeralAddon['startPosition'];$a<$numeralAddon['endPosition'];$a++){
				$structureElems=explode(";;",rtrim($records_list[$a]));
				$content.=str_replace("{TIME}",$structureElems[2],$selfItem);
				$content=str_replace("{GUEST_NAME}",$structureElems[1],$content);
				$content=str_replace("{CONTAINER_CONTENT}",$structureElems[4],$content);
				
			}
			$content=str_replace("{CONTAINER_CONTENT}",$content,$wrapper);
			$content=str_replace("{NUMERAL_NAVIGATION}",$numeralAddon['numeralNavigation'],$content);
		}	
		else {
			$content=str_replace("{CONTAINER_CONTENT}",self::EMPTY_BOOK_MESSAGE,$wrapper);
			$content=str_replace("{NUMERAL_NAVIGATION}","",$content);
		}
		$form=str_replace(self::TEXTAREA_MASK,'textarea',$form);
		$form=preg_replace("/\&\[([a-zA-Z0-9]+)\]\;/","&$1;",$form);
		$content=str_replace("{GUESTBOOK_FORM}",$form,$content);
		if (strlen(trim($headers))>0)
			$this->output['headers'][]=$headers;
		if (strlen(trim($headers1))>0)
			$this->output['headers'][]=$headers1;
		return $content;
	}
		
	private function genNumeralNavigation($pageN,$totalNumber,$tmps){
		$string="";
		$lastPage=ceil($totalNumber / self::ELEMS_ON_PAGE); 
		if ($pageN>$lastPage)
			$pageN=$lastPage;
		for ($a=1;$a<=$lastPage;$a++){
			if ($a!=$pageN){
				$string.=str_replace("{PAGE_NUMBER}",$a,$tmps['anchor']);
				$string=str_replace("{PAGE_ADDRESS}",self::CURR_ALIAS .self::PAGE_ADDRESS_POSTFIX .$a,$string);
			}
			else
				$string.=str_replace("{PAGE_NUMBER}",$a,$tmps['current']);
		}
		$startPosition=($pageN-1) * self::ELEMS_ON_PAGE;
		if ($pageN!=$lastPage)
			$endPosition=$pageN * self::ELEMS_ON_PAGE;
		else 
			$endPosition=$totalNumber;
		$string=str_replace("{CONTAINER_CONTENT}",$string,$tmps['wrapper']);
		return array("startPosition"=>$startPosition,"endPosition"=>$endPosition,"numeralNavigation"=>$string);
	}
	
	private function genEmbientContent($id,$sets){
		$this->output=self::walkOutGenFunctional($id,$sets,$this->output);
	}
	
	public function echoPage(){
		echo $this->output['html'];
	}
}
?>
