<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class deleteAccess extends ErrorsManager {
	const ACCESS_FILE="gatekeeper/users.fbd.xml";
	const NAVIGATE_BUTTONS="<a class='buttons_from_script' href='office/index.php?dep=settings&branch=accessList.php'>Вернуться к списку аккаунтов</a>\r\n<a class='buttons_from_script' href='office/index.php?dep=settings&trunk=settingsManager.php'>Вернуться к настройкам</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	
	private $file; private $access;
	
	public function __construct() {
		if (isset($_POST['submitBut'])===false || isset($_POST['pageNum'])===false || isset($_POST['password'])===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (file_exists(self::ACCESS_FILE)===true){
			$this->file=new DOMDocument();
			$this->file->load(self::ACCESS_FILE);
		}
		else {
			echo "<span class='error_class'>Не найден файл содержащий информацию об учётных записях пользователей.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (strlen(trim($_POST['password']))>25){
			echo "<span class='error_class'>При заполнении поля <q>пароль Управляющего</q> Вы превысили лимит количества символов</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (strlen(trim($_POST['password']))<6){
			echo "<span class='error_class'>Вы не заполнили поле <q>пароль Управляющего</q></span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (preg_match("/[а-яА-Я\`\~\$\%\^\&\*\{\}\[\]\(\)\=\+\,\.\?\'\"\:\;\№\<\>\/]/u",$_POST['password'])===1 && mb_strlen(trim($_POST['password']))>0){
			echo "<span class='error_class'>При заполнении поля <q>пароль Управляющего</q> были использованы запрещённые символы</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		
		$this->access=$_POST['pageNum'];
		$xPathDoc=new DOMXPath($this->file);
		$accessXML=$xPathDoc->query("/data/users/user[@login='".$this->access ."']/@type");
		if ($accessXML->length===0){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if ($accessXML->item(0)->nodeValue=="sa"){
			echo "<span class='error_class'>Нельзя удалить аккаунт Управляющего администратора.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$accessXML1=$xPathDoc->query("/data/users/user[@type='sa']/@password");
		$enteredPassword=trim(strip_tags($_POST['password']));
		$decryptor=new Wspc__MasterEncryption();
		if ($decryptor->extractSymbsFromString($accessXML1->item(0)->nodeValue,7)!=sha1($enteredPassword)){
			echo "<span class='error_class'>Введённый Вами пароль Управляющего не соответствует реальному</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	public function deleteStartEngine(){
		if (file_exists(self::ACCESS_FILE)===false){
			echo "<span class='error_class'>Не найден файл для записи информации об администраторах сайта. Скорее всего он был потерен из-за системного сбоя сервера. Обратитесь к Вашему Хостинг-Провайдеру для проведения backUp'а Вашего сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$deleteXML=new DOMXPath($this->file);
		$accessXML=$deleteXML->query("/data/users/user[@login='".$this->access ."']");
		$this->file->getElementsByTagName("users")->item(0)->removeChild($accessXML->item(0));
		if (@$this->file->save(self::ACCESS_FILE)===false){
			echo "<span class='error_class'>Произошёл сбой при записи авторизационных данных! Выйдите из админ. части и попытайтесь войти заново, если попытка окажется неудачной - обратитесь к хостинг-провайдеру для проведения BackUp'a сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		echo "<span class='success_class'>Удаление прошло успешно!</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
}
$temprObject=new deleteAccess();
$temprObject->deleteStartEngine();
?>