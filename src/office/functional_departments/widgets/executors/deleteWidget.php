<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class deletePage extends ErrorsManager {
	const NAVIGATION_FILE="../functional/widgets/widgetsList.fbd.csv";
	const CONTENT_DELETE_PATH="../functional/widgets/content/wsContent/";
	const HEADERS_DELETE_PATH="../functional/widgets/content/wsHeaders/";
	const NAVIGATE_BUTTONS="<a class='buttons_from_script' href='office/index.php?dep=widgets&trunk=widgetsList.php'>Вернуться к списку виджетов</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
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
			echo "<span class='error_class'>Не найден файл для записи информации о виджетах на сайте. Скорее всего он был потерен из-за системного сбоя сервера. Обратитесь к Вашему Хостинг-Провайдеру для проведения backUp'а Вашего сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		self::rewriteNavigationFile();
		if (file_exists(self::CONTENT_DELETE_PATH . $this->fileId .".fbd.tpl")===true){
			if (@unlink(self::CONTENT_DELETE_PATH . $this->fileId .".fbd.tpl")===false){
				echo "<span class='error_class'>Произошла ошибка при удалении файла<strong>:". self::CONTENT_DELETE_PATH . $this->fileId .".fbd.tpl</strong>. Пожалуйста проверьте факт его отсутствия через Ftp-доступ к Вашему сайту.</span>";
			}
		}
		if (file_exists(self::HEADERS_DELETE_PATH . $this->fileId .".fbd.tpl")===true){
			if (@unlink(self::HEADERS_DELETE_PATH . $this->fileId .".fbd.tpl")===false){
				echo "<span class='error_class'>Произошла ошибка при удалении файла<strong>:". self::HEADERS_DELETE_PATH . $this->fileId .".fbd.tpl</strong>. Пожалуйста проверьте факт его отсутствия через Ftp-доступ к Вашему сайту.</span>";
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
			echo "<span class='error_class'>Произошёл сбой при записи файла списка виджетов сайта! Проверьте права на возможность перезаписи, данные файлу widgetsList.fbd.csv, находящемуся в functional/widgets .</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
}
$temprObject=new deletePage();
$temprObject->deleteStartEngine();
?>