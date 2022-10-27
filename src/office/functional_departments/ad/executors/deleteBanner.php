<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class deletePage extends ErrorsManager {
	const NAVIGATION_FILE="../functional/ad/bannersList.fbd.csv";
	const BANNER_DELETE_PATH="../functional/ad/content/";
	const NAVIGATE_BUTTONS="<a class='buttons_from_script' href='office/index.php?dep=ad&branch=bannersList.php'>Вернуться к списку баннеров</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	private $fileId=0;
	
	public function __construct() {
		if (isset($_POST['submitBut'])===false || isset($_POST['pageNum'])===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$this->fileId=$_POST['pageNum'];
	}
	public function deleteStartEngine(){
		if (file_exists(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Не найден файл для записи информации о баннерах на сайте. Скорее всего он был потерен из-за системного сбоя сервера. Обратитесь к Вашему Хостинг-Провайдеру для проведения backUp'а Вашего сайта.</span>\r\n".self::NAVIGATE_BUTTONS;;
			exit;
		}
		self::rewriteNavigationFile();
		if (file_exists(self::BANNER_DELETE_PATH . $this->fileId .".fbd.tpl")===true){
			if (@unlink(self::BANNER_DELETE_PATH . $this->fileId .".fbd.tpl")===false){
				echo "<span class='error_class'>Произошла ошибка при удалении файла<strong>:". self::BANNER_DELETE_PATH . $this->fileId .".fbd.tpl</strong>. Пожалуйста проверьте факт его отсутствия через Ftp-доступ к Вашему сайту.</span>";
			}
		}
		echo "<span class='success_class'>Удаление прошло успешно!</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
	private function rewriteNavigationFile(){
		$navigationArray=file(self::NAVIGATION_FILE);
		$content="";
		for($a=0,$b=count($navigationArray);$a<$b;$a++){
			$elem=explode(";;",rtrim($navigationArray[$a]));
			if ($elem[0]!=$this->fileId)
				$content.=$navigationArray[$a];
		}
		if (@file_put_contents(self::NAVIGATION_FILE,$content)===false){
			echo "<span class='error_class'>Произошёл сбой при записи файла списка баннеров сайта! Проверьте права на возможность перезаписи, данные файлу bannersList.fbd.csv, находящемуся в functional/ad .</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
}
$temprObject=new deletePage();
$temprObject->deleteStartEngine();
?>