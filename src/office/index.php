<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
setlocale(LC_ALL, "ru_RU.UTF8");
mb_internal_encoding("UTF-8");
include_once("specials/unescaped_file.php");

$loaderObjs=new Loader();

$auth=new Wspc__Gatekeeper();
$fbdPages=$auth->checkAuth();

$GETInf=new manipulateGETInf();
$GETDep=$GETInf->searchGETElem("dep");
if ($GETDep===false)
	include_once("functional_departments/startPage/start.php");
else {
	$GETTrunk=$GETInf->searchGETElem("trunk");
	$GETBranch=$GETInf->searchGETElem("branch");
	if ($GETTrunk!==false){
		$path="functional_departments/{$GETDep}/{$GETTrunk}";
		if (in_array($path,$fbdPages,true)===true)
			ErrorsManager::hideAccessError("page");
		include_once($path);
	}
	else if ($GETBranch!==false) {
		$path="functional_departments/{$GETDep}/ramification/{$GETBranch}";
		if (in_array($path,$fbdPages,true)===true)
			ErrorsManager::hideAccessError("page");
		include_once($path);
	}
	else {
		$availableFiles=glob("functional_departments/{$GETDep}/*.php");
		if (empty($availableFiles)!==true && $availableFiles!==false){
			if (in_array($availableFiles[0],$fbdPages,true)===true)
				ErrorsManager::hideAccessError("page");
			include_once($availableFiles[0]);
		}
	}
}
exit;
?>