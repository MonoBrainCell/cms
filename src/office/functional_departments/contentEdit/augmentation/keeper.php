<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
/*[а-яА-Яa-zA-Z0-9\,\!\+\-\_\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/] - полный запрет символов (удалять для разрешения)*/
class App_contentEdit_keeper extends ErrorsManager {
	const SIMPLE_TEXT_PATTERN="/[\&\^\~\`\@\#\'\"\<\>]/u";
	const SIMPLE_ALIAS_PATTERN="/[а-яА-Я\,\!\+\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/]/u";
	const SIMPLE_NUM_PATTERN="/[а-яА-Яa-zA-Z\,\!\+\_\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/]/u";
	const NAVIGATION_FILE="../navigation.fbd.xml";
	const SITEMAP_FILE="../sitemap.xml";
	const CONTENT_SAVE_PATH="../content/";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к редактированию страницы</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=contentEdit&trunk=pagesList.php'>Вернуться к списку страниц</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	
	private $dataCheckSettings;
	private $pageIdProp="no id";
	private $newFileId=0;
	private $pageAlias;
	private $datasToRewrite=array();
	private $forbiddenAlias=array("start","main","advanced","news_php","news.php","guestbook_php","guestbook.php");
	public function __construct() {
		$this->dataCheckSettings=array(
			'page_name'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>200,
				'minLenght'=>3,
				'fieldName'=>'Название страницы (title)'
			),
			'page_h1'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>200,
				'minLenght'=>3,
				'fieldName'=>'Основной заголовок страницы (h1)'
			),
			'page_kwords'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>300,
				'fieldName'=>'Ключевые слова страницы'
			),
			'page_desc'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>300,
				'fieldName'=>'Описание страницы'
			),
			'content'=>array(
				'minLenght'=>1,
				'fieldName'=>'Контент страницы'
			),
			'page_navigation_name'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>100,
				'minLenght'=>3,
				'fieldName'=>'Название страницы в навигации'
			),
			'page_alias'=>array(
				'pattern'=>self::SIMPLE_ALIAS_PATTERN,
				'maxLenght'=>150,
				'minLenght'=>3,
				'fieldName'=>'Alias страницы (Адрес страницы)',
				'specialEdition'=>array(
					'pattern'=>'self::correctPageAlias',
					'minLenght'=>'self::convertPageNameToAlias'
				)
			),
			'page_parent'=>array(
				'pattern'=>self::SIMPLE_ALIAS_PATTERN,
				'maxLenght'=>150,
				'minLenght'=>1,
				'fieldName'=>'Родительская страница'
			)
		);
	}
	
	public function startKeeperEngine() {
		if (isset($_POST['page_id'][0])===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		else if ($_POST['page_id'][0]!="start"){
			$type="fullcheck";
			if ($_POST['page_id'][0]!="new"){
				$this->pageIdProp="old id";
				$type="shortcheck";
			}
			else
				$this->pageIdProp="new id";
		}
		else 
			$type="shortcheck";
		if ($type=="shortcheck"){
			unset($this->dataCheckSettings['page_navigation_name']);
			unset($this->dataCheckSettings['page_alias']);
			unset($this->dataCheckSettings['page_parent']);
		}
		foreach ($this->dataCheckSettings as $field=>$properties){
			$SECheckOut=array();
			if (isset($_POST[$field][0])===false){
				echo "<span class='error_class'>Не были переданы данные из поля <q>{$properties['fieldName']}</q></span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			if (isset($properties['pattern'])===true){
				if (preg_match($properties['pattern'],$_POST[$field][0])==1 && mb_strlen(trim($_POST[$field][0]))>0 && isset($properties['specialEdition']['pattern'])===false){
					echo "<span class='error_class'>При заполнении поля <q>{$properties['fieldName']}</q> были использованы запрещённые символы</span>\r\n".self::NAVIGATE_BUTTONS;
					exit;
				}
				else if (preg_match($properties['pattern'],$_POST[$field][0])==1 && mb_strlen(trim($_POST[$field][0]))>0 && isset($properties['specialEdition']['pattern'])===true){
					$SECheckOut[]="pattern";
				}
			}
			if (isset($properties['minLenght'])===true){
				if (mb_strlen(trim($_POST[$field][0]))<$properties['minLenght'] && isset($properties['specialEdition']['minLenght'])===false){
					echo "<span class='error_class'>Вы не заполнили поле <q>{$properties['fieldName']}</q></span>\r\n".self::NAVIGATE_BUTTONS;
					exit;
				}
				else if (mb_strlen(trim($_POST[$field][0]))<$properties['minLenght'] && isset($properties['specialEdition']['minLenght'])===true)
					$SECheckOut[]="minLenght";
			}
			if (isset($properties['maxLenght'])===true){
				if (mb_strlen(trim($_POST[$field][0]))>$properties['maxLenght'] && isset($properties['specialEdition']['maxLenght'])===false){
					echo "<span class='error_class'>При заполнении поля <q>{$properties['fieldName']}</q> Вы превысили лимит количества символов</span>\r\n".self::NAVIGATE_BUTTONS;
					exit;
				}
				else if (mb_strlen(trim($_POST[$field][0]))>$properties['maxLenght'] && isset($properties['specialEdition']['maxLenght'])===true)
					$SECheckOut[]="maxLenght";
			}
			if (empty($SECheckOut)===false){
				foreach ($properties['specialEdition'] as $propName=>$propFunc){
					if (array_search($propName,$SECheckOut,TRUE)!==false){
						$this->datasToRewrite[$field]=call_user_func($propFunc);
						break;
					}
				}
			}
			else {
				$this->datasToRewrite[$field]=trim($_POST[$field][0]);
			}	
		}
		if ($type=="shortcheck"){
			$this->newFileId=$_POST['page_id'][0];
			self::reloadContent();
			self::reloadSitemap();
		}
		else if ($type=="fullcheck"){
			if ($this->pageIdProp!="no id")
				$this->pageAlias=$this->datasToRewrite["page_alias"];
				$this->newFileId=$this->pageAlias;
			self::reloadNavigation();
			self::reloadContent();
			self::reloadSitemap();
		}
		echo "<span class='success_class'>Страница сохранена. Чтобы увидеть изменения обновите данную страницу в браузере.</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
	
	private function reloadContent() {
		$content = "<?php\r\n\$pageTitle = '" . $this->datasToRewrite['page_name'] . "';\r\n" .
		"\$pageH1 = '" . $this->datasToRewrite['page_h1'] . "';\r\n" .
		"\$pageDesc = '" . $this->datasToRewrite['page_desc'] . "';\r\n" .
		"\$pageKWords = '" . $this->datasToRewrite['page_kwords'] . "';\r\n" .
		"\$content = <<<EOT\r\n" . $this->datasToRewrite['content'] . "\r\n" .
		"EOT;\r\n?>";
		if ($this->pageIdProp=="no id"){
			if (@file_put_contents(self::CONTENT_SAVE_PATH ."start.php",$content)===false){
				echo "<span class='error_class'>Произошёл сбой при записи контента сайта! Проверьте права данные файлам в папки content на возможность их перезаписи.</span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
		}
		else {
			if (@file_put_contents(self::CONTENT_SAVE_PATH . $this->newFileId .".php",$content)===false){
				echo "<span class='error_class'>Произошёл сбой при записи контента сайта! Проверьте права данные файлам в папки content на возможность их перезаписи.</span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
		}
	}
	private function reloadNavigation() {
		if (file_exists(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Не найден файл для записи информации о навигации сайта. Скорее всего он был потерен из-за системного сбоя сервера. Обратитесь к Вашему Хостинг-Провайдеру для проведения backUp'а Вашего сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (in_array($this->pageAlias,$this->forbiddenAlias,true)===true){
			echo "<span class='error_class'>Указанный Вами Alias использовать нельзя. Подберите другой.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$navigation=new DOMDocument();
		$navigation->formatOutput=true;
		$navigation->preserveWhiteSpace=false;
		$navigation->load(self::NAVIGATION_FILE);
		$xPathDoc=new DOMXPath($navigation);
		$xPResult=$xPathDoc->query("/navigation//page[@alias='".$this->pageAlias ."']");
		if ($xPResult->length!==0){
			echo "<span class='error_class'>Указанный Вами Alias страницы уже существует. Подберите другой.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if ($this->datasToRewrite['page_parent']=="-1" || $this->datasToRewrite['page_parent']=="0"){
			$parentType="root";
			if ($this->datasToRewrite['page_parent']=="-1")
				$parent="advanced";
			else
				$parent="main";
		}
		else {
			$parentType="page";
			$parent=$this->datasToRewrite['page_parent'];
		}
		if ($parentType=="page"){
			$xPResult=$xPathDoc->query("/navigation//page[@alias='".$parent."']");
			if ($xPResult->length===0){
				$parentType="root"; $parent="main";
			}
			$controlRelatives=$xPathDoc->query("/navigation//page[@alias='".$parent."']/ancestor::advanced");
			if ($controlRelatives->length!==0){
				$parentType="root"; $parent="main";
			}
			unset($controlRelatives);
		}
		if ($parentType=="root")
			$xPResult=$xPathDoc->query("/navigation/".$parent);
		$newPage=$navigation->createElement("page");
		$newPage->setAttribute("alias",$this->pageAlias);
		$newPage->setAttribute("name",$this->datasToRewrite['page_navigation_name']);
		$newPage->setAttribute("enclave","0");
		$xPResult->item(0)->appendChild($newPage);
		if (@$navigation->save(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Произошёл сбой при записи файла навигации сайта! Проверьте права на возможность перезаписи, данные файлу navigation.fbd.xml, находящемуся в корневой папке сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	
	private function reloadSitemap() {
		if (file_exists(self::SITEMAP_FILE)===false)
			return false;
		$domainName="http://{$_SERVER['HTTP_HOST']}/";
		$lastmodDate=date("c");
		$sitemap=new DOMDocument();
		$sitemap->formatOutput=true;
		$sitemap->preserveWhiteSpace=false;
		$sitemap->load(self::SITEMAP_FILE);
		$nSpace=$sitemap->lookupNamespaceUri($sitemap->namespaceURI);
		$xPathDoc=new DOMXPath($sitemap);
		$xPathDoc->registerNamespace('x', $nSpace);
		if ($this->pageIdProp=="no id")
			$pageAddress=$domainName;
		else if ($this->pageIdProp=="old id")
			$pageAddress=$domainName."page/".$this->newFileId;
		else
			$pageAddress=$domainName."page/".$this->pageAlias;
		if ($this->pageIdProp!="new id"){
			$checkTag=$xPathDoc->query("/x:urlset/x:url[x:loc='{$pageAddress}']/x:lastmod");
			$checkTag->item(0)->nodeValue=$lastmodDate;
		}
		else {
			$urlset=$xPathDoc->query("/x:urlset");
			$url=$sitemap->createElement('url');
			$locTag=$sitemap->createElement('loc',$pageAddress);
			$TimeTag=$sitemap->createElement('lastmod',$lastmodDate);
			$urlset->item(0)->appendChild($url);
			$url->appendChild($locTag);
			$url->appendChild($TimeTag);
		}
		if (@$sitemap->save(self::SITEMAP_FILE)===false){
			echo "<span class='error_class'>Произошла ошибка при перезаписи файла sitemap.xml.</span>\r\n".self::NAVIGATE_BUTTONS;
		}
	}
	
	private function leadToLatinSymbs($string){
		$cyr_string=array("а","б","в","г","д","е","ё","ж","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ъ","ы","ь","э","ю","я"," ","/","`","^","#","@","*","$","%",",",";","=",":","<",">",".");
		
		$lat_replace=array("a","b","v","g","d","e","e","zh","z","i","i","k","l","m","n","o","p","r","s","t","u","f","kh","ts","ch","sh","sch","","i","","e","yu","ya","_","_","_","_","_","_","_","_","_","_","_","_","_","_","_","_");
		
		$string=mb_strtolower($string);
		$array=array();$new_word="";
		for ($a=0,$b=mb_strlen($string);$a<$b;$a++){
			$array[]=mb_substr($string,$a,1);
		}
		for ($a=0,$b=count($array);$a<$b;$a++){
			$keyToSymb=array_search($array[$a],$cyr_string);
			if ($keyToSymb===false)
				$new_word.=$array[$a];
			else {
				$new_word.=$lat_replace[$keyToSymb];
			}
		}
		return $new_word;
	}
	
	private function correctPageAlias(){
		return self::leadToLatinSymbs($_POST['page_alias'][0]);
	}
	private function convertPageNameToAlias(){
		return self::leadToLatinSymbs($_POST['page_navigation_name'][0]);
	}
}
?>