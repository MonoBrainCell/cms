<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_ad_arrangeBanners extends ErrorsManager{
	const NAVIGATION_FILE="../functional/ad/bannersList.fbd.csv";
	const NAV_ELEM_TPL_PATH="functional_departments/ad/dummyes/arrangeBanners_elem.fbd.tpl";
	const NAV_CONTEINER_TPL_PATH="functional_departments/ad/dummyes/arrangeBanners_conteiner.fbd.tpl";
	
	private $htmlContent;
	
	public function __construct(){
		if (file_exists(self::NAVIGATION_FILE)===false){
			$this->htmlContent="<div id='main_content'><h1>Файл ". self::NAVIGATION_FILE .", повреждён или отстутствует.</h1></div>";
			return false;
		}
		if (file_exists(self::NAV_ELEM_TPL_PATH)===false || file_exists(self::NAV_CONTEINER_TPL_PATH)===false) {
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		$innerContent=self::genNavigationElems();
		$mainTpl=file_get_contents(self::NAV_CONTEINER_TPL_PATH);
		$this->htmlContent=str_replace("{VARIABLE_PART}",$innerContent,$mainTpl);
	}
	
	private function genNavigationElems(){
		$elemTemplate=file_get_contents(self::NAV_ELEM_TPL_PATH);
		$navigation=file(self::NAVIGATION_FILE);
		$string="";
		for ($a=0,$b=count($navigation);$a<$b;$a++){
			$structureElems=explode(";;",rtrim($navigation[$a]));
			$string.=str_replace("{BAN_NAME}",$structureElems[1],$elemTemplate);
			$string=str_replace("{BAN_NUMBER}",$structureElems[0],$string);
			if ($structureElems[3]!="through")
				$string=str_replace("{BAN_TYPE}","Локальный баннер",$string);
			else
				$string=str_replace("{BAN_TYPE}","Глобальный баннер",$string);
		}
		return $string;
	}
	
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>