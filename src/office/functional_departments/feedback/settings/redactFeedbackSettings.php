<?php
// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
$array=array(
	"title"=>array(
		"name"=>"none",
		"parent"=>"none",
		"type"=>"embed",
		"content"=>"Редактирование формы обратной связи",
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
		"content"=>"{include:adminTemplate/htmlDummyes/admin_navigation.fbd.tpl}",
		"headerTags"=>"<link type='text/css' rel='stylesheet' href='office/adminTemplate/style/style.css'>\r\n"
	),
	"content"=>array(
		"name"=>"App_feedback_developer",
		"parent"=>"none",
		"type"=>"involvement",
		"content"=>"none",
		"headerTags"=>"{include:functional_departments/feedback/settings/redactFeedback_headers.fbd.tpl}"
	)
);
?>