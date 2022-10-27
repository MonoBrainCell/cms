<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_settings_sitemapGennews {
	const NAVIGATION_FILE="../functional/news/newsList.fbd.csv";
	const LIMIT_OF_ITEMS_IN_PAGE=20;

	public function __construct(&$sitemap,&$urlset){
		if (file_exists(self::NAVIGATION_FILE)===false)
			return false;
		$domainName_item="http://{$_SERVER['HTTP_HOST']}/news.php?article=";
		$domainName_group="http://{$_SERVER['HTTP_HOST']}/news.php?gId=";
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
		for ($a=0;$a<$elemsInFile;$a++){
			$elem=explode(";;",$navigation[$a]);
			$urlTag=$sitemap->createElement('url');
			$urlset->appendChild($urlTag);
				
			$locTag=$sitemap->createElement('loc',$domainName_item.$elem[0]);
			$urlTag->appendChild($locTag);
				
			$lmTag=$sitemap->createElement('lastmod',$lastmodDate);
			$urlTag->appendChild($lmTag);
		}
	}
}
?>