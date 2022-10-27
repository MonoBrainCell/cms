<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_guestbook_importToSitemap extends ErrorsManager {
	const NAVIGATION_FILE="../functional/guestbook/recordsList.fbd.csv";
	const PATH_POSTFIX="?gId=";
	const GENERIC_FILE="guestbook.php";
	const LIMIT_OF_ITEMS_IN_PAGE=10;
	private $map;
	public function __construct($sitemap){
		if (file_exists(self::NAVIGATION_FILE)===false)
			return false;
		$fileArray=file(self::NAVIGATION_FILE);
		if ($fileArray===false
		||
		empty($fileArray)===true
		||
		($fileArray!==false && mb_strlen(trim($fileArray[0]),"utf-8")<3))
			return false;
		$recordsQuantity=count($fileArray);
		unset($fileArray);
		$domainName="http://{$_SERVER['HTTP_HOST']}/";
		$lastmodDate=date("c");
		$root=$sitemap->getElementsByTagName("urlset");
		$pages=ceil( $recordsQuantity / self::LIMIT_OF_ITEMS_IN_PAGE);
		for ($a=1;$a<=$pages;$a++){
			$loc=$sitemap->createElement("loc",$domainName.self::GENERIC_FILE .self::PATH_POSTFIX .$a);
			$lastmod=$sitemap->createElement("lastmod",$lastmodDate);
			$url=$sitemap->createElement("url");
			$url->appendChild($loc); $url->appendChild($lastmod); $root->item(0)->appendChild($url);
		}
		$this->map=$sitemap;
	}
	
	public function getMap() {return $this->map;}
}
?>