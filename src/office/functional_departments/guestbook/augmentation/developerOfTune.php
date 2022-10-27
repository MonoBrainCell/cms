<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_guestbook_developerOfTune extends ErrorsManager{
	const NEWS_BLOCK_TPL_FILE="../functional/guestbook/dummyes/guestbookBlockTpls.fbd.tpl";
	const NEWS_ITEM_TPL_FILE="../functional/guestbook/dummyes/guestbookRecordTpls.fbd.tpl";
	const TUNE_ELEM_TPL_PATH="functional_departments/guestbook/dummyes/tuneGuestbook_elem.fbd.tpl";
	const TUNE_CONTEINER_TPL_PATH="functional_departments/guestbook/dummyes/tuneGuestbook_conteiner.fbd.tpl";
	private $htmlContent;
	
	public function __construct(){
		$GETInf=new manipulateGETInf();
		$tOT=$GETInf->searchGETElem("tOT");// "block","item"
		if ($tOT===false || ($tOT!="block" && $tOT!="item")) {
			$this->htmlContent="<div id='main_content'><h1>Не был передан или передан с ошибкой обязательный идентифицирующий параметр. Скорее всего Вы попытались перейти к странице не через навигацию по административной части сайта.</h1></div>";
			return false;
		}
		if (file_exists(self::TUNE_ELEM_TPL_PATH)===false || file_exists(self::TUNE_CONTEINER_TPL_PATH)===false) {
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		if ($tOT=="block"){
			$formStructure=array(
				"templatePath"=>self::NEWS_BLOCK_TPL_FILE,
				array(
					"type"=>"singl",
					"fieldName"=>"service_headers",
					"fieldDesc"=>"Рабочие заголовки для браузера",
					"idInTpl"=>0
				),
				array(
					"type"=>"singl",
					"fieldName"=>"records_block_wrapper",
					"fieldDesc"=>"Шаблон оболочки списка записей",
					"idInTpl"=>1
				),
				array(
					"type"=>"singl",
					"fieldName"=>"numeral_navigation_wrapper",
					"fieldDesc"=>"Шаблон оболочки навигации по записям гостевой книги",
					"idInTpl"=>2
				),
				array(
					"type"=>"singl",
					"fieldName"=>"numeral_navigation_anchor",
					"fieldDesc"=>"Шаблон ссылки навигации по записям гостевой книги",
					"idInTpl"=>3
				),
				array(
					"type"=>"singl",
					"fieldName"=>"numeral_navigation_deadlock",
					"fieldDesc"=>"Шаблон указателя текущей страницы гостевой книги",
					"idInTpl"=>4
				),
				array(
					"type"=>"singl",
					"fieldName"=>"add_record_form",
					"fieldDesc"=>"Шаблон формы добавления записи",
					"idInTpl"=>5
				)
			);
			
		}
		else if ($tOT=="item"){
			$formStructure=array(
				"templatePath"=>self::NEWS_ITEM_TPL_FILE,
				array(
					"type"=>"singl",
					"fieldName"=>"service_headers",
					"fieldDesc"=>"Рабочие заголовки для браузера",
					"idInTpl"=>0
				),
				array(
					"type"=>"singl",
					"fieldName"=>"records_item_assembly",
					"fieldDesc"=>"Шаблон отображения записи гостевой книги",
					"idInTpl"=>1
				)
			);
		}
		if (isset($formStructure['templatePath'])===false || (isset($formStructure['templatePath'])===true && file_exists($formStructure['templatePath'])===false)) {
			$this->htmlContent="<div id='main_content'><h1>Файл {$formStructure['templatePath']}, повреждён или отстутствует.</h1></div>";
			return false;
		}
		$mainTpl=file_get_contents(self::TUNE_CONTEINER_TPL_PATH);
		$content=str_replace("{NAVIGATION_ID}",$tOT,$mainTpl);

		$innerContent=self::genElemsOfTune($formStructure);
		$this->htmlContent=str_replace("{VARIABLE_PART}",$innerContent,$content);
	}
	
	private function genElemsOfTune($array){
		$tplsContent=file_get_contents($array['templatePath']);
		unset($array['templatePath']);
		$elemTpl=file_get_contents(self::TUNE_ELEM_TPL_PATH);
		$separators=array(array("|--|","/\|\-\-\|/"),array("|-|","/\|\-\|/"));
		$tplsArray=array();
		if (preg_match($separators[0][1],$tplsContent)==1)
			$tplsArray=explode($separators[0][0],$tplsContent);
		if (empty($tplsArray)===true){
			if (preg_match($separators[1][1],$tplsContent)==1)
				$tplsArray=explode($separators[1][0],$tplsContent);
		}
		else {
			for ($a=0,$b=count($tplsArray);$a<$b;$a++){
				if (preg_match($separators[1][1],$tplsArray[$a])==1)
					$tplsArray[$a]=explode($separators[1][0],$tplsArray[$a]);
			}
		}
		$totalString="";
		foreach($array as $options){
			if ($options['type']=="singl"){
				$totalString.=self::replaceKeywords(array("",$options['fieldName'],$options['fieldDesc'],$options['idInTpl']),$elemTpl,$tplsArray);
			}
			else if ($options['type']=="group"){
				$firstElem=array_shift($options['elements']);
				$totalString.=self::replaceKeywords(array("<h2>{$options["groupName"]}</h2>",$firstElem['fieldName'],$firstElem['fieldDesc'],$firstElem['idInTpl']),$elemTpl,$tplsArray);
				if (empty($options['elements'])===true)
					continue;
				for ($a=0,$b=count($options['elements']);$a<$b;$a++){
					$totalString.=self::replaceKeywords(array("",$options['elements'][$a]['fieldName'],$options['elements'][$a]['fieldDesc'],$options['elements'][$a]['idInTpl']),$elemTpl,$tplsArray);
				}
			}
		}
		return $totalString;
	}
	private function replaceKeywords($arrayElem,$template,$values){
		$entityConvert=new maskHTMLEntity(true);
		$totalString=str_replace("{GROUP_NAME}",$entityConvert->maskEngine($arrayElem[0]),$template);
		$totalString=str_replace("{FIELD_NAME}",$entityConvert->maskEngine($arrayElem[1]),$totalString);
		$totalString=str_replace("{FIELD_DESC}",$entityConvert->maskEngine($arrayElem[2]),$totalString);
		if (is_array($arrayElem[3])===false && isset($values[$arrayElem[3]])===true){
			$fieldValue=$entityConvert->maskEngine($values[$arrayElem[3]]);
		}
		else if (is_array($arrayElem[3])===true && isset($values[$arrayElem[3][0]])===true && isset($values[$arrayElem[3][1]])===true){
			$fieldValue=$entityConvert->maskEngine($values[$arrayElem[3][0]][$arrayElem[3][1]]);
		}
		else
			$fieldValue="";
		$totalString=str_replace("{FIELD_VALUE}",$fieldValue,$totalString);
		return $totalString;
	}
	
	public function getHtmlPiece() {
		return $this->htmlContent;
	}
}
?>