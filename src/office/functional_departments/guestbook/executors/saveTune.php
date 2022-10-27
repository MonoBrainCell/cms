<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class navElemsTextRedact{
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к редактированию</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=guestbook&trunk=recordsList.php'>Вернуться к списку записей гостевой книги</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	const NEWS_BLOCK_TPL_FILE="../functional/guestbook/dummyes/guestbookBlockTpls.fbd.tpl";
	const NEWS_ITEM_TPL_FILE="../functional/guestbook/dummyes/guestbookRecordTpls.fbd.tpl";
	const LEVEL1_SEPARATOR="|--|";
	const LEVEL2_SEPARATOR="|-|";
	public function __construct(){
		if(
		isset($_POST['submit_changes'][0])===false || isset($_POST['type_of_tune'][0])===false
		||
		($_POST['type_of_tune'][0]!="block" && $_POST['type_of_tune'][0]!="item")
		){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$checkArray=self::initCheckArray($_POST['type_of_tune'][0]);
		$totalArray=array();
		$entityConvert=new maskHTMLEntity(false);
		for ($a=0,$b=count($checkArray['elements']);$a<$b;$a++){
			if (is_array($checkArray['elements'][$a])===false){
				if (isset($_POST[$checkArray['elements'][$a]][0])===true)
					$totalArray[]=$_POST[$checkArray['elements'][$a]][0];
				else
					$totalArray[]=" ";
			}
			else {
				$interimArray=array();
				for ($c=0;$c<$checkArray['elements'][$a][0];$c++){
					$elem=$checkArray['elements'][$a][1];
					if (is_array($elem)===false){
						if (isset($_POST[$elem][$c])===true)
							$interimArray[]=$entityConvert->maskEngine($_POST[$elem][$c]);
						else
							$interimArray[]=" ";
					}
					else {
						$twiceIterim=array();
						for ($d=0,$e=count($elem);$d<$e;$d++){
							if (isset($_POST[$elem[$d]][$c])===true)
								$twiceIterim[]=$entityConvert->maskEngine($_POST[$elem[$d]][$c]);
							else
								$twiceIterim[]=" ";
						}
						$iterimArray[]=implode(self::LEVEL2_SEPARATOR,$twiceIterim);
					}
				}
				$totalArray[]=implode(self::LEVEL1_SEPARATOR,$iterimArray);
			}
		}
		$string=implode(self::LEVEL1_SEPARATOR,$totalArray);
		if (@file_put_contents($checkArray["designedFile"],$string)===false){
			echo "<span class='error_class'>Ошибка перезаписи файла ". $checkArray["designedFile"] .". Проверьте права доступа к этому файлу.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		echo "<span class='success_class'>Изменение произведено успешно.</span>\r\n". self::NAVIGATE_BUTTONS;
		exit;
	}
	private function initCheckArray($type){
		if ($type=="block")
			$returnArray=array(
					"designedFile"=>self::NEWS_BLOCK_TPL_FILE,
					"elements"=>array("service_headers","records_block_wrapper","numeral_navigation_wrapper","numeral_navigation_anchor","numeral_navigation_deadlock","add_record_form")
				);
		else if ($type=="item")
			$returnArray=array(
					"designedFile"=>self::NEWS_ITEM_TPL_FILE,
					"elements"=>array("service_headers","records_item_assembly")
				);
		return $returnArray;
	}
}
$varObj=new navElemsTextRedact();
?>
