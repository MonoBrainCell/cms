<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
/*[а-яА-Яa-zA-Z0-9\,\!\+\-\_\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/] - полный запрет символов (удалять для разрешения)*/
class App_imagesGallery_keeper extends ErrorsManager {
	const SIMPLE_TEXT_PATTERN="/[\&\^\~\`\@\#\'\"\<\>]/u";
	const NAVIGATION_FILE="../functional/imagesGallery/gallerysList.fbd.csv";
	const BIG_LIST_SAVE_FILE="../functional/imagesGallery/content/";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к редактированию галереи</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=imagesGallery&trunk=gallerysList.php'>Вернуться к списку галерей изображений</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	const BIG_SIZE_LIMIT=3072;
	const SIZE_ELEM_MAIN_FACTOR=6;
	
	private $dataCheckSettings;
	
	public function __construct() {
		$this->dataCheckSettings=array(
		'gallery_name'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>50,
				'minLenght'=>2,
				'fieldName'=>'Название галереи'
			),
		'gallery_type'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'minLenght'=>2,
				'fieldName'=>'тип галереи'
			)
		);
	}
	public function startKeeperEngine() {
		$datasToRewrite=array();
		if (isset($_POST['gallery_id'][0])===false || isset($_POST['group_name'])===false || isset($_POST['preview_images'])===false || isset($_POST['original_images'])===false){
			echo "<span class='error_class'>Были переданы не все данные для адекватной работы данного функционала. Обновите страницы и повторите попытку редактирования.".self::NAVIGATE_BUTTONS;
			exit;
		}
		foreach ($this->dataCheckSettings as $field=>$properties){
			if (isset($_POST[$field][0])===false){
				echo "<span class='error_class'>Не были переданы данные из поля <q>{$properties['fieldName']}</q></span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			if (isset($properties['pattern'])===true && preg_match($properties['pattern'],$_POST[$field][0])===1 && mb_strlen(trim($_POST[$field][0]))>0){
				echo "<span class='error_class'>При заполнении поля <q>{$properties['fieldName']}</q> были использованы запрещённые символы</span>\r\n".self::NAVIGATE_BUTTONS;
				exit;

			}
			if (isset($properties['minLenght'])===true && mb_strlen(trim($_POST[$field][0]))<$properties['minLenght']){
				echo "<span class='error_class'>Вы не заполнили поле <q>{$properties['fieldName']}</q></span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			if (isset($properties['maxLenght'])===true && mb_strlen(trim($_POST[$field][0]))>$properties['maxLenght']){
				echo "<span class='error_class'>При заполнении поля <q>{$properties['fieldName']}</q> Вы превысили лимит количества символов</span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			$datasToRewrite[$field]=$_POST[$field][0];
		}
		
		$varStr=self::repackAndCheckDatas($_POST['preview_images'],$_POST['original_images'],$_POST['group_name']);
		if ($varStr['imagesCount']>self::BIG_SIZE_LIMIT){
			$datasToRewrite["imagesString"]="codeIns";
			$rewriteFileMarker=true;
		}
		else
			$datasToRewrite["imagesString"]=$varStr['totalString'];
		$rewriteDatas=self::searchParams($_POST['gallery_id'][0]);
		self::reloadListing($rewriteDatas,$datasToRewrite);
		if (isset($rewriteFileMarker)===true)
			self::reloadCodeFile($rewriteDatas['newId'],$varStr['totalString']);
		echo "<span class='success_class'>Галерея сохранена. Чтобы увидеть изменения обновите данную страницу в браузере.</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}

	private function searchParams($id){
		if (file_exists(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Не найден файл для записи информации о галерее. Скорее всего он был потерен из-за системного сбоя сервера. Обратитесь к Вашему Хостинг-Провайдеру для проведения backUp'а Вашего сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$fileArray=file(self::NAVIGATION_FILE);	
		$returnDatas=array(); $returnDatas['fileArray']=array(); $idArray=array();$stringsArray=array(); $maxIndex=0;
		if (empty($fileArray)===true || count($fileArray)<1) {
			$returnDatas['newId']=0;
			$returnDatas['stringNum']=0;
			$returnDatas['fileArray']=array();
		}
		else {
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
			
		
		}	
		return $returnDatas;
	}
	
	private function reloadCodeFile($id,$string){
		if (@file_put_contents(self::BIG_LIST_SAVE_FILE . $id .".fbd.csv",$string)===false){
			echo "<span class='error_class'>Произошёл сбой при записи отдельного списка изображений галереи! Проверьте права данные файлам в папке functional/imagesGallery/content на возможность их перезаписи.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	
	private function repackAndCheckDatas($previews,$originals,$names){
		$previewsArr=explode("::",$previews);
		$originalsArr=explode("::",$originals);
		$namesArr=explode("::",$names);

		$previewsTotal=count($previewsArr);
		$originalsTotal=count($originalsArr);
		$namesTotal=count($namesArr);
		if ($previewsTotal!=$originalsTotal || $originalsTotal!=$namesTotal || $previewsTotal!=$namesTotal){
			echo "<span class='error_class'>Данные были переданы с ошибками. Обновите страницу в браузере и повторите попытку</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$imagesArr=array();
		$size=0;
		for ($a=0,$picturesTotal=0,$previewsTotal=0,$originalsTotal=0;$a<$namesTotal;$a++){
			if (preg_match(self::SIMPLE_TEXT_PATTERN,$namesArr[$a])===1 && mb_strlen(trim($namesArr[$a]))>0){
				echo "<span class='error_class'>При заполнении одного из полей <q>Поясняющий текст</q> были использованы запрещённые символы</span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			if (mb_strlen(trim($namesArr[$a]))>50){
				echo "<span class='error_class'>При заполнении одного из полей <q>Поясняющий текст</q> был превышен лимит количества знаков</span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			if($previewsArr[$a]!="0"){
				$previewsArr[$a]=basename($previewsArr[$a]);
				$picturesTotal++;
				$previewsTotal++;
			}
			if($originalsArr[$a]!="0"){
				$originalsArr[$a]=basename($originalsArr[$a]);
				$picturesTotal++;
				$originalsTotal++;
			}
			$size+=strlen(trim($namesArr[$a]))+strlen(trim($originalsArr[$a]))+strlen(trim($previewsTotal[$a]))+self::SIZE_ELEM_MAIN_FACTOR;
			$imagesArr[]="{$originalsArr[$a]}::{$previewsArr[$a]}::{$namesArr[$a]}";
		}
		if ($previewsTotal===0){
			echo "<span class='error_class'>Вы не указали ни одного preview-изображения для галереи</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if ($originalsTotal===0){
			echo "<span class='error_class'>Вы не указали ни одного оригинального изображения для галереи</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		return array("totalString"=>implode(",,",$imagesArr),"imagesCount"=>$picturesTotal);
	}
	
	private function reloadListing($fileArray,$settings){
		$stringReplace="{$fileArray['newId']};;{$settings['gallery_name']};;{$settings['gallery_type']};;{$settings['imagesString']}\r\n";
		$fileArray['fileArray'][$fileArray['stringNum']]=$stringReplace;
		
		$string=implode("",$fileArray['fileArray']);
	
		if (@file_put_contents(self::NAVIGATION_FILE,$string)===false){
			echo "<span class='error_class'>Произошёл сбой при записи файла списка галерей! Проверьте права на возможность перезаписи, данные файлу gallerysList.fbd.csv, находящемуся по следующему пути: functional/imagesGallery/ .</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
}
?>