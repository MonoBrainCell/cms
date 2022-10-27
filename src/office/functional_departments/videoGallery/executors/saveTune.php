<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class saveVideoGalleryTune{
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к редактированию</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=videoGallery&trunk=videosList.php'>Вернуться к списку видео-вставок</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	const VIDEO_INSERT_TPL_FILE="../functional/videoGallery/dummyes/videoGalleryTpls.fbd.tpl";
	const LEVEL1_SEPARATOR="|--|";
	public function __construct(){
		if(isset($_POST['submit_changes'][0])===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$array=array("browser_headers","html_video");
		$totalArray=array();
		$entityConvert=new maskHTMLEntity(false);
		for ($a=0,$b=count($array);$a<$b;$a++){
			if (isset($_POST[$array[$a]][0])===false)
				$totalArray[]="";
			else
				$totalArray[]=$entityConvert->maskEngine($_POST[$array[$a]][0]);
		}
		
		$string=implode(self::LEVEL1_SEPARATOR,$totalArray);
		if (@file_put_contents(self::VIDEO_INSERT_TPL_FILE,$string)===false){
			echo "<span class='error_class'>Ошибка перезаписи файла ". self::VIDEO_INSERT_TPL_FILE .". Проверьте права доступа к этому файлу.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		echo "<span class='success_class'>Изменение произведено успешно.</span>\r\n". self::NAVIGATE_BUTTONS;
		exit;
	}
}
$varObj=new saveVideoGalleryTune();
?>
