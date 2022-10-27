<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class App_settings_loginKeeper extends ErrorsManager {
	const ACCESS_FILE="gatekeeper/users.fbd.xml";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к изменению логина</span>\r\n<a class='buttons_from_script' href='office/index.php?dep=settings&trunk=settingsManager.php'>Вернуться к настройкам</a>\r\n<a class='buttons_from_script' href='office/index.php'>Вернуться на главную</a>";
	const SUCCESS_BUTTON="<a class='buttons_from_script' href='office/index.php?logout=yes'>Перезайти</a>";
	public function __construct(){}
	public function startKeeperEngine(){
		if (isset($_POST['old_login'][0])===false || isset($_POST['new_login'][0])===false || isset($_POST['password'][0])===false){
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
		if (strlen(trim($_POST['new_login'][0]))>15){
			echo "<span class='error_class'>При заполнении поля <q>Новый Логин</q> Вы превысили лимит количества символов</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (strlen(trim($_POST['new_login'][0]))<5){
			echo "<span class='error_class'>Вы не заполнили поле <q>Новый Логин</q></span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		if (preg_match("/[а-яА-Я\`\~\$\%\^\&\*\{\}\[\]\(\)\=\+\,\.\?\'\"\:\;\№\<\>\/]/u",$_POST['new_login'][0])===1 && mb_strlen(trim($_POST['new_login'][0]))>0){
			echo "<span class='error_class'>При заполнении поля <q>Новый Логин</q> были использованы запрещённые символы</span>\r\n".self::NAVIGATE_BUTTONS;
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
		$newLogin=trim(strip_tags($_POST['new_login'][0]));
		$xPathDoc=new DOMXPath($file);
		$account=$xPathDoc->query("/data/users/user[@login='{$_POST['old_login'][0]}']/@login");
		if ($account->length===0){
			echo "<span class='error_class'>Произошёл сбой при передаче данных для обработки. Повторите попытку!</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$accessXML=$xPathDoc->query("/data/users/user[@login='{$newLogin}']");
		if ($accessXML->length!==0){
			echo "<span class='error_class'>Такой Логин уже используется в учётных записях! Выберите другой.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$enteredPassword=trim(strip_tags($_POST['password'][0]));
		$decryptor=new Wspc__MasterEncryption();
		$accessXML=$xPathDoc->query("/data/users/user[@type='sa']/@password");
		if ($decryptor->extractSymbsFromString($accessXML->item(0)->nodeValue,7)!=sha1($enteredPassword)){
			echo "<span class='error_class'>Введённый Вами пароль Управляющего не соответствует реальному</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		$account->item(0)->nodeValue=$newLogin;
		if (@$file->save(self::ACCESS_FILE)===false){
			echo "<span class='error_class'>Произошёл сбой при записи авторизационных данных! Выйдите из админ. части и попытайтесь войти заново, если попытка окажется неудачной - обратитесь к хостинг-провайдеру для проведения BackUp'a сайта.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		echo "<span class='success_class'>Изменения сохранены. Журнал логинов пользователей обновлен. Для дальнейшей корректной работы с CMS перезайдите в административную часть.</span>\r\n".self::SUCCESS_BUTTON;
		exit;
	}
}
?>