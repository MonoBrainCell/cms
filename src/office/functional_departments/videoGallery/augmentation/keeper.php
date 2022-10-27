<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
/*[а-яА-Яa-zA-Z0-9\,\!\+\-\_\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/] - полный запрет символов (удалять для разрешения)*/
class App_videoGallery_keeper extends ErrorsManager {
	const SIMPLE_TEXT_PATTERN="/[\&\^\~\`\@\#\'\"\<\>]/u";
	const SIMPLE_NUMERAL_PATTERN="/[а-яА-Яa-zA-Z\,\!\+\-\_\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/]/u";
	const NAVIGATION_FILE="../functional/videoGallery/videosList.fbd.csv";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к редактированию видео-вставки</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=videoGallery&trunk=videosList.php'>Вернуться к списку добавленного видео</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	const LIMITER=100;
	
	private $dataCheckSettings;
	
	public function __construct() {
		$this->dataCheckSettings=array(
		'video_name'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>75,
				'minLenght'=>2,
				'fieldName'=>'Название видео-вставки'
			),
		'video_width'=>array(
				'pattern'=>self::SIMPLE_NUMERAL_PATTERN,
				'minLenght'=>1,
				'fieldName'=>'Ширина видео-вставки'
			),
		'video_height'=>array(
				'pattern'=>self::SIMPLE_NUMERAL_PATTERN,
				'minLenght'=>1,
				'fieldName'=>'Высота видео-вставки'
			),
		'code_insert'=>array(
				'minLenght'=>1,
				'fieldName'=>'Код выданный видео-хостером'
			)
		);
	}
	public function startKeeperEngine() {
		$datasToRewrite=array();
		if (isset($_POST['video_id'][0])===false){
			echo "<span class='error_class'>Были переданы не все данные для адекватной работы данного функционала. Обновите страницы и повторите попытку редактирования.".self::NAVIGATE_BUTTONS;
			exit;
		}
		if ($_POST['video_id'][0]!="new")
			unset($this->dataCheckSettings['code_insert']);
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
		if ($_POST['video_id'][0]=="new")
			$datasToRewrite['code_insert']=self::extractCode($datasToRewrite['code_insert']);

		$rewriteDatas=self::searchParams($_POST['video_id'][0]);
		self::reloadListing($rewriteDatas,$datasToRewrite);

		echo "<span class='success_class'>Видео-вставка сохранена. Чтобы увидеть изменения обновите данную страницу в браузере.</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}

	private function searchParams($id){
		if (file_exists(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Не найден файл для записи информации о видео-вставке. Скорее всего он был потерен из-за системного сбоя сервера. Обратитесь к Вашему Хостинг-Провайдеру для проведения backUp'а Вашего сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
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
				if ($id=="new" && $b==self::LIMITER){
					if ($a===0 && isset($marker)===false){
						$elem=explode(";;",$fileArray[$a]);
						$returnDatas['newId']=$elem[0];
						$returnDatas['stringNum']=self::LIMITER - 1;
						$marker=true;
					}
					else {
						$returnDatas['fileArray'][]=$fileArray[$a];
					}
				}
				else {
					$elem=explode(";;",$fileArray[$a]);
					if ($id=="new"){
						$idArray[]=$elem[0]*1;
						$stringsArray[$elem[0]]=$a;
						if ($maxIndex<$elem[0])
							$maxIndex=$elem[0];
					}
					else {
						if ($elem[0]==$id){
							$returnDatas['newId']=$elem[0];
							$returnDatas['stringNum']=$a;
						}
					}
					$returnDatas['fileArray'][]=$fileArray[$a];
				}
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
	
	private function extractCode($fileCode) {
		$findMarker=preg_match("/\<iframe.*src=('|\")(.*?)('|\")/u",$fileCode,$matches);
		if ($findMarker!==0){
			$srcExtract=$matches[2];
			return $srcExtract;
		}
		else {
			echo "<span class='error_class'>При заполнении поля <q>Код выданный видео-хостером</q> Вы указали ошибочный контент</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	
	private function reloadListing($fileArray,$settings){
		if ($_POST['video_id'][0]!="new"){
			$varElem=explode(";;",rtrim($fileArray['fileArray'][$fileArray['stringNum']]));
			$settings['code_insert']=$varElem[4];
		}
		$stringReplace="{$fileArray['newId']};;{$settings['video_name']};;{$settings['video_width']};;{$settings['video_height']};;{$settings['code_insert']}\r\n";
		$fileArray['fileArray'][$fileArray['stringNum']]=$stringReplace;
		
		$string=implode("",$fileArray['fileArray']);
		
		if (@file_put_contents(self::NAVIGATION_FILE,$string)===false){
			echo "<span class='error_class'>Произошёл сбой при записи файла списка добавленного видео! Проверьте права на возможность перезаписи, данные файлу videosList.fbd.csv, находящемуся по следующему пути: functional/videoGallery/ .</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
}
?>