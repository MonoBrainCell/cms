<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
/*[а-яА-Яa-zA-Z0-9\,\!\+\-\_\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/] - полный запрет символов (удалять для разрешения)*/
class App_news_keeper extends ErrorsManager {
	const SIMPLE_TEXT_PATTERN="/[\&\^\~\`\@\#\'\"\<\>]/u";
	const NAVIGATION_FILE="../functional/news/newsList.fbd.csv";
	const SITEMAP_FILE="../sitemap.xml";
	const CONTENT_SAVE_PATH="../functional/news/content/";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к редактированию новости</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=news&trunk=newsList.php'>Вернуться к списку новостей</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	const SYMBS_LIMITER=300;
	const RESIDUAL_QUANTITY=150;
	const LIMIT_OF_ITEMS_IN_PAGE=20;
	public function __construct() {	}
	public function startKeeperEngine() {
		$dCS=array(
			'news_header'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>150,
				'minLenght'=>3,
				'fieldName'=>'Новостной заголовок'
			)
		);
		$field='news_header';
		if (isset($_POST['news_id'][0])===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (isset($_POST[$field][0])===false){
			echo "<span class='error_class'>Не были переданы данные из поля <q>{$dCS[$field]['fieldName']}</q></span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (isset($dCS[$field]['pattern'])===true && preg_match($dCS[$field]['pattern'],$_POST[$field][0])===1 && mb_strlen(trim($_POST[$field][0]))>0){
			echo "<span class='error_class'>При заполнении поля <q>{$dCS[$field]['fieldName']}</q> были использованы запрещённые символы</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (isset($dCS[$field]['minLenght'])===true && mb_strlen(trim($_POST[$field][0]))<$dCS[$field]['minLenght']){
			echo "<span class='error_class'>Вы не заполнили поле <q>{$dCS[$field]['fieldName']}</q></span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (isset($dCS[$field]['maxLenght'])===true && mb_strlen(trim($_POST[$field][0]))>$dCS[$field]['maxLenght']){
			echo "<span class='error_class'>При заполнении поля <q>{$dCS[$field]['fieldName']}</q> Вы превысили лимит количества символов</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$datasToRewrite[$field]=trim($_POST[$field][0]);
		if ($_POST['news_id'][0]!="new")
			$datasToRewrite['date']="self";
		else
			$datasToRewrite['date']=date("d-m-Y");
		if (isset($_POST['content_text'][0])===true && mb_strlen(trim($_POST['content_text'][0]))>3){
			$datasToRewrite['content']=stripslashes($_POST['content_text'][0]);
			$tmprVar=self::prepareNewsContent($datasToRewrite['content']);
			$datasToRewrite['shortContent']=$tmprVar[0];
			$datasToRewrite['issuedNews']=$tmprVar[1];
			unset($tmprVar);
		}
		else {
			echo "<span class='error_class'>Вы не заполнили поле <q>Новостной контент</q></span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}

		$rewriteDatas=self::searchParams($_POST['news_id'][0]);
		self::reloadListing($rewriteDatas,$datasToRewrite);
		self::reloadCodeFile($rewriteDatas['newId'],$datasToRewrite['content'],$datasToRewrite['issuedNews'],self::CONTENT_SAVE_PATH);
		if ($_POST['news_id'][0]!=="new")
			$newMarker=false;
		else
			$newMarker=true;
		self::reloadSitemap($rewriteDatas['stringNum'],count($rewriteDatas['fileArray'])+1,$newMarker,$rewriteDatas['newId'],$datasToRewrite['issuedNews']);

		echo "<span class='success_class'>Новость сохранена. Чтобы увидеть изменения обновите данную страницу в браузере.</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
	
	private function prepareNewsContent($content){
		$content=preg_replace("/\&lt\;|\&gt\;/u","",$content);
		$content=html_entity_decode($content,ENT_COMPAT,"utf-8");
		$lengthStr=mb_strlen(strip_tags($content),"utf-8");
		$tag=false;
		$newStr="";
		if ($lengthStr>self::SYMBS_LIMITER){
			$issuedNews=1;
			for ($a=0,$c=1,$b=mb_strlen($content,"utf-8");$a<$b;$a++){
				if ($c===self::RESIDUAL_QUANTITY)
					break;
				$cutStr=mb_substr($content,$a,1,"utf-8");
				$newStr.=$cutStr;
				if ($cutStr==="<"){
					$tag=true; continue;
				}
				else if ($cutStr===">"){
					$tag=false; continue;
				}
				if ($tag===false)
					$c++;
			}
		}
		else {
			$issuedNews=0; $newStr=$content;
		}
		$tagSnip="/\<[a-zA-Z]*[^\>]*$/u";
		$EntitySnip="/\&[a-zA-Z]*$/u";
		$newStr=preg_replace($tagSnip,"",$newStr);
		$newStr=preg_replace($EntitySnip,"",$newStr);
		$pOne='<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body>
<div id="html_content">';
		$pTwo='</div></body></html>';
		$newStr1=$pOne.$newStr.$pTwo;
		$htmlDoc=new DOMDocument();
		@$htmlDoc->loadHTML($newStr1);
		$newStr=$htmlDoc->saveHTML();
		$newStr=str_replace($pOne,"",$newStr);
		$newStr=str_replace($pTwo,"",$newStr);
		$newStr=trim($newStr);
		$newStr=str_replace("\r","",$newStr);
		$newStr=str_replace("\n","",$newStr);		
		return array($newStr,$issuedNews);
	}
	
	private function searchParams($id){
		if (file_exists(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Не найден файл для записи информации о новости. Скорее всего он был потерен из-за системного сбоя сервера. Обратитесь к Вашему Хостинг-Провайдеру для проведения backUp'а Вашего сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
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
				$elem=explode("|--|",$fileArray[$a]);
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
				for ($a=1;$a<=$maxIndex;$a++){
					if (array_search($a,$idArray,TRUE)===false){
						$returnDatas['newId']=$a;
						$successMarker=true;
						break;
					}
				}
			}
			if (isset($successMarker)===false && isset($returnDatas['stringNum'])===false){
				if ($maxIndex!==1 || empty($idArray)===false)
					$returnDatas['newId']=$maxIndex+1;
				else
					$returnDatas['newId']=$maxIndex;
				$returnDatas['stringNum']=$b;
			}
			else if (isset($returnDatas['stringNum'])===false){
				if ($id=="new")
					$returnDatas['stringNum']=$b;
				else
					$returnDatas['stringNum']=$stringsArray[$returnDatas['newId']];
			}
		}
		return $returnDatas;
	}
	
	private function reloadCodeFile($fileId,$fileCode,$issuedNews,$path){
		if ($issuedNews=="0"){
			if (file_exists($path . $fileId .".fbd.tpl")===true)
				unlink($path . $fileId .".fbd.tpl");
		}
		else {
			if (@file_put_contents($path . $fileId .".fbd.tpl",$fileCode)===false){
				echo "<span class='error_class'>Произошёл сбой при записи полного контента новости! Проверьте права данные файлам в папке functional/news/content на возможность их перезаписи.</span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
		}
		
	}
	
	private function reloadListing($fileArray,$settings){
		if ($settings['date']=='self'){
			$varElem=explode("|--|",$fileArray['fileArray'][$fileArray['stringNum']]);
			$settings['date']=$varElem[1];
		}
		$stringReplace="{$fileArray['newId']}|--|{$settings['date']}|--|{$settings['news_header']}|--|{$settings['issuedNews']}|--|{$settings['shortContent']}\r\n";
		$fileArray['fileArray'][$fileArray['stringNum']]=$stringReplace;
		
		$string=implode("",$fileArray['fileArray']);
		if (@file_put_contents(self::NAVIGATION_FILE,$string)===false){
			echo "<span class='error_class'>Произошёл сбой при записи файла списка новостей! Проверьте права на возможность перезаписи, данные файлу newsList.fbd.csv, находящемуся по следующему пути: functional/news/ .</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	
	private function reloadSitemap($strId,$strQuantity,$newMarker,$id,$issuedMarker){
		if (file_exists(self::SITEMAP_FILE)===false)
			return false;
		function createElementsGroup($doc,$root,$addr,$index,$lastModTime){
			$newUrl=$doc->createElement("url");
			$newLoc=$doc->createElement("loc",$addr.$index);
			$newLastMod=$doc->createElement("lastmod",$lastModTime);
			$root->item(0)->appendChild($newUrl);
			$newUrl->appendChild($newLoc); $newUrl->appendChild($newLastMod);
			return array($doc,$root);
		}
		$domainName_item="http://{$_SERVER['HTTP_HOST']}/news.php?article=";
		$domainName_group="http://{$_SERVER['HTTP_HOST']}/news.php?gId=";
		$lastmodDate=date("c");
		$sitemap=new DOMDocument();
		$sitemap->formatOutput=true;
		$sitemap->preserveWhiteSpace=false;
		$sitemap->load(self::SITEMAP_FILE);
		$itemPosition=ceil( ($strId+1) / self::LIMIT_OF_ITEMS_IN_PAGE);
		$nSpace=$sitemap->lookupNamespaceUri($sitemap->namespaceURI);
		$xPathDoc=new DOMXPath($sitemap);
		$xPathDoc->registerNamespace('x', $nSpace);
		$root=$xPathDoc->query("/x:urlset");
		if ($newMarker===true){
			if ($strQuantity % self::LIMIT_OF_ITEMS_IN_PAGE===1){
				$strQuantity--;$decreaseMark=true;
			}
			$pagesQuantity=ceil($strQuantity / self::LIMIT_OF_ITEMS_IN_PAGE);
			for ($a=1;$a<=$pagesQuantity;$a++){
				$page=$xPathDoc->query("/x:urlset/x:url[x:loc='{$domainName_group}{$a}']/x:lastmod");
				$page->item(0)->nodeValue=$lastmodDate;
			}
			if (isset($decreaseMark)===true){
				$tmpVar=createElementsGroup($sitemap,$root,$domainName_group,$a,$lastmodDate);
				$sitemap=$tmpVar[0];
				$root=$tmpVar[1];
				unset($tmpVar);
			}
		}
		else {
			$page=$xPathDoc->query("/x:urlset/x:url[x:loc='{$domainName_group}{$itemPosition}']/x:lastmod");
			$page->item(0)->nodeValue=$lastmodDate;
		}
		if ($issuedMarker=="0"){
			$issuedPage=$xPathDoc->query("/x:urlset/x:url[x:loc='{$domainName_item}{$id}']");
			if ($issuedPage->length!==0)
				$root->item(0)->removeChild($issuedPage->item(0));
		}
		else {
			$issuedPage=$xPathDoc->query("/x:urlset/x:url[x:loc='{$domainName_item}{$id}']");
			if ($issuedPage->length!==0)
				$issuedPage->item(0)->getElementsByTagName('lastmod')->item(0)->nodeValue=$lastmodDate;
			else {
				$tmpVar=createElementsGroup($sitemap,$root,$domainName_item,$id,$lastmodDate);
				$sitemap=$tmpVar[0];
				$root=$tmpVar[1];
				unset($tmpVar);
			}
		}
		if (@$sitemap->save(self::SITEMAP_FILE)===false){
			echo "<span class='error_class'>Произошла ошибка при перезаписи файла sitemap.xml.</span>\r\n".self::NAVIGATE_BUTTONS;
		}
	}
}
?>