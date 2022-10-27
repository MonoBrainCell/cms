<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
$loaderObjs->insertCustomPath("Shr","subsidiary/php/{%CLASS_NAME%}.php");
if (file_exists("functional_departments/ad/dummyes/viewFlashesList_mainContent.fbd.tpl")===true)
	$tpl1=file_get_contents("functional_departments/ad/dummyes/viewFlashesList_mainContent.fbd.tpl");
else
	$tpl1="";
if (file_exists("functional_departments/ad/dummyes/viewFlashesList_mainContent.fbd.tpl")===true)
	$tpl2=file_get_contents("functional_departments/ad/dummyes/viewFlashesList_element.fbd.tpl");
else
	$tpl2="";

$varObject=new Shr__listOfFiles("files/media/banners/flashes",$tpl1,$tpl2);
echo $varObject->filesListHTML;
exit;
?>