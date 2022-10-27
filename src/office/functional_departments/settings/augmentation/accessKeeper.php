<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_settings_accessKeeper extends ErrorsManager {
	const ACCESS_FILE="gatekeeper/users.fbd.xml";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к созданию аккаунта</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=settings&trunk=settingsManager.php'>Вернуться к настройкам</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	public function __construct(){}
	public function startKeeperEngine(){
		$access=array("a"=>3,"v"=>1);
		if (isset($_POST['login'][0])===false || isset($_POST['new_password'][0])===false || isset($_POST['password'][0])===false || isset($_POST['repeat_new_password'][0])===false || isset($_POST['access_type'][0])===false){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (file_exists(self::ACCESS_FILE)===true){
			$file=new DOMDocument();
			$file->formatOutput=true;
			$file->preserveWhiteSpace=false;
			$file->load(self::ACCESS_FILE);
		}
		else {
			echo "<span class='error_class'>Не найден файл содержащий информацию об учётных записях пользователей.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (strlen(trim($_POST['password'][0]))>25){
			echo "<span class='error_class'>При заполнении поля <q>Пароль Управляющего</q> Вы превысили лимит количества символов</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (strlen(trim($_POST['password'][0]))<6){
			echo "<span class='error_class'>Вы не заполнили поле <q>Пароль Управляющего</q></span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (preg_match("/[а-яА-Я\`\~\$\%\^\&\*\{\}\[\]\(\)\=\+\,\.\?\'\"\:\;\№\<\>\/]/u",$_POST['password'][0])===1 && mb_strlen(trim($_POST['password'][0]))>0){
			echo "<span class='error_class'>При заполнении поля <q>Пароль Управляющего</q> были использованы запрещённые символы</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		
		if (strlen(trim($_POST['new_password'][0]))>25){
			echo "<span class='error_class'>При заполнении поля <q>Пароль</q> Вы превысили лимит количества символов</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (strlen(trim($_POST['new_password'][0]))<6){
			echo "<span class='error_class'>Вы не заполнили поле <q>Пароль</q></span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (preg_match("/[а-яА-Я\`\~\$\%\^\&\*\{\}\[\]\(\)\=\+\,\.\?\'\"\:\;\№\<\>\/]/u",$_POST['new_password'][0])===1 && mb_strlen(trim($_POST['new_password'][0]))>0){
			echo "<span class='error_class'>При заполнении поля <q>Пароль</q> были использованы запрещённые символы</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		
		if (strlen(trim($_POST['repeat_new_password'][0]))>25){
			echo "<span class='error_class'>При заполнении поля <q>Повторение пароля</q> Вы превысили лимит количества символов</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (strlen(trim($_POST['repeat_new_password'][0]))<6){
			echo "<span class='error_class'>Вы не заполнили поле <q>Повторение пароля</q></span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (preg_match("/[а-яА-Я\`\~\$\%\^\&\*\{\}\[\]\(\)\=\+\,\.\?\'\"\:\;\№\<\>\/]/u",$_POST['repeat_new_password'][0])===1 && mb_strlen(trim($_POST['repeat_new_password'][0]))>0){
			echo "<span class='error_class'>При заполнении поля <q>Повторение пароля</q> были использованы запрещённые символы</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		
		if (strlen(trim($_POST['login'][0]))>15){
			echo "<span class='error_class'>При заполнении поля <q>Логин</q> Вы превысили лимит количества символов</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (strlen(trim($_POST['login'][0]))<5){
			echo "<span class='error_class'>Вы не заполнили поле <q>Логин</q></span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (preg_match("/[а-яА-Я\`\~\$\%\^\&\*\{\}\[\]\(\)\=\+\,\.\?\'\"\:\;\№\<\>\/]/u",$_POST['login'][0])===1 && mb_strlen(trim($_POST['login'][0]))>0){
			echo "<span class='error_class'>При заполнении поля <q>Логин</q> были использованы запрещённые символы</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$login=trim(strip_tags($_POST['login'][0]));
		$enteredPassword=trim(strip_tags($_POST['password'][0]));
		$enteredNewPassword=trim(strip_tags($_POST['new_password'][0]));
		$enteredPasswordRepeat=trim(strip_tags($_POST['repeat_new_password'][0]));
		$accessType=$_POST['access_type'][0];
		if ($enteredNewPassword!=$enteredPasswordRepeat){
			echo "<span class='error_class'>Содержание поля <q>Пароль</q> должно быть таким же как и содержание поля <q>Повторение Пароля</q></span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$xPathDoc=new DOMXPath($file);
		$accessXML=$xPathDoc->query("/data/users/user[@login='{$login}']");
		if ($accessXML->length!==0){
			echo "<span class='error_class'>Такой Логин уже используется в учётных записях! Выберите другой.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$accessXML=$xPathDoc->query("/data/users/user[@type='sa']/@password");
		$decryptor=new Wspc__MasterEncryption();
		if ($decryptor->extractSymbsFromString($accessXML->item(0)->nodeValue,7)!=sha1($enteredPassword)){
			echo "<span class='error_class'>Введённый Вами пароль Управляющего не соответствует реальному</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (array_key_exists($accessType,$access)===true){
			foreach ($access as $type=>$num){
				$accessXML=$xPathDoc->query("/data/users/user[@type='{$type}']");
				if ($accessXML->length>=$num && $type==$accessType){
					echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
					exit;
				}
			}
		}
		else {
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}//die(self::NAVIGATE_BUTTONS);
		$enteredNewPassword=$decryptor->genHashOfString($enteredNewPassword,"2");
		$enteredNewPassword=$decryptor->embedSymbsToString($enteredNewPassword,"16s_u",7);
		$newAccess=$file->createElement("user");
		$newAccess->setAttribute("login",$login);
		$newAccess->setAttribute("type",$accessType);
		$newAccess->setAttribute("password",$enteredNewPassword);
		$file->getElementsByTagName("users")->item(0)->appendChild($newAccess);
		if (@$file->save(self::ACCESS_FILE)===false){
			echo "<span class='error_class'>Произошёл сбой при записи авторизационных данных! Выйдите из админ. части и попытайтесь войти заново, если попытка окажется неудачной - обратитесь к хостинг-провайдеру для проведения BackUp'a сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		echo "<span class='success_class'>Изменения сохранены.</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
}
?>