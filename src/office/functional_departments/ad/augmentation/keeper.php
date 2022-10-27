<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
/*[а-яА-Яa-zA-Z0-9\,\!\+\-\_\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/] - полный запрет символов (удалять для разрешения)*/
class App_ad_keeper extends ErrorsManager {
	const SIMPLE_TEXT_PATTERN="/[\&\^\~\`\@\#\'\"\<\>]/u";
	const SIMPLE_ALIAS_PATTERN="/[а-яА-Я\,\!\+\~\`\@\#\$\^\*\(\)\[\]\{\}\;\'\"\<\>]/u";
	const NAVIGATION_FILE="../functional/ad/bannersList.fbd.csv";
	const CONTENT_SAVE_PATH="../functional/ad/content/";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к редактированию баннера</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=ad&branch=bannersList.php'>Вернуться к списку баннеров</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	const LIMITER=20;
	const THROUGH_MARKER="through";
	
	private $dataCheckSettings;
	public function __construct() {
		$this->dataCheckSettings=array(
			'banner_name'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>100,
				'minLenght'=>3,
				'fieldName'=>'Название баннера'
			),
			'banner_code'=>array(
				'fieldName'=>'Исходный код баннера',
				'minLenght'=>3
			),
			'title_hint'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>100,
				'fieldName'=>'Подсказка при наведении на баннер'
			),
			'hint'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>150,
				'fieldName'=>'Текстовое содержание баннера'
			),
			'image_addr'=>array(
				'pattern'=>self::SIMPLE_ALIAS_PATTERN,
				'maxLenght'=>200,
				'fieldName'=>'Адрес изображения для баннера'
			),
			'target_page'=>array(
				'pattern'=>self::SIMPLE_ALIAS_PATTERN,
				'maxLenght'=>200,
				'fieldName'=>'Целевой адрес'
			)
		);
	}
	
	public function startKeeperEngine(){
		$postDatas=array();
		if (isset($_POST['banner_id'][0])===false || isset($_POST['banner_type'][0])===false || isset($_POST['page_to_view'][0])===false || isset($_POST['submit_redaction'][0])===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if ($_POST['banner_type'][0]!="code")
			unset($this->dataCheckSettings['banner_code']);
		else {
			unset($this->dataCheckSettings['image_addr']);
			unset($this->dataCheckSettings['title_hint']);
			unset($this->dataCheckSettings['hint']);
			unset($this->dataCheckSettings['target_page']);
			if (isset($_POST['banner_code'][0])===true && mb_strlen(trim($_POST['banner_code'][0]))<1){
				echo "<span class='error_class'>Вы не заполнили поле <q>Исходный код баннера</q></span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
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
		if ($_POST["banner_id"][0]=="new")
			$listingFile=self::returnListing();
		else
			$listingFile=self::returnListing($_POST["banner_id"][0]);
		$bannerName=$postDatas['banner_name'];
		unset($postDatas['banner_name']);
		$bannerContent=self::tuneBanner($postDatas);
		$pagePlacement=self::tunePlacementPages($_POST['page_to_view']);
		self::reloadListing($listingFile,$bannerName,$bannerContent,$pagePlacement);
		if ($_POST['banner_type'][0]=="code")
			self::reloadCodeFile($listingFile['newId'],$postDatas['banner_code']);
		echo "<span class='success_class'>Баннер сохранен.</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
	
	private function returnListing($id="new"){
		$returnArray=array();
		if (file_exists(self::NAVIGATION_FILE)===false)
			return array('newId'=>0,'stringNum'=>0,'fileArray'=>array());
		$navigation=file(self::NAVIGATION_FILE);
		if (count($navigation)>=self::LIMITER){
			echo "<span class='error_class'>Достигнуто максимальное количество баннеров для сайта (". self::LIMITER .").</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}

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
	
	private function tuneBanner($postDatas){
		if (array_key_exists("banner_code",$postDatas)===true)
			return "codeIns";
		else
			return implode(",,",$postDatas);
	}
	
	private function tunePlacementPages($pages){
		if (array_search(self::THROUGH_MARKER,$pages,TRUE)===true)
			return self::THROUGH_MARKER;
		else
			return implode(",,",$pages);
	}
	
	private function reloadCodeFile($fileId,$fileCode) {
		$entityConvert=new maskHTMLEntity(false);
		$fileCode=$entityConvert->maskEngine($fileCode);
		if (@file_put_contents(self::CONTENT_SAVE_PATH . $fileId .".fbd.tpl",$fileCode)===false){
			echo "<span class='error_class'>Произошёл сбой при записи кода баннера! Проверьте права данные файлам в папке functional/ad/content на возможность их перезаписи.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	private function reloadListing($fileArray,$name,$content,$placement){
		$stringReplace="{$fileArray['newId']};;{$name};;{$content};;{$placement}\r\n";
		$fileArray['fileArray'][$fileArray['stringNum']]=$stringReplace;
		
		$string=implode("",$fileArray['fileArray']);
		
		if (@file_put_contents(self::NAVIGATION_FILE,$string)===false){
			echo "<span class='error_class'>Произошёл сбой при записи файла списка баннеров! Проверьте права на возможность перезаписи, данные файлу bannersList.fbd.csv, находящемуся по следующему пути: functional/ad .</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
}
?>