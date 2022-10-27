<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_settings_accessTypeKeeper extends ErrorsManager {
	const ACCESS_FILE="gatekeeper/users.fbd.xml";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к изменению типа доступа</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=settings&trunk=settingsManager.php'>Вернуться к настройкам</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	public function __construct(){}
	public function startKeeperEngine(){
		$access=array("a"=>3,"v"=>1);
		if (isset($_POST['login'][0])===false || isset($_POST['access_type'][0])===false || isset($_POST['password'][0])===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (file_exists(self::ACCESS_FILE)===true){
			$file=new DOMDocument();
			$file->load(self::ACCESS_FILE);
		}
		else {
			echo "<span class='error_class'>Не найден файл содержащий информацию об учётных записях пользователей.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (array_key_exists($_POST['access_type'][0],$access)===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (strlen(trim($_POST['password'][0]))>25){
			echo "<span class='error_class'>При заполнении поля <q>пароль Управляющего</q> Вы превысили лимит количества символов</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (strlen(trim($_POST['password'][0]))<6){
			echo "<span class='error_class'>Вы не заполнили поле <q>пароль Управляющего</q></span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (preg_match("/[а-яА-Я\`\~\$\%\^\&\*\{\}\[\]\(\)\=\+\,\.\?\'\"\:\;\№\<\>\/]/u",$_POST['password'][0])===1 && mb_strlen(trim($_POST['password'][0]))>0){
			echo "<span class='error_class'>При заполнении поля <q>пароль Управляющего</q> были использованы запрещённые символы</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$newType=$_POST['access_type'][0];
		$xPathDoc=new DOMXPath($file);
		$accessXML=$xPathDoc->query("/data/users/user[@login='{$_POST['login'][0]}']");
		if ($accessXML->length===0){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if ($accessXML->item(0)->attributes->getNamedItem("type")->nodeValue=="sa"){
			echo "<span class='error_class'>Нельзя переназначать права доступа у Управляющего администратора.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$accessXML1=$xPathDoc->query("/data/users/user[@type='sa']/@password");
		$enteredPassword=trim(strip_tags($_POST['password'][0]));
		$decryptor=new Wspc__MasterEncryption();
		if ($decryptor->extractSymbsFromString($accessXML1->item(0)->nodeValue,7)!=sha1($enteredPassword)){
			echo "<span class='error_class'>Введённый Вами пароль Управляющего не соответствует реальному</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		
		if ($newType!=$accessXML->item(0)->attributes->getNamedItem("type")->nodeValue){
			foreach ($access as $type=>$quantity){
				$accessXML1=$xPathDoc->query("/data/users/user[@type='{$type}']");
				if ($type==$newType && $accessXML1->length == $quantity){
					echo "<span class='error_class'>Нельзя переназначать права доступа у Управляющего администратора.</span>\r\n".self::NAVIGATE_BUTTONS;
					exit;
				}
			}
		}
		$enteredPassword=trim(strip_tags($_POST['password'][0]));
		$decryptor=new Wspc__MasterEncryption();
		$accessXML1=$xPathDoc->query("/data/users/user[@type='sa']/@password");
		if ($decryptor->extractSymbsFromString($accessXML1->item(0)->nodeValue,7)!=sha1($enteredPassword)){
			echo "<span class='error_class'>Введённый Вами пароль Управляющего не соответствует реальному</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$account=$xPathDoc->query("/data/users/user[@login='{$_POST['login'][0]}']/@type");
		$account->item(0)->nodeValue=$newType;
		if (@$file->save(self::ACCESS_FILE)===false){
			echo "<span class='error_class'>Произошёл сбой при записи авторизационных данных! Выйдите из админ. части и попытайтесь войти заново, если попытка окажется неудачной - обратитесь к хостинг-провайдеру для проведения BackUp'a сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		echo "<span class='success_class'>Изменения сохранены.</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
}
?>