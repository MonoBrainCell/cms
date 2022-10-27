<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_guestbook_developer extends ErrorsManager {
	const NAVIGATION_FILE="../functional/guestbook/recordsList.fbd.csv";
	const CONTENT_TPL_PATH="functional_departments/guestbook/dummyes/redactRecord.fbd.tpl";
	private $htmlContent;
	
	public function __construct(){
		$GETInf=new manipulateGETInf();
		$recId=$GETInf->searchGETElem("recId");
		if (file_exists(self::NAVIGATION_FILE)===true)
			$navigation=file(self::NAVIGATION_FILE);
		else 
			$navigation=false;
		if (file_exists(self::CONTENT_TPL_PATH)===true)
			$mainTemplate=file_get_contents(self::CONTENT_TPL_PATH);
		else
			$mainTemplate=false;
		if ($navigation===false){
			$this->htmlContent="<div id='main_content'><h1>Файл recordsList.fbd.csv, расположенный в functional/guestbook повреждён или отстутствует.</h1></div>";
			return false;
		}
		else if (empty($navigation)===true) {
			$this->htmlContent="<div id='main_content'><h1>Ещё не оставлено ни одной записи</h1></div>";
			return false;
		}
		if ($mainTemplate===false) {
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		
		if ($recId===false){
			$this->htmlContent="<div id='main_content'><h1>Не были переданы все необходимые параметры для отображения записи гостевой книги. Попробуйте повторно зайти в функционал гостевой книги из стартовой страницы административной части</h1></div>";
			return false;
		}
		if ($recId!==false){
			for ($a=0,$b=count($navigation);$a<$b;$a++){
				$elem=explode(";;",rtrim($navigation[$a]));
				if ($elem[0]==$recId){
					$guestName=$elem[1];
					$adddedDate=$elem[2];
					$email=$elem[3];
					$message=str_replace("<br>","\r\n",$elem[4]);
					$findMarker=true;
					break;
				}
			}
			if ($findMarker===false){
				$this->htmlContent="<div id='main_content'><h1>В базе новостей (recordsList.fbd.csv, расположенный в functional/guestbook) не найден указанный идентификатор записи:{$recId}</h1></div>";
				return false;
			}
			$hiddenId=$recId;
		}
		$this->htmlContent=str_replace("{HIDDEN_ID}",$hiddenId,$mainTemplate);
		
		$this->htmlContent=str_replace("{GUEST_NAME}",$guestName,$this->htmlContent);
		
		$this->htmlContent=str_replace("{CONTENT}",$message,$this->htmlContent);
		
		$this->htmlContent=str_replace("{ADDED_DATE}",$adddedDate,$this->htmlContent);
		
		$this->htmlContent=str_replace("{GUEST_EMAIL}",$email,$this->htmlContent);
	}
	
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>