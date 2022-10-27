<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class deletePage extends ErrorsManager {
	const NAVIGATION_FILE="../functional/imagesGallery/gallerysList.fbd.csv";
	const NAVIGATE_BUTTONS="<a class='buttons_from_script' href='office/index.php?dep=imagesGallery&trunk=gallerysList.php'>Вернуться к списку галерей</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	private $fileId=0;
	private $strNum=0;
	private $itemsQuantity=0;
	
	public function __construct() {
		if (isset($_POST['submitBut'])===false || isset($_POST['pageNum'])===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$this->fileId=$_POST['pageNum'];
	}
	public function deleteStartEngine(){
		if (file_exists(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Не найден файл для записи информации о созданных галереях. Скорее всего он был потерен из-за системного сбоя сервера. Обратитесь к Вашему Хостинг-Провайдеру для проведения backUp'а Вашего сайта.</span>\r\n".self::NAVIGATE_BUTTONS;;
			exit;
		}
		self::rewriteNavigationFile();

		echo "<span class='success_class'>Удаление прошло успешно!</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
	private function rewriteNavigationFile(){
		$navigationArray=file(self::NAVIGATION_FILE);
		$content="";
		$this->itemsQuantity=count($navigationArray);
		for($a=0;$a<$this->itemsQuantity;$a++){
			$elem=explode(";;",rtrim($navigationArray[$a]));
			if ($elem[0]!=$this->fileId)
				$content.=$navigationArray[$a];
			else 
				$this->strNum=$a+1;
		}
		if (@file_put_contents(self::NAVIGATION_FILE,$content)===false){
			echo "<span class='error_class'>Произошёл сбой при записи файла списка созданных галерей! Проверьте права на возможность перезаписи, данные файлу gallerysList.fbd.csv, находящемуся в functional/imagesGallery/ .</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
}
$temprObject=new deletePage();
$temprObject->deleteStartEngine();
?>