<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_navigation_developerOfTune extends ErrorsManager{
	const MAIN_NAVIGATION_TPL_FILE="../functional/navigation/dummyes/mainNavigationTpls.fbd.tpl";
	const ADV_NAVIGATION_TPL_FILE="../functional/navigation/dummyes/advancedNavigationTpls.fbd.tpl";
	const RELELEM_NAVIGATION_TPL_FILE="../functional/navigation/dummyes/relElemNavigationTpls.fbd.tpl";
	const TUNE_ELEM_TPL_PATH="functional_departments/navigation/dummyes/tuneNavigation_elem.fbd.tpl";
	const TUNE_CONTEINER_TPL_PATH="functional_departments/navigation/dummyes/tuneNavigation_conteiner.fbd.tpl";
	private $htmlContent;
	
	public function __construct(){
		$GETInf=new manipulateGETInf();
		$tOT=$GETInf->searchGETElem("tOT");// "main","advanced", "relatedElems"
		if ($tOT===false || ($tOT!="main" && $tOT!="advanced" && $tOT!="relatedElems")) {
			$this->htmlContent="<div id='main_content'><h1>Не был передан или передан с ошибкой обязательный идентифицирующий параметр. Скорее всего Вы попытались перейти к странице не через навигацию по административной части сайта.</h1></div>";
			return false;
		}
		if (file_exists(self::TUNE_ELEM_TPL_PATH)===false || file_exists(self::TUNE_CONTEINER_TPL_PATH)===false) {
			$this->htmlContent="<div id='main_content'><h1>Не найден html-шаблон для отображения контента </h1></div>";
			return false;
		}
		if ($tOT=="main"){
			$formStructure=array(
				"templatePath"=>self::MAIN_NAVIGATION_TPL_FILE,
				array(
					"type"=>"singl",
					"fieldName"=>"navigation_wrapper",
					"fieldDesc"=>"Оболочка основной навигации",
					"idInTpl"=>0
				),
				array(
					"type"=>"group",
					"groupName"=>"Шаблоны навигации 1-го уровня",
					"elements"=>array(
						array(
							"fieldName"=>"direction_wrapper",
							"fieldDesc"=>"Шаблон навигационной директории 1-го уровня",
							"idInTpl"=>array(1,0)
						),
						array(
							"fieldName"=>"anchor",
							"fieldDesc"=>"Шаблон ссылки 1-го уровня",
							"idInTpl"=>array(1,1)
						)
					)
				),
				array(
					"type"=>"group",
					"groupName"=>"Шаблоны навигации 2-го уровня",
					"elements"=>array(
						array(
							"fieldName"=>"direction_wrapper",
							"fieldDesc"=>"Шаблон навигационной директории 2-го уровня",
							"idInTpl"=>array(2,0)
						),
						array(
							"fieldName"=>"anchor",
							"fieldDesc"=>"Шаблон ссылки 2-го уровня",
							"idInTpl"=>array(2,1)
						)
					)
				),
				array(
					"type"=>"group",
					"groupName"=>"Шаблоны навигации 3-го уровня",
					"elements"=>array(
						array(
							"fieldName"=>"direction_wrapper",
							"fieldDesc"=>"Шаблон навигационной директории 3-го уровня",
							"idInTpl"=>array(3,0)
						),
						array(
							"fieldName"=>"anchor",
							"fieldDesc"=>"Шаблон ссылки 3-го уровня",
							"idInTpl"=>array(3,1)
						)
					)
				)
			);
			
		}
		else if ($tOT=="advanced"){
			$formStructure=array(
				"templatePath"=>self::ADV_NAVIGATION_TPL_FILE,
				array(
					"type"=>"singl",
					"fieldName"=>"navigation_wrapper",
					"fieldDesc"=>"Оболочка дополнительной навигации",
					"idInTpl"=>0
				),
				array(
					"type"=>"singl",
					"fieldName"=>"anchor",
					"fieldDesc"=>"Шаблон ссылки дополнительной навигации",
					"idInTpl"=>1
				)
			);
		}
		else {
			$formStructure=array(
				"templatePath"=>self::RELELEM_NAVIGATION_TPL_FILE,
				array(
					"type"=>"singl",
					"fieldName"=>"path_wrapper",
					"fieldDesc"=>"Оболочка пути от главной страницы",
					"idInTpl"=>0
				),
				array(
					"type"=>"singl",
					"fieldName"=>"path_anchor",
					"fieldDesc"=>"Шаблон ссылки пути от главной страницы",
					"idInTpl"=>1
				),
				array(
					"type"=>"singl",
					"fieldName"=>"close_relatives_wrapper",
					"fieldDesc"=>"Оболочка навигации по текущей директории",
					"idInTpl"=>2
				),
				array(
					"type"=>"singl",
					"fieldName"=>"close_relatives_anchor",
					"fieldDesc"=>"Шаблон ссылки навигации по текущей директории",
					"idInTpl"=>3
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