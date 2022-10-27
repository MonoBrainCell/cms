<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_settings_enclaveInNavigation extends ErrorsManager {
	const FILE_OF_NAVIGATION="../navigation.fbd.xml";
	const SITEMAP_FILE="../sitemap.xml";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к изменению функционала сайта</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=settings&trunk=settingsManager.php'>Вернуться к настройкам</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	
	public function __construct(){
		if (file_exists(self::FILE_OF_NAVIGATION)===false){
			echo "<span class='error_class'>Не найден файл с навигацией сайта! Обратитесь к Хостинг-Провайдеру для произведения BackUp'а сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (isset($_POST['function_name'])===false || isset($_POST['function_value'])===false || isset($_POST['submit_action'])===false || isset($_POST['function_desc'])===false
		||
		($_POST['function_value']!="on" && $_POST['function_value']!="off")
		){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	public function saveStatus(){
		$navigation=new DOMDocument();
		$navigation->formatOutput=true;
		$navigation->preserveWhiteSpace=false;
		$navigation->load(self::FILE_OF_NAVIGATION);
		$varVar=explode("_",$_POST['function_name']);
		$_POST['function_name']=$varVar[1].".php";
		if ($_POST['function_value']=="on"){
			self::addEnclave($navigation);
			self::rewriteSitemapForEnclave("import");
		}
		else{
			self::removeEnclave($navigation);
			self::rewriteSitemapForEnclave("remove");
		}
		echo "<span class='success_class'>Изменения сохранены.</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
	
	private function addEnclave($navigation){
		$enclaves=array("news.php"=>"Новости","guestbook.php"=>"Гостевая книга");//Add to Array new enclave
		$xPathDoc=new DOMXPath($navigation);
		$check=$xPathDoc->query("/navigation//page[@alias='{$_POST['function_name']}' and @enclave='1']");
		if ($check->length===0){
			$root=$xPathDoc->query("/navigation/main");
			$newTag=$navigation->createElement("page");
			$newTag->setAttribute("alias",$_POST['function_name']);
			$newTag->setAttribute("name",$enclaves[$_POST['function_name']]);
			$newTag->setAttribute("enclave","1");
			$root->item(0)->appendChild($newTag);
		}
		if (@$navigation->save(self::FILE_OF_NAVIGATION)===false){
			echo "<span class='error_class'>Произошёл сбой при записи файла навигации сайта! Проверьте права на возможность перезаписи, данные файлу navigation.fbd.xml, находящемуся в корневой папке сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	
	private function removeEnclave($navigation){
		$xPathDoc=new DOMXPath($navigation);
		$check=$xPathDoc->query("/navigation//page[@alias='{$_POST['function_name']}' and @enclave='1']/parent::*");
		if ($check->length!==0){
			$relacedTag=$xPathDoc->query("/navigation//page[@alias='{$_POST['function_name']}' and @enclave='1']");
			$check->item(0)->removeChild($relacedTag->item(0));
		}
		if (@$navigation->save(self::FILE_OF_NAVIGATION)===false){
			echo "<span class='error_class'>Произошёл сбой при записи файла навигации сайта! Проверьте права на возможность перезаписи, данные файлу navigation.fbd.xml, находящемуся в корневой папке сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	
	private function rewriteSitemapForEnclave($type){
		if (file_exists(self::SITEMAP_FILE)===false)
			return false;
		$domainName="http://{$_SERVER['HTTP_HOST']}/";
		$sitemap=new DOMDocument();
		$sitemap->formatOutput=true;
		$sitemap->preserveWhiteSpace=false;
		$sitemap->load(self::SITEMAP_FILE);
		$nSpace=$sitemap->lookupNamespaceUri($sitemap->namespaceURI);
		$xPathDoc=new DOMXPath($sitemap);
		$xPathDoc->registerNamespace('x', $nSpace);

		$root=$xPathDoc->query("/x:urlset");
		$tags=$xPathDoc->query("/x:urlset/x:url");
		if ($tags->length===0)
			return false;
		for ($a=0;$a<$tags->length;$a++){
			$locCheck=$tags->item($a)->getElementsByTagName("loc")->item(0)->nodeValue;
			if (preg_match("/.*".$_POST['function_name'].".*/u",$locCheck)!==0)
				$root->item(0)->removeChild($tags->item($a));
		}
		if ($type==="import") {
			$functional=str_replace(".php","",$_POST['function_name']);
			$pathToClass="functional_departments/{$functional}/augmentation/importToSitemap.php";
			if (file_exists($pathToClass)===true){
				$clName="App_{$functional}_importToSitemap";
				$importResult=new $clName($sitemap);
				$sitemap=$importResult->getMap();
				
			}
			else
				return false;
		}
		if ($sitemap==NULL || @$sitemap->save(self::SITEMAP_FILE)===false){
			return false;
		}
	}
}
?>