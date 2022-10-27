<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_news_developer extends ErrorsManager {
	const NAVIGATION_FILE="../functional/news/newsList.fbd.csv";
	const CONTENT_TPL_PATH="functional_departments/news/dummyes/redactNews.fbd.tpl";
	const CONTENT_STORAGE_PATH="../functional/news/content/";
	private $htmlContent;
	
	public function __construct(){
		$GETInf=new manipulateGETInf();
		$newsId=$GETInf->searchGETElem("newsId");
		if (file_exists(self::NAVIGATION_FILE)===true)
			$navigation=file(self::NAVIGATION_FILE);
		else 
			$navigation=false;
		if (file_exists(self::CONTENT_TPL_PATH)===true)
			$mainTemplate=file_get_contents(self::CONTENT_TPL_PATH);
		else
			$mainTemplate=false;
		if ($navigation===false){
			$this->htmlContent="<div id='main_content'><h1>Файл newsList.fbd.csv, расположенный в functional/news повреждён или отстутствует.</h1></div>";
			return false;
		}
		if ($mainTemplate===false) {
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		
		if ($newsId!==false && $newsId!="new"){
			for ($a=0,$b=count($navigation);$a<$b;$a++){
				$elem=explode("|--|",$navigation[$a]);
				if ($elem[0]==$newsId){
					$newsHeader=$elem[2];
					$findMarker=true;
					if ($elem[3]=="1"){
						if (file_exists(self::CONTENT_STORAGE_PATH .$newsId .".fbd.tpl")===false){
							$this->htmlContent="<div id='main_content'><h1>Не найден файл с контентом новости: ". self::CONTENT_STORAGE_PATH .$newsId .".fbd.tpl</h1></div>";
							return false;
						}
						$newsContent=file_get_contents(self::CONTENT_STORAGE_PATH .$newsId .".fbd.tpl");
					}
					else
						$newsContent=$elem[4];
					break;
				}
			}
			if ($findMarker===false){
				$this->htmlContent="<div id='main_content'><h1>В базе новостей (newsList.fbd.csv, расположенный в functional/news) не найден указанный идентификатор новости:{$newsId}</h1></div>";
				return false;
			}
			$hiddenId=$newsId;
		}
		else if ($newsId=="new"){
			$newsContent="";$newsHeader="";
			$hiddenId="new";
		}
		$this->htmlContent=str_replace("{HIDDEN_ID}",$hiddenId,$mainTemplate);
		
		$this->htmlContent=str_replace("{NEWS_HEADER}",$newsHeader,$this->htmlContent);
		
		$this->htmlContent=str_replace("{CONTENT}",$newsContent,$this->htmlContent);
	}
	
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>