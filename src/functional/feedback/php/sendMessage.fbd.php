<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
/*[а-яА-Яa-zA-Z0-9\,\!\+\-\_\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/] - полный запрет символов (удалять для разрешения)*/
class sendMessageClass extends ErrorsManager {
	
	const EMAIL_ADDRESS="sg.forever@yandex.ru";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к странице</span>\r\n";
	const EMAIL_STYLE_FILE="functional/feedback/css/emailSendStyle.css";
	const CONTENT_HEADER="Content-type: text/html; charset=utf-8\r\n";
	const MESSAGE_THEME="Поступило сообщение с сайта {DOMEN_NAME}";
	const EMAIL_CONTENT=
"{MESSAGE_STYLE}\r\n<h2>Сообщение с сайта: {DOMEN_NAME}</h2>\r\n
<p>Отправитель: <strong>{NAME}</strong></p>\r\n
<p>Контактный Email: <strong>{EMAIL}</strong></p>\r\n
<p>Контактный Телефон: <strong>{PHONE}</strong></p>\r\n
<h3>Сообщение</h3>\r\n
<blockquote>\r\n{MESSAGE}\r\n</blockquote>";

	const SIMPLE_TEXT_PATTERN="/[^0-9а-яА-Яa-zA-Z\-\.\(\)\s]/u";
	const SIMPLE_EMAIL_PATTERN="/[^0-9a-zA-Z\-\.\_\@]/u";
	const STRING_EMAIL_PATTERN="/^[a-zA-Z0-9\.\-\_]+\@[a-zA-Z0-9\-]+\.[a-zA-Z]+/u";
	const SIMPLE_PHONE_PATTERN="/[^0-9\+\-\(\)]/u";
	const SIMPLE_MESSAGE_PATTERN="/[^0-9а-яА-Яa-zA-Z\.\,\!\+\-\_\*\(\)\=\:\;\'\"\<\>\?\s]/u";
	
	private $dataCheckSettings;
	private $datasToRewrite=array();
	
	public function __construct() {
		$this->dataCheckSettings=array(
			'name'=>array(
				'pattern'=>self::SIMPLE_TEXT_PATTERN,
				'maxLenght'=>60,
				'minLenght'=>3,
				'fieldName'=>'Ваше имя'
			),
			'email'=>array(
				'pattern'=>self::SIMPLE_EMAIL_PATTERN,
				'patternStr'=>self::STRING_EMAIL_PATTERN,
				'maxLenght'=>120,
				'fieldName'=>'Ваш E-mail'
			),
			'phone'=>array(
				'pattern'=>self::SIMPLE_PHONE_PATTERN,
				'minLenght'=>7,
				'maxLenght'=>30,
				'fieldName'=>'Ваш телефон'
			),
			'message'=>array(
				'pattern'=>self::SIMPLE_MESSAGE_PATTERN,
				'minLenght'=>5,
				'maxLenght'=>200,
				'fieldName'=>'Ваше сообщение'
			)
		);
	}
	
	public function sendEngine() {mb_regex_encoding("UTF-8");
		if (
		isset($_POST['send_datas'][0])===false
		||
		(isset($_POST['send_datas'][0])===true && mb_strlen(trim($_POST['send_datas'][0]))<1)
		){
			echo "<span class='error_class'>Произошла ошибка при отправке данных. Перезагрузите страницу и повторите попытку.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		foreach ($this->dataCheckSettings as $field=>$properties){
			if (isset($_POST[$field][0])===false){
				echo "<span class='error_class'>Не были переданы данные из поля <q>{$properties['fieldName']}</q></span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			if (isset($properties['pattern'])===true && preg_match($properties['pattern'],$_POST[$field][0])===1 && mb_strlen(trim($_POST[$field][0]))>0){
				echo "<span class='error_class'>При заполнении поля <q>{$properties['fieldName']}</q> были использованы запрещённые символы</span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			if (isset($properties['patternStr'])===true && preg_match($properties['patternStr'],$_POST[$field][0])===0 && mb_strlen(trim($_POST[$field][0]))>0){
				echo "<span class='error_class'>Поле <q>{$properties['fieldName']}</q> было заполнено с ошибками (Не соответствует указанному шаблону заполнения).</span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			if (isset($properties['minLenght'])===true && mb_strlen(trim($_POST[$field][0]))<$properties['minLenght']){
				echo "<span class='error_class'>Вы не заполнили поле <q>{$properties['fieldName']}</q></span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			if (isset($properties['maxLenght'])===true && mb_strlen(trim($_POST[$field][0]))>$properties['maxLenght']){
				echo "<span class='error_class'>При заполнении поля <q>{$properties['fieldName']}</q> Вы превысили лимит количества символов</span>\r\n".self::NAVIGATE_BUTTONS;
				exit;
			}
			$this->datasToRewrite[$field]=trim($_POST[$field][0]);
		}
		self::sendMessage();
		echo "<span class='success_class'>Ваше сообщение отправлено. Мы свяжемся с Вами в ближайшее время.</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
	
	private function sendMessage() {
		$theme=str_replace("{DOMEN_NAME}",$_SERVER['HTTP_HOST'],self::MESSAGE_THEME);
		if (file_exists(self::EMAIL_STYLE_FILE)===true)
			$style="<style type='text/css'>\r\n".file_get_contents(self::EMAIL_STYLE_FILE)."</style>\r\n";
		else
			$style="";
		$content=str_replace("{DOMEN_NAME}",$_SERVER['HTTP_HOST'],self::EMAIL_CONTENT);
		$content=str_replace("{MESSAGE_STYLE}",$style,$content);
		$content=str_replace("{NAME}",$this->datasToRewrite['name'],$content);
		$content=str_replace("{PHONE}",$this->datasToRewrite['phone'],$content);
		$this->datasToRewrite['message']=nl2br($this->datasToRewrite['message'],false);
		$content=str_replace("{MESSAGE}",$this->datasToRewrite['message'],$content);
		if (mb_strlen(trim($this->datasToRewrite['email']))>0)
			$content=str_replace("{EMAIL}",$this->datasToRewrite['email'],$content);
		else
			$content=str_replace("{EMAIL}",'не указан',$content);
		if (@mail(self::EMAIL_ADDRESS,$theme,$content,self::CONTENT_HEADER)===false){
			echo "<span class='error_class'>Произошёл сбой при отправке сообщения! Обновите страницу и повторите попытку.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
}
$varObj=new sendMessageClass();
$varObj->sendEngine();
?>