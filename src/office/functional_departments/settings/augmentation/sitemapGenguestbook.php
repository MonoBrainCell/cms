<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_settings_sitemapGenguestbook {
	const NAVIGATION_FILE="../functional/guestbook/recordsList.fbd.csv";
	const LIMIT_OF_ITEMS_IN_PAGE=10;

	public function __construct(&$sitemap,&$urlset){
		if (file_exists(self::NAVIGATION_FILE)===false)
			return false;
		$domainName_group="http://{$_SERVER['HTTP_HOST']}/guestbook.php?gId=";
		$lastmodDate=date("c");
		$navigation=file(self::NAVIGATION_FILE);
		$elemsInFile=count($navigation);
		$pagesCount=ceil($elemsInFile / self::LIMIT_OF_ITEMS_IN_PAGE);
		for ($a=1;$a<=$pagesCount;$a++){
			$urlTag=$sitemap->createElement('url');
			$urlset->appendChild($urlTag);
				
			$locTag=$sitemap->createElement('loc',$domainName_group.$a);
			$urlTag->appendChild($locTag);
				
			$lmTag=$sitemap->createElement('lastmod',$lastmodDate);
			$urlTag->appendChild($lmTag);
		}
	}
}
?>