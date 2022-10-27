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
	$("#view_available_images").click(function(){
		var datas={};
		datas['phpFileInc']="functional_departments/ad/executors/imagesListViewer.php";
		
		var messageWindowProps={};
		messageWindowProps.startActMsg="Производится поиск доступных изображений . . .";
		messageWindowProps.id="viewer_images_window";
		messageWindowProps.overburdenId="ajax_overburden";
		messageWindowProps.closeButId="ajax_close_button";
		messageWindowProps.errorMsg="Простите, произошла ошибка во время поиска. Обновите страницу в браузере и повторите попытку.";
		
		var attachingParams={};
		attachingParams.fieldElem="input[type=text][name=image_addr]";
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
	$("#view_available_flashes").click(function(){
		var datas={};
		datas['phpFileInc']="functional_departments/ad/executors/flashesListViewer.php";
		
		var messageWindowProps={};
		messageWindowProps.startActMsg="Производится поиск доступных flash-файлов . . .";
		messageWindowProps.id="viewer_images_window";
		messageWindowProps.overburdenId="ajax_overburden";
		messageWindowProps.closeButId="ajax_close_button";
		messageWindowProps.errorMsg="Простите, произошла ошибка во время поиска. Обновите страницу в браузере и повторите попытку.";
		
		var attachingParams={};
		attachingParams.pathConteinerSelector=".path_container";
		attachingParams.closeButId="ajax_close_button";
		
		sendAjaxRqst(datas,messageWindowProps,attachEventsToBoxOfFiles,attachingParams);
	});
	
	$("#view_available_pages").click(function(){
		var datas={};
		datas['phpFileInc']="functional_departments/ad/executors/pagesListViewer.php";
		
		var messageWindowProps={};
		messageWindowProps.startActMsg="Производится поиск страниц . . .";
		messageWindowProps.id="viewer_images_window";
		messageWindowProps.overburdenId="ajax_overburden";
		messageWindowProps.closeButId="ajax_close_button";
		messageWindowProps.errorMsg="Простите, произошла ошибка во время поиска. Обновите страницу в браузере и повторите попытку.";
		
		var attachingParams={};
		attachingParams.fieldElem="input[type=text][name=target_page]";
		attachingParams.pathConteinerSelector=".path_container";
		attachingParams.closeButId="ajax_close_button";
		attachingParams.addIconSelector=".add_to_field_icon";
		
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
	
	var fieldSettings2={};
	fieldSettings2.lengthW={};
	fieldSettings2.lengthW.alertElemSelector=".warning_lenght";
	fieldSettings2.symbolsW={};
	fieldSettings2.symbolsW.symbsToSearch=/[а-яА-Я\,\!\+\~\`\@\#\$\^\*\(\)\[\]\{\}\;\'\"\<\>]/;
	fieldSettings2.symbolsW.alertElemSelector=".warning_sintaxis";
	fieldSettings2.symbolsW.alertText="Введён запрещённый символ &frasl; ";
	
	$("input[type=text][name!=image_addr][name!=target_page]").textFieldsManager(fieldSettings1);
	if ($("input[name=banner_type]:checked").val()=="constructor"){
		$("input[type=text][name=image_addr],input[type=text][name=target_page]").textFieldsManager(fieldSettings2);
	}
	$("input[name=banner_type]").change(function(){
		if ($("body").data("constructor")==undefined){
			var htmlCode='<fieldset><legend>Конструктор</legend><h3>Адрес изображения для баннера</h3><div class="form_elem"><span class="block_with_advice"><span class="view_advice" title="Пояснение"></span><span class="advice">Содержание данного поля укажет на изображение, которое должно быть использовано в качестве баннера. <br> <strong>Не нужно заполнять данное поле, если планируется создавать текстовый баннер.</strong><br><span class="redaction_advice">Пожалуйста заполняйте данное поле латинскими символами и цифрами, также можете использовать: <q> - </q>, <q> _ </q> </span></span></span><input type="text" name="image_addr" value="" maxlength="200"><span class="warning_sintaxis"></span>	<span class="warning_lenght">Осталось символов: 200</span></div><h3>Подсказка при наведении на баннер</h3><div class="form_elem"><span class="block_with_advice"><span class="view_advice" title="Пояснение"></span><span class="advice">Содержание данного поля будет появляться, когда посетитель наведёт курсор на данный баннер, в виде всплывающей подсказки.<br><span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span></span></span><input type="text" name="title_hint" value="" maxlength="100"><span class="warning_sintaxis"></span><span class="warning_lenght">Осталось символов: 100</span></div><h3>Текстовое содержание баннера</h3><div class="form_elem"><span class="block_with_advice"><span class="view_advice" title="Пояснение"></span><span class="advice">Содержание данного поля будет появляться, при отсутствие указанного изображения (если использован <q>Баннер-изображение</q>) или как текст самого баннера (если использован <q>Текстовый баннер</q>).<br><span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span></span></span><input type="text" name="hint" value="" maxlength="150"><span class="warning_sintaxis"></span><span class="warning_lenght">Осталось символов: 150</span></div><h3>Целевая страница</h3><div class="form_elem"><span class="block_with_advice"><span class="view_advice" title="Пояснение"></span><span class="advice">Содержание данного поля будет использовано для указания адреса на который посетитель будет переходить при клике по баннеру.<br><span class="redaction_advice">Пожалуйста заполняйте данное поле латинскими символами и цифрами, также можете использовать: <q> - </q>, <q> _ </q></span></span></span><input type="text" name="target_page" value="" maxlength="200"><span class="warning_sintaxis"></span><span class="warning_lenght">Осталось символов: 200</span></div></fieldset>';
			$("body").data("constructor",htmlCode);
		}
		if ($("body").data("code")==undefined){
			var htmlCode1='<div id="code_block"><h2>Исходный код баннера</h2><div class="form_elem"><span class="block_with_advice" style="margin-right:0;"><span class="view_advice" title="Пояснение"></span><span class="advice">Содержание данного поля будет использовано как исходный html-код баннера.<br>Данный функционал удобно использовать, если Вы хорошо знаете html или от Вас требуется разместить выданный Вам html-код баннера.</span></span><textarea name="banner_code"></textarea></div></div>';
			$("body").data("code",htmlCode1);
		}
		if ($(this).val()=="constructor"){
			$("#code_block").wrap("<div id='cut_paste_elem'></div>");
			var htmlCont=$("#cut_paste_elem").html();
			$("body").data("code",htmlCont);
			$("#cut_paste_elem").html($("body").data("constructor"));
			$("fieldset").unwrap();
			$("input[type=text][name!=image_addr][name!=target_page]").textFieldsManager(fieldSettings1);
			$("input[type=text][name=image_addr],input[type=text][name=target_page]").textFieldsManager(fieldSettings2);
		}
		else if ($(this).val()=="code"){
			$("fieldset").wrap("<div id='cut_paste_elem'></div>");
			var htmlCont=$("#cut_paste_elem").html();
			$("body").data("constructor",htmlCont);
			$("#cut_paste_elem").html($("body").data("code"));
			$("#code_block").unwrap();
		}
	});
});

</script>
