<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
$loaderObjs->insertCustomPath("Shr","subsidiary/php/{%CLASS_NAME%}.php");
if (isset($_POST['direction'])===true)
	$pathEnd="functional/costEngine/{$_POST['direction']}";
else {
	$pathEnd="functional/costEngine/";
}
if (file_exists("functional_departments/widgets/dummyes/viewFiles_mainContent.fbd.tpl")===true)
	$tpl1=file_get_contents("functional_departments/widgets/dummyes/viewFiles_mainContent.fbd.tpl");
else
	$tpl1="";
if (file_exists("functional_departments/widgets/dummyes/viewFiles_mainContent.fbd.tpl")===true)
	$tpl2=file_get_contents("functional_departments/widgets/dummyes/viewFiles_element.fbd.tpl");
else
	$tpl2="";

$varObject=new Shr__listOfFiles($pathEnd,$tpl1,$tpl2);
echo $varObject->filesListHTML;
exit;
?>