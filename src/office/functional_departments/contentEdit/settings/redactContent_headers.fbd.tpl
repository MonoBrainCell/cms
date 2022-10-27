<script type="text/javascript" src="office/jq_libs/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="office/specials/jss/attachElemToWindow.plugin.js"></script>
<script type="text/javascript" src="office/specials/jss/navigation_manager.js"></script>
<link type="text/css" rel="stylesheet" href="office/functional_departments/contentEdit/design/redact_page_style.css">
<link type="text/css" rel="stylesheet" href="office/functional_departments/contentEdit/design/custom_styles.css">
<script type="text/javascript" src="office/subsidiary/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
    mode : "textareas",
    theme : "advanced",
	//content_css:"jscripts/tiny_mce/custom_css.css",
	//skin: "o2k7",
	//skin_variant:"silver",
    language : "ru",
	plugins: "advhr,advlist,insertdatetime,media,table,fullscreen,paste",/*wordcount,fullpage*/
	element_format: "html",
	shema:"html5",
	document_base_url:"/",
	relative_urls : false,
	convert_urls : false,
	 theme_advanced_buttons1 : "copy,paste,pastetext,pasteword,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect,|,|,fullscreen",
        theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,advhr,blockquote,|,undo,redo,|,link,unlink,|,image,media,|,charmap,sub,sup,|,forecolor,backcolor,|,insertdate,inserttime",
        theme_advanced_buttons3 : "tablecontrols,|,removeformat,|,cleanup,|,code,|,help",
        theme_advanced_buttons4 : "",
	theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
	theme_advanced_blockformats : "p,div,h1,h2,h3,h4,h5,h6,blockquote,dt,dd",
	theme_advanced_font_sizes:"10px,11px,12px,13px,14px,15px,16px,17px,18px,19px,20px,21px,22px",
	theme_advanced_resizing : true,
	external_image_list_url : "office/subsidiary/php/tinymceImagesList.php"
});
//childWind.document.getElementById('src').value="way/to/window.path";
</script>
<script type="text/javascript" src="office/subsidiary/js/collectFormElems.func.js"></script>
<script type="text/javascript" src="office/subsidiary/js/sendAjaxRqst.func.js"></script>
<script type="text/javascript" src="office/subsidiary/js/textFieldsManager.plugin.js"></script>
<script type="text/javascript" src="office/subsidiary/js/attachEventsToBoxOfFiles.func.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("form[target=ajax]").submit(function(){ return false; });
	$("input[name=submit_redaction]").click(function(){
	tinyMCE.triggerSave();
	var datas=collectFormElems("develop_content");
	datas['phpFileInc']=$(this).parents("form").attr("action");
	
	var messageWindowProps={};
	messageWindowProps.startActMsg="Производится отправка данных для сохранения . . .";
	messageWindowProps.id="ajax_window";
	messageWindowProps.overburdenId="ajax_overburden";
	messageWindowProps.closeButId="ajax_close_button";
	messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку.";
	
	sendAjaxRqst(datas,messageWindowProps,false,false);
	});
	/*---*/
	$("#view_available_images").click(function(){
		var datas={};
		datas['phpFileInc']="functional_departments/contentEdit/executors/imagesListViewer.php";
		
		var messageWindowProps={};
		messageWindowProps.startActMsg="Производится поиск доступных изображений . . .";
		messageWindowProps.id="viewer_images_window";
		messageWindowProps.overburdenId="ajax_overburden";
		messageWindowProps.closeButId="ajax_close_button";
		messageWindowProps.errorMsg="Простите, произошла ошибка во время поиска. Обновите страницу в браузере и повторите попытку.";
		
		var attachingParams={};
		if (document.body.childWindow!=undefined){
			attachingParams.fieldElem=document.body.childWindow.document.getElementById('src');
		}
		else {
			attachingParams.fieldElem="no_elem";
		}
		attachingParams.pathConteinerSelector=".path_container";
		attachingParams.closeButId="ajax_close_button";
		attachingParams.addIconSelector=".add_to_field_icon";
		
		attachingParams.imageManager={};
		attachingParams.imageManager.imageId="view_image";
		attachingParams.imageManager.preloaderId="image_preloader";
		attachingParams.imageManager.viewIconSelector=".view_image_icon";
		attachingParams.imageManager.navigateButtons={};
		attachingParams.imageManager.navigateButtons.prevSelector="#prev_image_view";
		attachingParams.imageManager.navigateButtons.nextSelector="#next_image_view";
		attachingParams.imageManager.rowContainingIconSelector=".file_row";
		attachingParams.imageManager.realSizeButSelector="#zoom_image_to_true";
		attachingParams.imageManager.undelyingId="under_big_image";
		
		
		sendAjaxRqst(datas,messageWindowProps,attachEventsToBoxOfFiles,attachingParams);
	});
	/*---*/
	var fieldSettings1={};
	fieldSettings1.lengthW={};
	fieldSettings1.lengthW.alertElemSelector=".warning_lenght";
	fieldSettings1.symbolsW={};
	fieldSettings1.symbolsW.symbsToSearch=/[\&\^\~\`\@\#\'\"\<\>]/;
	fieldSettings1.symbolsW.alertElemSelector=".warning_sintaxis";
	fieldSettings1.symbolsW.alertText="Введён запрещённый символ &frasl; ";
	
	var fieldSettings2={};
	fieldSettings2.lengthW={};
	fieldSettings2.lengthW.alertElemSelector=".warning_lenght";
	fieldSettings2.symbolsW={};
	fieldSettings2.symbolsW.symbsToSearch=/[а-яА-Я\,\!\+\~\`\@\#\$\%\^\&\*\(\)\=\[\]\{\}\:\;\'\"\<\>\?\/]/;
	fieldSettings2.symbolsW.alertElemSelector=".warning_sintaxis";
	fieldSettings2.symbolsW.alertText="Введён запрещённый символ &frasl; ";
	
	$("input[type=text][name!=page_alias]").textFieldsManager(fieldSettings1);
	$("input[type=text][name=page_alias]").textFieldsManager(fieldSettings2);
	
});
</script>
