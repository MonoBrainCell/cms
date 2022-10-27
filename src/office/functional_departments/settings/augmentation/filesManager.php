<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_settings_filesManager {
	const REDIRECT_ADDRESS="index.php?dep=settings&branch=loadFiles.php";
	const MAX_FILES_QUANTITY=10;
	
	public function __construct(){
		if (
		isset($_POST['directory'])===false
		||
		(isset($_POST['directory'])===true && is_array($_POST['directory'])===false)
		||
		isset($_FILES['file'])===false
		||
		count($_POST['directory'])!=count($_FILES['file']['tmp_name'])
		)
			echo "<meta http-equiv='refresh' content='0;url=".self::REDIRECT_ADDRESS ."&msg=params_failed'>";
		$failedFiles=array();
		for ($a=0,$b=count($_FILES['file']['tmp_name']);$a<$b;$a++){
			if (is_uploaded_file($_FILES['file']['tmp_name'][$a])===true){
				if ($_FILES['file']['error'][$a]!==0){
					$failedFiles[]=$_FILES['file']['name'][$a];
					$filesFailedMarker=true;
				}
				else {
					if (@move_uploaded_file($_FILES['file']['tmp_name'][$a],$_POST['directory'][$a].self::leadToLatinSymbs($_FILES['file']['name'][$a]))===false){
						$failedFiles[]=$_FILES['file']['name'][$a];
						$filesFailedMarker=true;
					}
				}
			}
			else {
				$failedFiles[]=$_FILES['file']['name'][$a];
				$filesFailedMarker=true;
			}
		}
		if (isset($filesFailedMarker)===true){
			if (empty($failedFiles)===false)
				$_SESSION['failedFiles']=$failedFiles;
			echo "<meta http-equiv='refresh' content='0;url=".self::REDIRECT_ADDRESS ."&msg=failed_some_files'>";
		}
		else 
			echo "<meta http-equiv='refresh' content='0;url=".self::REDIRECT_ADDRESS ."&msg=success'>";
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
?>