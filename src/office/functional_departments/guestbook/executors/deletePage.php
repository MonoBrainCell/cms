<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class deletePage extends ErrorsManager {
	const NAVIGATION_FILE="../functional/guestbook/recordsList.fbd.csv";
	const SITEMAP_FILE="../sitemap.xml";
	const PAGE_DELETE_PATH="../functional/guestbook/content/";
	const NAVIGATE_BUTTONS="<a class='buttons_from_script' href='office/index.php?dep=guestbook&trunk=recordsList.php'>Вернуться к списку записей</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	const LIMIT_OF_ITEMS_IN_PAGE=10;
	private $fileId=0;
	private $strNum=0;
	private $itemsQuantity=0;
	
	public function __construct() {
		if (isset($_POST['submitBut'])===false || isset($_POST['pageNum'])===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$this->fileId=$_POST['pageNum'];
	}
	public function deleteStartEngine(){
		if (file_exists(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Не найден файл для записи информации о новостях сайта. Скорее всего он был потерен из-за системного сбоя сервера. Обратитесь к Вашему Хостинг-Провайдеру для проведения backUp'а Вашего сайта.</span>\r\n".self::NAVIGATE_BUTTONS;;
			exit;
		}
		self::rewriteNavigationFile();
		if (file_exists(self::PAGE_DELETE_PATH . $this->fileId .".fbd.tpl")===true){
			if (@unlink(self::PAGE_DELETE_PATH . $this->fileId .".fbd.tpl")===false){
				echo "<span class='error_class'>Произошла ошибка при удалении файла<strong>:". self::PAGE_DELETE_PATH . $this->fileId .".fbd.tpl</strong>. Пожалуйста проверьте факт его отсутствия через Ftp-доступ к Вашему сайту.</span>";
			}
		}
		if (file_exists(self::SITEMAP_FILE)===true){
			self::rewriteSitemapFile();
		}
		echo "<span class='success_class'>Удаление прошло успешно!</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
	private function rewriteNavigationFile(){
		$navigationArray=file(self::NAVIGATION_FILE);
		$content="";
		$this->itemsQuantity=count($navigationArray);
		for($a=0;$a<$this->itemsQuantity;$a++){
			$elem=explode(";;",rtrim($navigationArray[$a]));
			if ($elem[0]!=$this->fileId)
				$content.=$navigationArray[$a];
			else 
				$this->strNum=$a+1;
		}
		if (@file_put_contents(self::NAVIGATION_FILE,$content)===false){
			echo "<span class='error_class'>Произошёл сбой при записи файла навигации сайта! Проверьте права на возможность перезаписи, данные файлу recordsList.fbd.csv, находящемуся в functional/guestbook/ .</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	
	private function rewriteSitemapFile(){
		$startMarker=ceil($this->strNum / self::LIMIT_OF_ITEMS_IN_PAGE);
		if (($this->itemsQuantity - 1) % self::LIMIT_OF_ITEMS_IN_PAGE!==0)
			$endMarker=ceil($this->itemsQuantity / self::LIMIT_OF_ITEMS_IN_PAGE);
		else {
			$endMarker=ceil($this->itemsQuantity / self::LIMIT_OF_ITEMS_IN_PAGE)-1;
			$deletePage=ceil($this->itemsQuantity / self::LIMIT_OF_ITEMS_IN_PAGE);
		}
		$domainName_group="http://{$_SERVER['HTTP_HOST']}/guestbook.php?gId=";
		$lastmodDate=date("c");
		
		$sitemap=new DOMDocument();
		$sitemap->formatOutput=true;
		$sitemap->preserveWhiteSpace=true;
		$sitemap->load(self::SITEMAP_FILE);
		$nSpace=$sitemap->lookupNamespaceUri($sitemap->namespaceURI);
		$xPathDoc=new DOMXPath($sitemap);
		$xPathDoc->registerNamespace('x', $nSpace); 
		for ($a=$startMarker;$a<=$endMarker;$a++){
			$checkTag=$xPathDoc->query("/x:urlset/x:url[x:loc='".$domainName_group.$a."']/x:lastmod");
			if ($checkTag->length!==0){
				$checkTag->item(0)->nodeValue=$lastmodDate;
			}
		}
		if (isset($deletePage)===true){
			$checkTag=$xPathDoc->query("/x:urlset/x:url[x:loc='".$domainName_group.$deletePage."']");
			if ($checkTag->item(0)!=NULL) {
				$root=$xPathDoc->query("/x:urlset");
				$root->item(0)->removeChild($checkTag->item(0));
			}
		}
		if (@$sitemap->save(self::SITEMAP_FILE)===false){
			echo "<span class='error_class'>Произошла ошибка при перезаписи файла sitemap.xml.</span>\r\n".self::NAVIGATE_BUTTONS;
		}
	}
}
$temprObject=new deletePage();
$temprObject->deleteStartEngine();
?>