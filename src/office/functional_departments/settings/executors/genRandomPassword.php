<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
class genRandomPassword {
	const NAVIGATE_BUTTONS="<span class='buttons_from_script' id='recharge_request'>Другой пароль</span>\r\n<span id='ajax_close_button'>Вернуться к изменению пароля</span>\r\n";
	const MIN_PASSWORD_LENGTH=8;
	const MAX_PASSWORD_LENGTH=25;
	
	public function __construct(){
		$passwordSymbols=array(0,1,2,3,4,5,6,7,8,9,"_","-","#","!","@","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");//untill: 14
		if (isset($_POST['quantity'][0])===false
		||
		(isset($_POST['quantity'][0])===true && $_POST['quantity'][0]<self::MIN_PASSWORD_LENGTH)
		)
			$quantity=self::MIN_PASSWORD_LENGTH;
		else if (isset($_POST['quantity'][0])===true && $_POST['quantity'][0]>self::MAX_PASSWORD_LENGTH)
			$quantity=self::MAX_PASSWORD_LENGTH;
		else
			$quantity=$_POST['quantity'][0];
		$string="";
		for ($a=0;$a<=$quantity;$a++){
			$randKey=array_rand($passwordSymbols);
			if ($a===0)
				$randUpperA=rand(0,5);
			else if ($randUpperA<=$a && $randKey>14){
				$string.=strtoupper($passwordSymbols[$randKey]);
				$randUpperA=rand(1,5)+$a;
			}
			else
				$string.=$passwordSymbols[$randKey];
		}
		echo "<span class='success_class'>{$string}</span>\r\n".self::NAVIGATE_BUTTONS;
		exit;
	}
}
$object=new genRandomPassword();
?>