<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
$loaderObjs->insertCustomPath("Shr","subsidiary/php/{%CLASS_NAME%}.php");
if (isset($_POST['direction'])===true && $_POST['direction']=="javascript")
	$path="functional/guestbook/javascript";
else if (isset($_POST['direction'])===true && $_POST['direction']=="css")
	$path="functional/guestbook/css";
else
	$path="functional/guestbook/php";
if (file_exists("functional_departments/news/dummyes/viewFiles_mainContent.fbd.tpl")===true)
	$tpl1=file_get_contents("functional_departments/news/dummyes/viewFiles_mainContent.fbd.tpl");
else
	$tpl1="";
if (file_exists("functional_departments/news/dummyes/viewFiles_mainContent.fbd.tpl")===true)
	$tpl2=file_get_contents("functional_departments/news/dummyes/viewFiles_element.fbd.tpl");
else
	$tpl2="";

$varObject=new Shr__listOfFiles($path,$tpl1,$tpl2);
echo $varObject->filesListHTML;
exit;
?>