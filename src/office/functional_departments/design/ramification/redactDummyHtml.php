<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
$settings_file="functional_departments/design/settings/redactDummyHtmlSettings.php";
$loaderObjs->insertCustomPath("Shr","subsidiary/php/{%CLASS_NAME%}.php");
$loaderObjs->insertCustomPath("Spl","specials/{%CLASS_NAME%}.php");
if (file_exists($settings_file)===true)
	include_once($settings_file);
	
$html=new Spl__pageOutput($array);
echo $html->returnHtml();
?>