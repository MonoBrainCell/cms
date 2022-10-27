<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class Pre__news extends ErrorsManager{
	const CURR_ALIAS="news.php";
	//---
	const PATH_TO_DUMMIES="design/";
	const DUMMY_FILE="dummy.fbd.html";
	const NEWS_DUMMY_FILE="news_dummy.fbd.html";
	const SETTINGS_FILE="settings.fbd.php";
	const CONTENT_PATH="functional/news/content/";
	const ENGINE_OFF_FILE="off.fbd.html";
	const ENGINE_OFF_HTML="<h1>Сайт закрыт на реконструкцию</h1>";
	const E404_FILE="404.html";
	const E404_HTML="<h1>Данная страница недоступна.</h1><p>Вернитесь на <a href='{DOMEN_NAME}'>главную</a></p>";
	const EMPTY_NEWS_BLOCK="<h2>Новостей пока нет. Ждите! Работа над новостным контентом идёт полным ходом.</h2>";
	//---
	const PAGE_ADDRESS_POSTFIX="?gId=";
	const ARTICLE_ADDRESS_POSTFIX="?article=";
	const NEWS_LIST_FILE="functional/news/newsList.fbd.csv";
	const ELEMS_ON_PAGE=20;
	const NEWS_BLOCK_TMP="functional/news/dummyes/newsBlockTpls.fbd.tpl";
	const NEWS_ARTICLE_TMP="functional/news/dummyes/newsItemTpls.fbd.tpl";
	const LEVEL1_SEPARATOR="|--|";
	
	private $output=array();
	private $newsList=array();
	
	public function __construct($type,$num){
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
		
		$this->newsList=file(self::NEWS_LIST_FILE);
		if ($type=="news")
			self::genContent($num);
		
		else
			self::genContent($num,self::CURR_ALIAS,$sets['mainEngineSearch']);
		
		self::genEmbientContent(self::CURR_ALIAS,$sets['templateSearch']);
		$this->output['html']=str_replace("{_HEAD_STRING_}",implode("\r\n",$this->output['headers']),$this->output['html']);
	}
	
	private function initSets(){
		if (file_exists(self::SETTINGS_FILE)===false)
			parent::hideErrorByMessage();
		if (file_exists(self::NEWS_ARTICLE_TMP)===false || file_exists(self::NEWS_BLOCK_TMP)===false || file_exists(self::NEWS_LIST_FILE)===false)
			parent::hideErrorByMessage();
		include self::SETTINGS_FILE;
		if (isset($siteEngineSwitcher)===true && isset($selectedDesign)===true && isset($functional)===true){
			if ($siteEngineSwitcher=="off"){
				if (file_exists(self::PATH_TO_DUMMIES .$selectedDesign."/".self::ENGINE_OFF_FILE)===true)
					echo file_get_contents(self::PATH_TO_DUMMIES .$selectedDesign."/".self::ENGINE_OFF_FILE);
				else 
					echo self::ENGINE_OFF_HTML;
				header("http/1.1 404 Not Found");
				exit;
			}
			else if (file_exists(self::PATH_TO_DUMMIES .$selectedDesign."/".self::DUMMY_FILE)===false
			&&
			file_exists(self::PATH_TO_DUMMIES .$selectedDesign."/".self::NEWS_DUMMY_FILE)===false)
				parent::hideErrorByMessage();
			else {
				$functional=stripslashes($functional);
				$functionalArr=@unserialize($functional);
				if ($functionalArr===false)
					parent::hideErrorByMessage();
				if (file_exists(self::PATH_TO_DUMMIES .$selectedDesign."/".self::NEWS_DUMMY_FILE)===true)
					$this->output['html']=file_get_contents(self::PATH_TO_DUMMIES .$selectedDesign."/".self::NEWS_DUMMY_FILE);
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
				if ($sets[$a]['ramification'][$c]['engineSwitcher']=="on")
					$content=$mod->{$sets[$a]['ramification'][$c]['methodName']}($content);
			}
		}
		
		return $content;
	}
	
	private function genContent($num,$id=false,$sets=false){
		if ($id===false || $sets===false){
			$content=self::genNewsList($num);
		}
		else
			$content=self::genNewsArticle($num);
		
		$this->output['html']=str_replace("{_TITLE_}",$content['title'],$this->output['html']);
		$this->output['html']=str_replace("{_DESCRIPTION_}",$content['description'],$this->output['html']);
		$this->output['html']=str_replace("{_KEYWORDS_}",$content['keywords'],$this->output['html']);
		$this->output['html']=str_replace("{_HEADER_1_}",$content['h1'],$this->output['html']);
		if ($id===false || $sets===false)
			$this->output['html']=str_replace("{_CONTENT_}",$content['content'],$this->output['html']);
		else {			
			$tmpVar=self::walkOutGenFunctional($id,$sets,array('html'=>$content['content'],'headers'=>$this->output['headers']));
			$content['content']=$tmpVar['html'];
			if (empty($tmpVar['headers'])===false)
				$this->output['headers']=$tmpVar['headers'];
			
			$this->output['html']=str_replace("{_CONTENT_}",$content['content'],$this->output['html']);
			
		}
	}
	
	private function genNewsList($num){
		$news_list=array_reverse($this->newsList);
		$navTmps=array();
		$tmp=file_get_contents(self::NEWS_BLOCK_TMP);
		list($headers,$wrapper,$selfItem,$anchor,$navTmps['wrapper'],$navTmps['anchor'],$navTmps['current'])=array_pad(explode(self::LEVEL1_SEPARATOR,$tmp),7,false);
		$content=array();
		if (isset($headers)===false || isset($wrapper)===false || isset($selfItem)===false || isset($anchor)===false || isset($navTmps['wrapper'])===false || isset($navTmps['anchor'])===false || isset($navTmps['current'])===false){
			$content['title']="";$content['description']="";$content['keywords']="";$content['h1']="";$content['content']="";
			return $content;
		}
		$numeralAddon=self::genNumeralNavigation($num,count($news_list),$navTmps);
		$content['content']="";
		if (empty($news_list)===false && mb_strlen(trim($news_list[0]))>0){
			$keywords=array();
			for ($a=$numeralAddon['startPosition'];$a<$numeralAddon['endPosition'];$a++){
				$structureElems=explode("|--|",rtrim($news_list[$a]));
				$content['content'].=str_replace("{TIME}",$structureElems[1],$selfItem);
				$content['content']=str_replace("{NEWS_HEADER}",$structureElems[2],$content['content']);
				$keywords[]=$structureElems[2];
				$content['content']=str_replace("{CONTAINER_CONTENT}",$structureElems[4],$content['content']);
				if ($structureElems[3]=="1"){
					$content['content']=str_replace("{ANCHOR_TO_FULL_NEWS}",$anchor,$content['content']);
					$content['content']=str_replace("{NEWS_ITEM_ADDRESS}",self::CURR_ALIAS .self::ARTICLE_ADDRESS_POSTFIX .$structureElems[0],$content['content']);
				}
				else
					$content['content']=str_replace("{ANCHOR_TO_FULL_NEWS}","",$content['content']);
			}
			$content['content']=str_replace("{CONTAINER_CONTENT}",$content['content'],$wrapper);
			$content['content']=str_replace("{NUMERAL_NAVIGATION}",$numeralAddon['numeralNavigation'],$content['content']);
			$content['keywords']=implode(",",$keywords);
		}
		else {
			$content['content']=str_replace("{CONTAINER_CONTENT}",self::EMPTY_NEWS_BLOCK,$wrapper);
			$content['content']=str_replace("{NUMERAL_NAVIGATION}","",$content['content']);
			$content['keywords']="Новости";
		}
		$content['description']="Здесь можно ознакомиться с новостями этого сайта";
		$content['title']='Новости. Страница '.$num;
		$content['h1']='Новостной блок. Страница '.$num;
		if (strlen(trim($headers))>0)
			$this->output['headers'][]=$headers;
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
	
	private function genNewsArticle($num){
		$content=array();
		for ($a=0,$b=count($this->newsList);$a<$b;$a++){
			$elem=explode("|--|",rtrim($this->newsList[$a]));
			if ($elem[0]==$num){
				$curElem=$elem;
				break;
			}
		}
		if (isset($curElem)===false)
			parent::hideErrorByMessage();
		if (file_exists(self::CONTENT_PATH .$num.".fbd.tpl")===false)
			$text="";
		else
			$text=file_get_contents(self::CONTENT_PATH .$num.".fbd.tpl");
		$cTmp=file_get_contents(self::NEWS_ARTICLE_TMP);
		list($headers,$tmp)=array_pad(explode(self::LEVEL1_SEPARATOR,$cTmp),2,false);
		if (isset($headers)===false || isset($tmp)===false){
			$content['title']="";$content['description']="";$content['keywords']="";$content['h1']="";$content['content']="";
			return $content;
		}
		if (strlen(trim($headers))>0)
			$this->output['headers'][]=$headers;
		$content['content']=str_replace('{TIME}',$curElem[1],$tmp);
		$content['content']=str_replace('{CONTAINER_CONTENT}',$text,$content['content']);
		$content['title']="Новость. {$curElem[2]}";
		$content['description']="Новость. {$curElem[2]}. Добавлена:{$curElem[1]}";
		$content['keywords']="Новости,{$curElem[2]}";
		$content['h1']=$curElem[2];
		return $content;
	}
	
	
	private function genEmbientContent($id,$sets){
		$this->output=self::walkOutGenFunctional($id,$sets,$this->output);
	}
	
	public function echoPage(){
		echo $this->output['html'];
	}
}
?>
