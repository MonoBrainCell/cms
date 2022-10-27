<script type="text/javascript" src="office/jq_libs/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="office/specials/jss/attachElemToWindow.plugin.js"></script>
<script type="text/javascript" src="office/specials/jss/navigation_manager.js"></script>
<link type="text/css" rel="stylesheet" href="office/functional_departments/navigation/design/navigationRedactStyle.css">
<script type="text/javascript" src="office/subsidiary/js/fullControlElements.func.js"></script>
<script type="text/javascript" src="office/subsidiary/js/collectFormElems.func.js"></script>
<script type="text/javascript" src="office/subsidiary/js/sendAjaxRqst.func.js"></script>
<script type="text/javascript" src="office/functional_departments/navigation/js/advancedControlElements.js"></script>
<script type="text/javascript" src="office/subsidiary/js/textFieldsManager.plugin.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var options={};
	options.control={};
	options.control.panelId ="action_menu";
	options.control.overburdenId="overlay_content";
	options.control.cancelButtonId="cancel_action";
	options.control.callMenuClass="managment_icon";
	options.control.elemClass="navigation_elem";
	options.control.parentElemClass="navigation_row";
	options.control.abortClass="abort_action";
	options.control.successClass="success_class";
	options.control.wastePrefix=/page_number_/;

	options.messageWindowProps={};
	options.messageWindowProps.startActMsg="Производится отправка данных для сохранения . . .";
	options.messageWindowProps.id="ajax_window";
	options.messageWindowProps.overburdenId="ajax_overburden";
	options.messageWindowProps.closeButId="ajax_close_button";
	options.messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку.";

	options.panelCommand={};
	options.panelCommand.insert_before={};
	options.panelCommand.insert_after={};
	options.panelCommand.change_alias={};
	options.panelCommand.change_page_name={};
	options.panelCommand.link_to_page={};

	options.panelCommand.insert_before.typeAction="before";
	options.panelCommand.insert_before.manipulateClass="insert_before_icon";
	options.panelCommand.insert_before.relatedWindowId="insert_before_dialog";
	options.panelCommand.insert_before.deniedClass="no_remoove";
	options.panelCommand.insert_before.phpFileInc="functional_departments/navigation/executors/replacePage.php";

	options.panelCommand.insert_after.typeAction="after";
	options.panelCommand.insert_after.manipulateClass="insert_after_icon";
	options.panelCommand.insert_after.relatedWindowId="insert_after_dialog";
	options.panelCommand.insert_after.deniedClass="no_remoove";
	options.panelCommand.insert_after.phpFileInc="functional_departments/navigation/executors/replacePage.php";

	options.panelCommand.link_to_page.typeAction="custom";
	options.panelCommand.link_to_page.customFunction=relinkInit;
	options.panelCommand.link_to_page.manipulateClass="link_to_icon";
	options.panelCommand.link_to_page.relatedWindowId="link_to_page_dialog";
	options.panelCommand.link_to_page.deniedClass="no_remoove";
	options.panelCommand.link_to_page.deniedTargetClass="no_link";
	options.panelCommand.link_to_page.phpFileInc="functional_departments/navigation/executors/relinkPage.php";

	options.panelCommand.change_alias.typeAction="custom";
	options.panelCommand.change_alias.customFunction=aliasChangeInit;
	options.panelCommand.change_alias.manipulateClass="page_alias";
	options.panelCommand.change_alias.deniedClass="no_rewrite";
	options.panelCommand.change_alias.relatedWindowId="alias_redact_form";

	options.panelCommand.change_page_name.typeAction="textRedact";
	options.panelCommand.change_page_name.manipulateClass="page_name";
	options.panelCommand.change_page_name.deniedClass="no_rewrite";
	options.panelCommand.change_page_name.relatedWindowId="page_name_redact_form";

	fullControlElements(options);

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
	
	$("#page_name_redact_form input[type=text]").textFieldsManager(fieldSettings1);
	$("#alias_redact_form input[type=text]").textFieldsManager(fieldSettings2);
	
	$("body").on('click','.slide_down_childrens',function(){
		$(this).attr('class','slide_up_childrens');
		$(this).siblings(".navigation_row").css("display","block");
	});
	$("body").on('click','.slide_up_childrens',function(){
		$(this).attr('class','slide_down_childrens');
		$(this).siblings(".navigation_row").css("display","none");
	});
});
</script>