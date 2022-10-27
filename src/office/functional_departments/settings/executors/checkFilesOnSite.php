<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class checkFilesOnSite{
	const NAVIGATE_BUTTONS="<span class='abort_loading_files' id='ajax_close_button'>Вернуться к выбору файлов</span>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	const CONTINUE_BUTTON="<span id='continue_button' class='buttons_from_script'>Загрузить файлы</span>";
	public function __construct(){
		if (isset($_POST['file'])===false || isset($_POST['directory'])===false){
			echo "<span class='error_class'>Были переданы не все данные для адекватной работы данного функционала. Обновите страницы и повторите попытку.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (count($_POST['file'])!=count($_POST['directory'])){
			echo "<span class='error_class'>Данные для адекватной работы данного функционала были переданы с ошибкой. Обновите страницы и повторите попытку.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$allreadyExists=array();
		$initObj=new App_settings_availableFolder();
		$access=$initObj->checkId();
		if ($access===0){
			echo "<span class='error_class'>Вы не имеете необходимого уровня доступа к системе загрузки файлов.</span>".self::NAVIGATE_BUTTONS;
			return false;
		}
		for ($a=0,$b=count($_POST['file']);$a<$b;$a++){
			if (empty($_POST['file'][$a])===true || empty($_POST['directory'][$a])===true){
				echo "<span class='error_class'>Были переданы не все данные для адекватной работы данного функционала. Обновите страницы и повторите попытку.</span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			if ($initObj->checkXML($access,$_POST['directory'][$a])===false){
				echo "<span class='error_class'>Вы попытались загрузить файлы в директории, к которым у Вас нет доступа.</span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
				
			$dirContent=glob($_POST['directory'][$a]."*");
			$fileName=self::leadToLatinSymbs($_POST['file'][$a]);
			for ($c=0,$d=count($dirContent);$c<$d;$c++){
				if (is_file($dirContent[$c])===true){
					$existsFile=basename($dirContent[$c]);
					if ($existsFile==$fileName){
						$allreadyExists[]=array($_POST['file'][$a],$_POST['directory'][$a]);
						$markerOfExisting=true;
					}
				}
			}
		}
		if (isset($markerOfExisting)===true){
			$varArr=array();
			for ($a=0,$b=count($allreadyExists);$a<$b;$a++){
				$varArr[]="--&gt; имя файла: <em style='text-decoration:underline'>{$allreadyExists[$a][0]}</em>, директория: <em style='text-decoration:underline'>{$allreadyExists[$a][1]}</em>";
			}
			echo "<span class='success_class'>Некоторые файлы уже имеются в указанных Вами директориях:<br><br>".implode("<br><br>",$varArr).".<br><br> Если Вы не хотите их потерять - переименуйте загружаемые файлы, затем повторите попытку загрузки. В противном случае можете продолжать загрузку.</span>\r\n".self::NAVIGATE_BUTTONS .self::CONTINUE_BUTTON;
			exit;
		}
		else {
			echo "<span class='success_class'>Вы можете продолжить загрузку файлов</span>\r\n".self::NAVIGATE_BUTTONS .self::CONTINUE_BUTTON;
			exit;
		}
	}
	private function leadToLatinSymbs($string){
		$cyr_string=array("а","б","в","г","д","е","ё","ж","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ъ","ы","ь","э","ю","я"," ","/","`","^","#","@","*","$","%",",",";","=",":","<",">");
		
		$lat_replace=array("a","b","v","g","d","e","e","zh","z","i","i","k","l","m","n","o","p","r","s","t","u","f","kh","ts","ch","sh","sch","","i","","e","yu","ya","_","_","_","_","_","_","_","_","_","_","_","_","_","_","_");
		
		$string=mb_strtolower($string);
		$array=array();$new_word="";
		for ($a=0,$b=mb_strlen($string);$a<$b;$a++){
			$array[]=mb_substr($string,$a,1);
		}
		for ($a=0,$b=count($array);$a<$b;$a++){
			$keyToSymb=array_search($array[$a],$cyr_string);
			if ($keyToSymb===false)
				$new_word.=$array[$a];
			else {
				$new_word.=$lat_replace[$keyToSymb];
			}
		}
		return $new_word;
	}
}
$varObj=new checkFilesOnSite();
?>