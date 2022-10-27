<script type="text/javascript" src="office/jq_libs/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="office/specials/jss/attachElemToWindow.plugin.js"></script>
<script type="text/javascript" src="office/specials/jss/navigation_manager.js"></script>
<link type="text/css" rel="stylesheet" href="office/functional_departments/ad/design/redact_banner_style.css">
<link type="text/css" rel="stylesheet" href="office/functional_departments/ad/design/custom_styles.css">
<script type="text/javascript" src="office/subsidiary/js/collectFormElems.func.js"></script>
<script type="text/javascript" src="office/subsidiary/js/sendAjaxRqst.func.js"></script>
<script type="text/javascript" src="office/subsidiary/js/textFieldsManager.plugin.js"></script>
<script type="text/javascript" src="office/subsidiary/js/attachEventsToBoxOfFiles.func.js"></script>
<script type="text/javascript">

$(document).ready(function(){
	$("form[target=ajax]").submit(function(){ return false; });
	$("form[target=ajax] input[type=submit]").click(function(){
	var formName=$(this).parents("form").attr("name");
	var datas=collectFormElems(formName);
	datas['phpFileInc']=$(this).parents("form").attr("action");
	
	var messageWindowProps={};
	messageWindowProps.startActMsg="Производится отправка данных для сохранения . . .";
	messageWindowProps.id="ajax_window";
	messageWindowProps.overburdenId="ajax_overburden";
	messageWindowProps.closeButId="ajax_close_button";
	messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку.";
	
	sendAjaxRqst(datas,messageWindowProps,false,false);
	});
	//---
	$("#view_available_flashes").click(function(){
		var datas={};
		datas.phpFileInc="functional_departments/widgets/executors/filesViewer.php";
		datas.direction="javascript";
		var messageWindowProps={};
		messageWindowProps.startActMsg="Производится поиск доступных javascript-файлов . . .";
		messageWindowProps.id="viewer_images_window";
		messageWindowProps.overburdenId="ajax_overburden";
		messageWindowProps.closeButId="ajax_close_button";
		messageWindowProps.errorMsg="Простите, произошла ошибка во время поиска. Обновите страницу в браузере и повторите попытку.";
		
		var attachingParams={};
		attachingParams.closeButId="ajax_close_button";
		
		sendAjaxRqst(datas,messageWindowProps,attachEventsToBoxOfFiles,attachingParams);
	});
	
	$("#view_available_pages").click(function(){
		var datas={};
		datas.phpFileInc="functional_departments/widgets/executors/filesViewer.php";
		datas.direction="css";
		var messageWindowProps={};
		messageWindowProps.startActMsg="Производится поиск css-файлов . . .";
		messageWindowProps.id="viewer_images_window";
		messageWindowProps.overburdenId="ajax_overburden";
		messageWindowProps.closeButId="ajax_close_button";
		messageWindowProps.errorMsg="Простите, произошла ошибка во время поиска. Обновите страницу в браузере и повторите попытку.";
		
		var attachingParams={};
		attachingParams.closeButId="ajax_close_button";
		
		sendAjaxRqst(datas,messageWindowProps,attachEventsToBoxOfFiles,attachingParams);
	});
	
	//---
	var fieldSettings1={};
	fieldSettings1.lengthW={};
	fieldSettings1.lengthW.alertElemSelector=".warning_lenght";
	fieldSettings1.symbolsW={};
	fieldSettings1.symbolsW.symbsToSearch=/[\&\^\~\`\@\#\'\"\<\>]/;
	fieldSettings1.symbolsW.alertElemSelector=".warning_sintaxis";
	fieldSettings1.symbolsW.alertText="Введён запрещённый символ &frasl; ";
	
	$("input[type=text][name=widget_name]").textFieldsManager(fieldSettings1);
});

</script>
