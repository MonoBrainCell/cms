<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class navElemsTextRedact{
	const NAVIGATION_FILE="../navigation.fbd.xml";
	const SITEMAP_FILE="../sitemap.xml";
	const PATH_TO_CONTENT_FILE="../content/";
	const CONTENT_FILE_POSTFIX=".php";
	const PAGE_NAME_PREFIX="/page/";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к редактированию</span>\r\n";
	const SIMPLE_TEXT_PATTERN="/[\&\^\~\`\@\#\'\"\<\>]/u";
	const SIMPLE_ALIAS_PATTERN="/[а-яА-Я\,\!\+\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/]/u";
	private $forbAlias=array("start","news_php","guestbook_php");
	public function __construct(){
		if (file_exists(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Не найден файл: '". self::NAVIGATION_FILE ."'. Проверьте его наличие в корневой папке сайта.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		
		if (
		isset($_POST['submit_redaction'])===false || isset($_POST['field_type'][0])===false
		||
		(isset($_POST['field_type'][0])===true && $_POST['field_type'][0]!="item_text" && $_POST['field_type'][0]!="item_alias")
		||
		isset($_POST['startElem'])===false || isset($_POST['text_to_change'])===false		
		){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}		
		$checkArray=self::initCheckArray($_POST['field_type'][0]);
		if (isset($_POST["text_to_change"][0])===false){
			echo "<span class='error_class'>Не были переданы данные из поля <q>{$checkArray['fieldName']}</q></span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (preg_match($checkArray['pattern'],$_POST["text_to_change"][0])===1 && mb_strlen(trim($_POST["text_to_change"][0]))>0){
			echo "<span class='error_class'>При заполнении поля <q>{$checkArray['fieldName']}</q> были использованы запрещённые символы</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (mb_strlen(trim($_POST["text_to_change"][0]))<$checkArray['minLenght']){
			echo "<span class='error_class'>Вы не заполнили поле <q>{$checkArray['fieldName']}</q></span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (mb_strlen(trim($_POST["text_to_change"][0]))>$checkArray['maxLenght'] && isset($checkArray['specialEdition']['maxLenght'])===false){
			echo "<span class='error_class'>При заполнении поля <q>{$checkArray['fieldName']}</q> Вы превысили лимит количества символов</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$text=trim($_POST["text_to_change"][0]);
		$elemId=$_POST['startElem'];
		if ($_POST['field_type'][0]=="item_alias" && in_array($elemId,$this->forbAlias,TRUE)===true){
			echo "<span class='error_class'>Изменение alias'а указанной страницы недопустимо.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		else if (in_array($elemId,$this->forbAlias,TRUE)===true)
			$elemId=str_replace("_php",".php",$elemId);
		$file=new DOMDocument();
		$file->formatOutput=true;
		$file->preserveWhiteSpace=false;
		$file->load(self::NAVIGATION_FILE);
		$xPathDoc=new DOMXPath($file);
		$checkTag=$xPathDoc->query("/navigation/*//page[@alias='{$elemId}']");
		if ($checkTag->length===0){
			echo "<span class='error_class'>Данная страница не найдена. Возможно страница была удалена. Обновите административный раздел и повторите попытку.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if ($_POST['field_type'][0]=="item_text")
			$checkTag->item(0)->attributes->getNamedItem("name")->nodeValue=$text;
		else if ($_POST['field_type'][0]=="item_alias"){
			$checkTag->item(0)->attributes->getNamedItem("alias")->nodeValue=$text;
			self::renameFile($elemId,$text);
		}
		
		if (@$file->save(self::NAVIGATION_FILE)===false){
			echo "<span class='error_class'>Ошибка перезаписи файла ". self::NAVIGATION_FILE .". Проверьте права доступа к этому файлу.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		if ($_POST['field_type'][0]=="item_alias")
			self::rewriteSitemap($elemId,$text);
		echo "<span class='success_class'>Изменение произведено успешно. Вы можете продолжить работу с навигацией.</span>\r\n". self::NAVIGATE_BUTTONS;
		exit;
	}
	private function initCheckArray($type){
		if ($type=="item_text") {
			$returnArray=array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>100,
				'minLenght'=>3,
				'fieldName'=>'Название страницы в навигации'
			);
		}
		else if ($type=="item_alias"){
			$returnArray=array(
				'pattern'=>self::SIMPLE_ALIAS_PATTERN,
				'maxLenght'=>150,
				'minLenght'=>3,
				'fieldName'=>'Alias страницы (Адрес страницы)',
			);
		}
		return $returnArray;
	}
	private function renameFile($oldName,$newName){
		if (file_exists(self::PATH_TO_CONTENT_FILE .$oldName. self::CONTENT_FILE_POSTFIX)===false){
			echo "<span class='error_class'>Ошибка перезаписи файла контента ".$oldName.". Проверьте права доступа к этому файлу.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		if (@rename(self::PATH_TO_CONTENT_FILE .$oldName. self::CONTENT_FILE_POSTFIX, self::PATH_TO_CONTENT_FILE .$newName. self::CONTENT_FILE_POSTFIX)===false){
			echo "<span class='error_class'>Ошибка перезаписи файла контента ".$oldName.". Проверьте права доступа к этому файлу.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	private function rewriteSitemap($oldName,$newName){
		if (file_exists(self::SITEMAP_FILE)===false){
			echo "<span class='error_class'>Не найден файл: '". self::SITEMAP_FILE ."'. Проверьте его наличие в корневой папке сайта.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		$file=new DOMDocument();
		$file->formatOutput=true;
		$file->preserveWhiteSpace=false;
		$file->load(self::SITEMAP_FILE);
		$nSpace=$file->lookupNamespaceUri($file->namespaceURI);
		$xPathDoc=new DOMXPath($file);
		$xPathDoc->registerNamespace('x', $nSpace); 
		$oldPath="http://".$_SERVER['HTTP_HOST'].self::PAGE_NAME_PREFIX .$oldName;
		$newPath="http://".$_SERVER['HTTP_HOST'].self::PAGE_NAME_PREFIX .$newName;
		$checkTag=$xPathDoc->query("/x:urlset/x:url[x:loc='{$oldPath}']/x:*");
		if ($checkTag->length<2)
			return false;
		$lastmodDate=date("c");
		for ($a=0;$a<2;$a++){
			if ($checkTag->item($a)->nodeName=="loc")
				$checkTag->item($a)->nodeValue=$newPath;
			else if ($checkTag->item($a)->nodeName=="lastmod")
				$checkTag->item($a)->nodeValue=$lastmodDate;			
		}
		if (@$file->save(self::SITEMAP_FILE)===false){
			echo "<span class='error_class'>Ошибка перезаписи файла ". self::SITEMAP_FILE .". Проверьте права доступа к этому файлу.</span>\r\n". self::NAVIGATE_BUTTONS;
			exit;
		}
		return true;
	}
}
$varObj=new navElemsTextRedact();
?>