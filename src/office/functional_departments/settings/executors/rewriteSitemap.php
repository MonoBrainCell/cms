<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class rewriteSitemap {
	const DOMAIN_FILE="information_base/domain.txt";
	const NAVIGATION_FILE="../navigation.fbd.xml";
	const SITEMAP_FILE="../sitemap.xml";
	const NAVIGATE_BUTTONS="<a class='buttons_from_script' href='office/index.php?dep=settings&trunk=settingsManager.php'>Вернуться к настройкам</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	
	public function __construct($for_ajax_rqst=true){
		$this->refreshSitemap($for_ajax_rqst);
	}
	
	private function refreshSitemap($for_ajax){
		if (file_exists(self::NAVIGATION_FILE)===false){
			if($for_ajax===true){
				echo "<span class='error_class'>Не найден файл с навигацией сайта! Обратитесь к Хостинг-Провайдеру для произведения BackUp'а сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			else
				return "";
		}
		if (isset($_POST['action_agree'])===false && $for_ajax===true){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		
		$domainName="http://{$_SERVER['HTTP_HOST']}/";
		$lastmodDate=date("c");
		$sitemap=new DOMDocument('1.0','UTF-8');
		$sitemap->formatOutput=true;
		$sitemap->preserveWhiteSpace=false;
		$urlset=$sitemap->createElement('urlset');
		$sitemap->appendChild($urlset);
		$urlset->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
		
		$navigation=new DOMDocument();
		$navigation->formatOutput=true;
		$navigation->preserveWhiteSpace=false;
		$navigation->load(self::NAVIGATION_FILE);
		$xPathDoc=new DOMXPath($navigation);
		$pages=$xPathDoc->query("/navigation/*//page");
		
		$urlTag=$sitemap->createElement('url');
		$urlset->appendChild($urlTag);
		$locTag=$sitemap->createElement('loc',$domainName);
		$urlTag->appendChild($locTag);
		$lmTag=$sitemap->createElement('lastmod',$lastmodDate);
		$urlTag->appendChild($lmTag);
		
		if ($pages->length!==0){
			for ($a=0;$a<$pages->length;$a++){
				if ($pages->item($a)->attributes->getNamedItem('enclave')->nodeValue!="1"){
					$urlTag=$sitemap->createElement('url');
					$urlset->appendChild($urlTag);
					
					$locTag=$sitemap->createElement('loc',$domainName."page/".$pages->item($a)->attributes->getNamedItem('alias')->nodeValue);
					$urlTag->appendChild($locTag);
					
					$lmTag=$sitemap->createElement('lastmod',$lastmodDate);
					$urlTag->appendChild($lmTag);
				}
				else if ($pages->item($a)->attributes->getNamedItem('enclave')->nodeValue==="1"){
					//$sitemap=self::refreshSitemap($sitemap);
					//$sitemap->normalizeDocument();
					$className=str_replace(".php","",$pages->item($a)->attributes->getNamedItem('alias')->nodeValue);
					$className="App_{$className}_importToSitemap";
					$varObj=new $className($sitemap);
					$changedMap=$varObj->getMap();
					if($changedMap!=NULL){
					    $sitemap=$changedMap;
					}
				}
			}
		}
		
		if(file_put_contents(self::DOMAIN_FILE,$_SERVER['HTTP_HOST'])===false && $for_ajax===true){
			echo "<span class='error_class'>Произошла ошибка при сохранении информации о текущем доменном адресе.</span><hr>\r\n";
		}
		
		if (@$sitemap->save(self::SITEMAP_FILE)===false){
			if($for_ajax===true){
				echo "<span class='error_class'>Произошла ошибка при перезаписи файла sitemap.xml.</span>\r\n".self::NAVIGATE_BUTTONS;
			}
		}
		if($for_ajax===true){
			echo "<span class='success_class'>Файл sitemap.xml был успешно перезаписан!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		else
			return "";
	}
}
if(isset($_POST['from_admin_page'])===true)
	$varObj=new rewriteSitemap();
else
	$varObj=new rewriteSitemap(false);
?>