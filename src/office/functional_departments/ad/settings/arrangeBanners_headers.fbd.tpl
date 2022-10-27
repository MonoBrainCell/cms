<script type="text/javascript" src="office/jq_libs/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="office/specials/jss/attachElemToWindow.plugin.js"></script>
<script type="text/javascript" src="office/specials/jss/navigation_manager.js"></script>
<link type="text/css" rel="stylesheet" href="office/functional_departments/ad/design/arrangeBannersStyle.css">
<script type="text/javascript" src="office/subsidiary/js/fullControlElements.func.js"></script>
<script type="text/javascript" src="office/subsidiary/js/collectFormElems.func.js"></script>
<script type="text/javascript" src="office/subsidiary/js/sendAjaxRqst.func.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var options={};
	options.control={};
	options.control.panelId ="action_menu";
	options.control.overburdenId="overlay_content";
	options.control.cancelButtonId="cancel_action";
	options.control.callMenuClass="managment_icon";
	options.control.elemClass="banner_elem";
	options.control.parentElemClass="banner_row";
	options.control.abortClass="abort_action";
	options.control.successClass="success_class";
	options.control.wastePrefix=/banner_number_/;

	options.messageWindowProps={};
	options.messageWindowProps.startActMsg="Производится отправка данных для сохранения . . .";
	options.messageWindowProps.id="ajax_window";
	options.messageWindowProps.overburdenId="ajax_overburden";
	options.messageWindowProps.closeButId="ajax_close_button";
	options.messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку.";

	options.panelCommand={};
	options.panelCommand.insert_before={};
	options.panelCommand.insert_after={};

	options.panelCommand.insert_before.typeAction="before";
	options.panelCommand.insert_before.manipulateClass="insert_before_icon";
	options.panelCommand.insert_before.relatedWindowId="insert_before_dialog";
	options.panelCommand.insert_before.deniedClass="no_remoove";
	options.panelCommand.insert_before.phpFileInc="functional_departments/ad/executors/replaceBanner.php";

	options.panelCommand.insert_after.typeAction="after";
	options.panelCommand.insert_after.manipulateClass="insert_after_icon";
	options.panelCommand.insert_after.relatedWindowId="insert_after_dialog";
	options.panelCommand.insert_after.deniedClass="no_remoove";
	options.panelCommand.insert_after.phpFileInc="functional_departments/ad/executors/replaceBanner.php";

	fullControlElements(options);
});
</script>