<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
/*[а-яА-Яa-zA-Z0-9\,\!\+\-\_\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/] - полный запрет символов (удалять для разрешения)*/
class orderItemClass extends ErrorsManager {
	const ORDER_FUNC_ENABLED=false;
	//---
	const EMAIL_ADDRESS="sg.forever@yandex.ru";
	const NAVIGATE_BUTTONS="<span id='ajax_close_button'>Вернуться к странице</span>\r\n";
	const EMAIL_STYLE_FILE="functional/costEngine/css/orderItemStyle.css";
	const CONTENT_HEADER="Content-type: text/html; charset=utf-8\r\n";
	const MESSAGE_THEME="Поступил заказ с сайта {DOMEN_NAME}";
	const EMAIL_CONTENT=
"{MESSAGE_STYLE}\r\n<h2>Заказ с сайта: {DOMEN_NAME}</h2>\r\n
{ORDER_DETAILS}
<p class='total'>Итого: {ORDER_SUMM} руб.</p>
<p>Заказчик: <strong>{NAME}</strong></p>\r\n
<p>Контактный Email: <strong>{EMAIL}</strong></p>\r\n
<p>Контактный Телефон: <strong>{PHONE}</strong></p>\r\n
<h3>Приложенное сообщение</h3>\r\n
<blockquote>\r\n{MESSAGE}\r\n</blockquote>";
	
	const ITEM_WITHOUT_FACTOR=
"<h3>{DETAIL_DESC}</h3>
<p>{DETAIL_TEXT}: {DETAIL_VALUE}</p>";
	const ITEM_WITH_FACTOR=
"<h3>{DETAIL_DESC}</h3>
<p>{DETAIL_TEXT}: {DETAIL_VALUE} X {DETAIL_FACTOR} = {TOTAL_VALUE}</p>";
	//---
	const SIMPLE_TEXT_PATTERN="/[^0-9а-яА-Яa-zA-Z\-\.\(\)\s]/u";
	const SIMPLE_EMAIL_PATTERN="/[^0-9a-zA-Z\-\.\_\@]/u";
	const STRING_EMAIL_PATTERN="/^[a-zA-Z0-9\.\-\_]+\@[a-zA-Z0-9\-]+\.[a-zA-Z]+/u";
	const SIMPLE_PHONE_PATTERN="/[^0-9\+\-\(\)]/u";
	const SIMPLE_NUMERAL_PATTERN="/[^0-9]/u";
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
			'quantity'=>array(
				'pattern'=>self::SIMPLE_NUMERAL_PATTERN,
				'minLenght'=>1,
				'maxLenght'=>8,
				'fieldName'=>'Количество'
			),
			'message'=>array(
				'pattern'=>self::SIMPLE_MESSAGE_PATTERN,
				'maxLenght'=>200,
				'fieldName'=>'Ваше сообщение'
			)
		);
		
		$this->priceList=array(
			'item_type'=>array(
				'name'=>'Наименование',
				'value'=>array(
					'item1'=>array(
						'name'=>'Позиция 1',
						'value'=>950
					),
					'item2'=>array(
						'name'=>'Позиция 2',
						'value'=>3100
					),
					'item3'=>array(
						'name'=>'Позиция 3',
						'value'=>1200
					),
					'item4'=>array(
						'name'=>'Позиция 4',
						'value'=>2250
					),
					'item5'=>array(
						'name'=>'Позиция 5',
						'value'=>1900
					)
				)
			),
			'quantity'=>array(
				'name'=>'Количество',
				'value'=>true,
				'valueDesc'=>'Ед.',
				'factor'=>400
			),
			'option1'=>array(
				'name'=>'Опция 1',
				'valueDesc'=>'Вкл.',
				'value'=>2500
			),
			'option2'=>array(
				'name'=>'Опция 2',
				'valueDesc'=>'Вкл.',
				'value'=>300
			),
			'option3'=>array(
				'name'=>'Опция 3',
				'valueDesc'=>'Вкл.',
				'value'=>1000
			)
		);
	}
	
	public function sendEngine() {
		if (self::ORDER_FUNC_ENABLED!==false){
			mb_regex_encoding("UTF-8");
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
			unset($this->datasToRewrite['quantity']);
			self::sendMessage();
			echo "<span class='success_class'>Ваш заказ принят. Мы свяжемся с Вами в ближайшее время.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
		else {
			echo "<span class='error_class'>Простите! Функция заказа отключена. Вы можете только воспользоваться он-лайн калькулятором на странице.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
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
		if (mb_strlen(trim($this->datasToRewrite['message']))>0){
			$this->datasToRewrite['message']=nl2br($this->datasToRewrite['message'],false);
			$content=str_replace("{MESSAGE}",$this->datasToRewrite['message'],$content);
		}
		else 
			$content=str_replace("{MESSAGE}","не указано",$content);
		if (mb_strlen(trim($this->datasToRewrite['email']))>0)
			$content=str_replace("{EMAIL}",$this->datasToRewrite['email'],$content);
		else
			$content=str_replace("{EMAIL}",'не указан',$content);
		$order=self::genOrderDetails();
		$content=str_replace("{ORDER_DETAILS}",$order['htmlOrder'],$content);
		$content=str_replace("{ORDER_SUMM}",$order['totalCost'],$content);
		if (@mail(self::EMAIL_ADDRESS,$theme,$content,self::CONTENT_HEADER)===false){
			echo "<span class='error_class'>Произошёл сбой при отправке данных!Обновите страницу и повторите попытку заказа.</span>\r\n".self::NAVIGATE_BUTTONS;
			exit;
		}
	}
	
	private function genOrderDetails(){
		$htmlCode="";
		$totalMems=array();
		foreach ($this->priceList as $item=>$properties){
			if (isset($_POST[$item][0])===false){
				$totalMems[$item]=0;
				continue;
			}
			$desc=$properties['name'];
			if (is_array($properties['value'])===true ){
				if (array_key_exists($_POST[$item][0],$properties['value'])===true){
					$value=$properties['value'][$_POST[$item][0]]['value'];
					$valueDesc=$properties['value'][$_POST[$item][0]]['name'];
				}
				else {
					$value=0;
					$valueDesc="";
				}
				$htmlCode.=self::ITEM_WITHOUT_FACTOR;
			}
			else {
				$valueDesc=$properties['valueDesc'];
				if (isset($properties['factor'])===true){
					$value=1*$_POST[$item][0]*$properties['factor'];
					$htmlCode.=self::ITEM_WITH_FACTOR;
				}
				else {
					$value=$properties['value'];
					$htmlCode.=self::ITEM_WITHOUT_FACTOR;
				}
			}
			$htmlCode=str_replace("{DETAIL_DESC}",$desc,$htmlCode);
			$htmlCode=str_replace("{DETAIL_TEXT}",$valueDesc,$htmlCode);
			if (isset($properties['factor'])===true){
				$htmlCode=str_replace("{DETAIL_VALUE}",$_POST[$item][0],$htmlCode);
				$htmlCode=str_replace("{DETAIL_FACTOR}",$properties['factor'],$htmlCode);
				$htmlCode=str_replace("{TOTAL_VALUE}",$value,$htmlCode);
				$totalMems[$item]=$value;
			}
			else {
				if ($value===0)
					$htmlCode=str_replace("{DETAIL_VALUE}","",$htmlCode);
				else {
					$htmlCode=str_replace("{DETAIL_VALUE}",$value,$htmlCode);
					$totalMems[$item]=$value;
				}
			}
		}
		$total=$totalMems['item_type']+$totalMems['quantity']+$totalMems['option1']+$totalMems['option2']+$totalMems['option3'];
		
		return array('htmlOrder'=>$htmlCode,'totalCost'=>$total);
	}
}
$varObj=new orderItemClass();
$varObj->sendEngine();
?>