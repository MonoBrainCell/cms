<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
$array=array(
	"title"=>array(
		"name"=>"none",
		"parent"=>"none",
		"type"=>"embed",
		"content"=>"CMS Adept Illusion. Стартовая страница",
		"headerTags"=>"none"
	),
	"base_tag"=>array(
		"name"=>"Shr__baseTagGen",
		"parent"=>"none",
		"type"=>"involvement",
		"content"=>"none",
		"headerTags"=>"none"
	),
	"header_part"=>array(
		"name"=>"none",
		"parent"=>"none",
		"type"=>"embed",
		"content"=>"{include:adminTemplate/htmlDummyes/logo.fbd.tpl}",
		"headerTags"=>"<link type='text/css' rel='stylesheet' href='office/functional_departments/startPage/design/style.css'>\r\n"
	),
	"content"=>array(
		"name"=>"none",
		"parent"=>"none",
		"type"=>"embed",
		"content"=>"{include:functional_departments/startPage/settings/navigationList.fbd.tpl}",
		"headerTags"=>"none"
	)
);
?>