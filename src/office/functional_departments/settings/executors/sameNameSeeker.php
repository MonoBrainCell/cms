<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class sameNameSeeker {
	const NAVIGATE_BUTTONS="<span class='abort_rename_files' id='ajax_close_button'>Вернуться к выбору файла</span>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	const CONTINUE_BUTTON="<span id='continue_button' class='buttons_from_script'>Продолжить</span>";
	public function __construct(){
		if (isset($_POST['old_file_name'][0])===false || isset($_POST['directory'][0])===false || isset($_POST['new_file_name'][0])===false || isset($_POST['submit_redaction'][0])===false){
			echo "<span class='error_class'>Были переданы не все данные для адекватной работы данного функционала. Обновите страницы и повторите попытку.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$initObj=new App_settings_availableFolder();
		$access=$initObj->checkId();
		if ($access===0){
			echo "<span class='error_class'>Вы не имеете необходимого уровня доступа к системе загрузки файлов.</span>".self::NAVIGATE_BUTTONS;
			return false;
		}
		if ($initObj->checkXML($access,$_POST['directory'][0])===false){
			echo "<span class='error_class'>Вы попытались изменить содержание директории, к которой у Вас недостаточно прав доступа.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$fileName=self::leadToLatinSymbs($_POST['new_file_name'][0]);
		if (file_exists($_POST['directory'][0].$fileName)===true)
			$existsFile=$fileName;
		if (isset($existsFile)===true){
			$varArr="--&gt; имя файла: <em style='text-decoration:underline'>{$fileName}</em>, директория: <em style='text-decoration:underline'>{$_POST['directory'][0]}</em>";
			echo "<span class='success_class'>Некоторые файлы уже имеются в указанных Вами директориях:<br><br>".$varArr.".<br><br> Если Вы не хотите их потерять - отмените текущее действие и переименуйте их.</span>\r\n".self::NAVIGATE_BUTTONS .self::CONTINUE_BUTTON;
			exit;
		}
		echo "<span class='success_class'>Вы можете продолжить текущее действие</span>\r\n".self::NAVIGATE_BUTTONS .self::CONTINUE_BUTTON;
		exit;
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
$varObj=new sameNameSeeker();
?>