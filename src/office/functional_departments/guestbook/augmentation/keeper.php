<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
/*[а-яА-Яa-zA-Z0-9\,\!\+\-\_\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/] - полный запрет символов (удалять для разрешения)*/
class App_guestbook_keeper extends ErrorsManager {
	const SIMPLE_TEXT_PATTERN="/[\&\^\~\`\@\#\'\"\<\>]/u";
	const NAVIGATION_FILE="../functional/guestbook/recordsList.fbd.csv";
	const SITEMAP_FILE="../sitemap.xml";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к редактированию записи</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=guestbook&trunk=recordsList.php'>Вернуться к списку записей</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	const LIMIT_OF_ITEMS_IN_PAGE=10;
	private $dataCheckSettings;
	
	public function __construct() {
		$this->dataCheckSettings=array(
		'guest_name'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>50,
				'minLenght'=>2,
				'fieldName'=>'Имя посетителя'
			),
		'content_text'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>150,
				'minLenght'=>2,
				'fieldName'=>'Содержание записи'
			)
		);
	}
	public function startKeeperEngine() {
		$datasToRewrite=array();
		foreach ($this->dataCheckSettings as $field=>$properties){
			if (isset($_POST[$field][0])===false){
				echo "<span class='error_class'>Не были переданы данные из поля <q>{$properties['fieldName']}</q></span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			if (isset($properties['pattern'])===true && preg_match($properties['pattern'],$_POST[$field][0])==1 && mb_strlen(trim($_POST[$field][0]))>0){
				echo "<span class='error_class'>При заполнении поля <q>{$properties['fieldName']}</q> были использованы запрещённые символы</span>\r\n".self::NAVIGATE_BUTTONS;
				exit;

			}
			if (isset($properties['minLenght'])===true && mb_strlen(trim($_POST[$field][0]))<$properties['minLenght']){
				echo "<span class='error_class'>Вы не заполнили поле <q>{$properties['fieldName']}</q></span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			if (isset($properties['maxLenght'])===true && mb_strlen(trim($_POST[$field][0]))>$properties['maxLenght']){
				echo "<span class='error_class'>При заполнении поля <q>{$properties['fieldName']}</q> Вы превысили лимит количества символов</span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			$datasToRewrite[$field]=$_POST[$field][0];
		}
		$datasToRewrite['content_text']=nl2br($datasToRewrite['content_text'],FALSE);
		$datasToRewrite['content_text']=str_replace("\r","",$datasToRewrite['content_text']);
		$datasToRewrite['content_text']=str_replace("\n","",$datasToRewrite['content_text']);
		$rewriteDatas=self::searchParams($_POST['rec_id'][0]);
		self::reloadListing($rewriteDatas,$datasToRewrite);
		self::reloadSitemap($rewriteDatas['stringNum'],count($rewriteDatas['fileArray']));

		echo "<span class='success_class'>Запись сохранена. Чтобы увидеть изменения обновите данную страницу в браузере.</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}

	private function searchParams($id){
		if (file_exists(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Не найден файл для записи информации о записи. Скорее всего он был потерен из-за системного сбоя сервера. Обратитесь к Вашему Хостинг-Провайдеру для проведения backUp'а Вашего сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$fileArray=file(self::NAVIGATION_FILE);	
		$returnDatas=array(); $returnDatas['fileArray']=array(); $idArray=array();$stringsArray=array(); $maxIndex=1;
		if (empty($fileArray)===true || count($fileArray)<1) {
			$returnDatas['newId']=1;
			$returnDatas['stringNum']=0;
			$returnDatas['fileArray']=array();
		}
		else {
			for ($a=0,$b=count($fileArray);$a<$b;$a++){
				$elem=explode(";;",$fileArray[$a]);
				if ($id=="new"){
					$idArray[]=$elem[0]*1;
					$stringsArray[$elem[0]]=$a;
					if ($maxIndex<$elem[0])
						$maxIndex=$elem[0];
				}
				else {
					if ($elem[0]==$id){
						$returnDatas['newId']=$elem[0];
						$returnDatas['stringNum']=$a;
					}
				}
				$returnDatas['fileArray'][]=$fileArray[$a];
			}
			if ($maxIndex!==1){
				for ($a=1;$a<$maxIndex;$a++){
					if (array_search($a,$idArray,TRUE)===false){
						$returnDatas['newId']=$a;
						$successMarker=true;
						break;
					}
				}
				if (isset($successMarker)===false){
					$returnDatas['newId']=$maxIndex+1;
					$returnDatas['stringNum']=$b;
				}
				else{
					if ($id=="new")
						$returnDatas['stringNum']=$b;
					else
						$returnDatas['stringNum']=$stringsArray[$returnDatas['newId']];
				}
			}
		}	
		return $returnDatas;
	}
	
	private function reloadListing($fileArray,$settings){
		$varElem=explode(";;",$fileArray['fileArray'][$fileArray['stringNum']]);
		$settings['date']=$varElem[2];
		$settings['email']=$varElem[3];
		$stringReplace="{$fileArray['newId']};;{$settings['guest_name']};;{$settings['date']};;{$settings['email']};;{$settings['content_text']}\r\n";
		$fileArray['fileArray'][$fileArray['stringNum']]=$stringReplace;
		
		$string=implode("",$fileArray['fileArray']);
		if (@file_put_contents(self::NAVIGATION_FILE,$string)===false){
			echo "<span class='error_class'>Произошёл сбой при записи файла списка записей гостевой книги! Проверьте права на возможность перезаписи, данные файлу recordsList.fbd.csv, находящемуся по следующему пути: functional/guestbook/ .</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	
	private function reloadSitemap($strId,$strQuantity){
		if (file_exists(self::SITEMAP_FILE)===false)
			return false;
		$domainName_group="http://{$_SERVER['HTTP_HOST']}/guestbook.php?gId=";
		$lastmodDate=date("c");
		$sitemap=new DOMDocument();
		$sitemap->formatOutput=true;
		$sitemap->preserveWhiteSpace=false;
		$sitemap->load(self::SITEMAP_FILE);
		$itemPosition=ceil( ($strId+1) / self::LIMIT_OF_ITEMS_IN_PAGE);
		$nSpace=$sitemap->lookupNamespaceUri($sitemap->namespaceURI);
		$xPathDoc=new DOMXPath($sitemap);
		$xPathDoc->registerNamespace('x', $nSpace); 
		$checkTag=$xPathDoc->query("/x:urlset/x:url[x:loc='{$domainName_group}{$itemPosition}']/x:lastmod");
		if ($checkTag->length===0)
			return false;
		$checkTag->item(0)->nodeValue=$lastmodDate;
		if (@$sitemap->save(self::SITEMAP_FILE)===false){
			echo "<span class='error_class'>Произошла ошибка при перезаписи файла sitemap.xml.</span>\r\n".self::NAVIGATE_BUTTONS;
		}
	}
}
?>