<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
/*[а-яА-Яa-zA-Z0-9\,\!\+\-\_\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/] - полный запрет символов (удалять для разрешения)*/
class App_widgets_keeper extends ErrorsManager {
	const SIMPLE_TEXT_PATTERN="/[\&\^\~\`\@\#\'\"\<\>]/u";
	const NAVIGATION_FILE="../functional/widgets/widgetsList.fbd.csv";
	const CONTENT_SAVE_PATH="../functional/widgets/content/wsContent/";
	const HEADERS_SAVE_PATH="../functional/widgets/content/wsHeaders/";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к редактированию виджета</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=widgets&trunk=widgetsList.php'>Вернуться к списку виджетов</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	
	private $dataCheckSettings;
	public function __construct() {
		$this->dataCheckSettings=array(
			'widget_name'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>75,
				'minLenght'=>3,
				'fieldName'=>'Название виджета'
			)
		);
	}
	
	public function startKeeperEngine(){
		$postDatas=array();
		if (isset($_POST['widget_id'][0])===false || isset($_POST['submit_redaction'][0])===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		foreach ($this->dataCheckSettings as $field=>$properties){
			if (isset($_POST[$field][0])===false){
				echo "<span class='error_class'>Не были переданы данные из поля <q>{$properties['fieldName']}</q></span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			if (isset($properties['pattern'])===true){
				if (preg_match($properties['pattern'],$_POST[$field][0])===1 && isset($properties['specialEdition']['pattern'])===false && mb_strlen(trim($_POST[$field][0]))>0){
					echo "<span class='error_class'>При заполнении поля <q>{$properties['fieldName']}</q> были использованы запрещённые символы</span>\r\n".self::NAVIGATE_BUTTONS;
					exit;
				}
			}
			if (isset($properties['minLenght'])===true){
				if (mb_strlen(trim($_POST[$field][0]))<$properties['minLenght'] && isset($properties['specialEdition']['minLenght'])===false){
					echo "<span class='error_class'>Вы не заполнили поле <q>{$properties['fieldName']}</q></span>\r\n".self::NAVIGATE_BUTTONS;
					exit;
				}
			}
			if (isset($properties['maxLenght'])===true){
				if (mb_strlen(trim($_POST[$field][0]))>$properties['maxLenght'] && isset($properties['specialEdition']['maxLenght'])===false){
					echo "<span class='error_class'>При заполнении поля <q>{$properties['fieldName']}</q> Вы превысили лимит количества символов</span>\r\n".self::NAVIGATE_BUTTONS;
					exit;
				}
			}
			$postDatas[$field]=$_POST[$field][0];
		}
		if ($_POST["widget_id"][0]=="new")
			$listingFile=self::returnListing();
		else
			$listingFile=self::returnListing($_POST["widget_id"][0]);
		$widgetName=$postDatas['widget_name'];
		unset($postDatas['widget_name']);
		if (mb_strlen(trim($_POST['body_code'][0]))<1 && mb_strlen(trim($_POST['headers_code'][0]))<1){
			echo "<span class='error_class'>Должно быть заполнено хотя бы одно из следующих полей: <q>html-код рабочих заголовков</q> или <q>html-код виджета</q></span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		self::reloadListing($listingFile,$widgetName);
		if (mb_strlen(trim($_POST['body_code'][0]))>0)
			self::reloadCodeFile($listingFile['newId'],$_POST['body_code'][0],self::CONTENT_SAVE_PATH);
		else {
			$tplFilePath=self::CONTENT_SAVE_PATH . $listingFile['newId'] .".fbd.tpl";
			if (file_exists($tplFilePath)===true)
				unlink($tplFilePath);
		}
		if (mb_strlen(trim($_POST['headers_code'][0]))>0)	
			self::reloadCodeFile($listingFile['newId'],$_POST['headers_code'][0],self::HEADERS_SAVE_PATH);
		else {
			$tplFilePath=self::HEADERS_SAVE_PATH . $listingFile['newId'] .".fbd.tpl";
			if (file_exists($tplFilePath)===true)
				unlink($tplFilePath);
		}
			
		echo "<span class='success_class'>Виджет сохранен.</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
	
	private function returnListing($id="new"){
		$returnArray=array();
		if (file_exists(self::NAVIGATION_FILE)===false)
			return array('newId'=>0,'stringNum'=>0,'fileArray'=>array());
		$navigation=file(self::NAVIGATION_FILE);
		
		return self::searchParams($id,$navigation);
	}
	
	private function searchParams($id,$fileArray){
	$returnDatas=array(); $returnDatas['fileArray']=array(); $idArray=array();$stringsArray=array(); $maxIndex=0;
		for ($a=0,$b=count($fileArray);$a<$b;$a++){
			$elem=explode(";;",$fileArray[$a]);
			if ($id=="new"){
				$idArray[]=$elem[0]*1;
				$stringsArray[$elem[0]]=$a;
				if ($maxIndex<$elem[0])
					$maxIndex=$elem[0];
			}
			else {
				if ($elem[0]==$id){
					$returnDatas['newId']=$elem[0]*1;
					$returnDatas['stringNum']=$a;
				}
			}
			$returnDatas['fileArray'][]=$fileArray[$a];
		}
		if ($maxIndex!==0){
			for ($a=0;$a<$maxIndex;$a++){
				if (array_search($a,$idArray,TRUE)===false){
					$returnDatas['newId']=$a;
					$successMarker=true;
					break;
				}
			}
		}
		if (isset($successMarker)===false && isset($returnDatas['stringNum'])===false){
			if ($maxIndex!==0 || empty($idArray)===false)
				$returnDatas['newId']=$maxIndex+1;
			else
				$returnDatas['newId']=$maxIndex;
			$returnDatas['stringNum']=$b;
		}
		else if (isset($returnDatas['stringNum'])===false){
			if ($id=="new")
				$returnDatas['stringNum']=$b;
			else
				$returnDatas['stringNum']=$stringsArray[$returnDatas['newId']];
		}
		
		return $returnDatas;
	}
	
	private function reloadCodeFile($fileId,$fileCode,$path) {
		$entityConvert=new maskHTMLEntity(false);
		$fileCode=$entityConvert->maskEngine($fileCode);
		if (@file_put_contents($path . $fileId .".fbd.tpl",$fileCode)===false){
			echo "<span class='error_class'>Произошёл сбой при записи кода виджета! Проверьте права данные файлам в папке functional/widgets/content на возможность их перезаписи.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	private function reloadListing($fileArray,$name){
		$stringReplace="{$fileArray['newId']};;{$name}\r\n";
		$fileArray['fileArray'][$fileArray['stringNum']]=$stringReplace;
		
		$string=implode("",$fileArray['fileArray']);
		
		if (@file_put_contents(self::NAVIGATION_FILE,$string)===false){
			echo "<span class='error_class'>Произошёл сбой при записи файла списка виджетов! Проверьте права на возможность перезаписи, данные файлу widgetsList.fbd.csv, находящемуся по следующему пути: functional/widgets/ .</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
}
?>