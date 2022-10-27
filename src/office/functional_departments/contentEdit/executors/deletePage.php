<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class deletePage extends ErrorsManager {
	const NAVIGATION_FILE="../navigation.fbd.xml";
	const SITEMAP_FILE="../sitemap.xml";
	const PAGE_DELETE_PATH="../content/";
	const NAVIGATE_BUTTONS="<a class='buttons_from_script' href='office/index.php?dep=contentEdit&trunk=pagesList.php'>Вернуться к списку страниц</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	private $fileId=0;
	private $pageAlias="";
	
	public function __construct() {
		if (isset($_POST['submitBut'])===false || isset($_POST['pageNum'])===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if ($_POST['pageNum']=="start"){
			echo "<span class='error_class'>Эту Страницу (<q>Стартовая</q>) удалять НЕЛЬЗЯ!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$this->fileId=$_POST['pageNum'];
	}
	public function deleteStartEngine(){
		if (file_exists(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Не найден файл для записи информации о навигации сайта. Скорее всего он был потерен из-за системного сбоя сервера. Обратитесь к Вашему Хостинг-Провайдеру для проведения backUp'а Вашего сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		self::rewriteNavigationFile();
		if (file_exists(self::PAGE_DELETE_PATH . $this->fileId .".php")===true){
			if (@unlink(self::PAGE_DELETE_PATH . $this->fileId .".php")===false){
				echo "<span class='error_class'>Произошла ошибка при удалении файла<strong>:". self::PAGE_DELETE_PATH . $this->fileId .".php</strong>. Пожалуйста проверьте факт его отсутствия через Ftp-доступ к Вашему сайту.</span>";
			}
		}
		if (file_exists(self::SITEMAP_FILE)===true){
			self::rewriteSitemapFile();
		}
		echo "<span class='success_class'>Удаление прошло успешно!</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
	private function rewriteNavigationFile(){
		$file=new DOMDocument();
		$file->formatOutput=true;
		$file->preserveWhiteSpace=false;
		$file->load(self::NAVIGATION_FILE);
		$xPathDoc=new DOMXPath($file);
		$queryXP=$xPathDoc->query("/navigation//page[@alias='".$this->fileId ."']");
		$parent=$xPathDoc->query("/navigation//page[@alias='".$this->fileId ."']/parent::node()[name()='main' or name()='advanced' or name()='page']");
		if ($queryXP->length===0)
			return false;
		if ($parent->length===0){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$checkChild=$xPathDoc->query("/navigation//page[@alias='".$this->fileId ."']/child::page");
		if ($checkChild->length===0)
			$parent->item(0)->removeChild($queryXP->item(0));
		else {
			$children=array();
			for ($a=0;$a<$checkChild->length;$a++){
				$children[]=$queryXP->item(0)->removeChild($checkChild->item($a));
			}
			$parent->item(0)->removeChild($queryXP->item(0));
			if (empty($children)===false){
				for ($a=0,$b=count($children);$a<$b;$a++){
					$parent->item(0)->insertBefore($children[$a]);
				}
			}
		}
		if (@$file->save(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Произошёл сбой при записи файла навигации сайта! Проверьте права на возможность перезаписи, данные файлу navigation.fbd.xml, находящемуся в корневой папке сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	
	private function rewriteSitemapFile(){
		$domainName="http://{$_SERVER['HTTP_HOST']}/";
		
		$sitemap=new DOMDocument();
		$sitemap->formatOutput=true;
		$sitemap->preserveWhiteSpace=true;
		$sitemap->load(self::SITEMAP_FILE);
		$nSpace=$sitemap->lookupNamespaceUri($sitemap->namespaceURI);
		$xPathDoc=new DOMXPath($sitemap);
		$xPathDoc->registerNamespace('x', $nSpace); 
		$checkTag=$xPathDoc->query("/x:urlset/x:url[x:loc='".$domainName."page/".$this->fileId ."']");
		if ($checkTag->length===0)
			return false;
		$parent=$xPathDoc->query("/x:urlset");
		$parent->item(0)->removeChild($checkTag->item(0));
		if (@$sitemap->save(self::SITEMAP_FILE)===false){
			echo "<span class='error_class'>Произошла ошибка при перезаписи файла sitemap.xml.</span>\r\n".self::NAVIGATE_BUTTONS;
		}
	}
}
$temprObject=new deletePage();
$temprObject->deleteStartEngine();
?>