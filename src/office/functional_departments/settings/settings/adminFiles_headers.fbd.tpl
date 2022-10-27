<script type="text/javascript" src="office/jq_libs/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="office/specials/jss/attachElemToWindow.plugin.js"></script>
<script type="text/javascript" src="office/specials/jss/navigation_manager.js"></script>
<link type="text/css" rel="stylesheet" href="office/functional_departments/ad/design/redact_banner_style.css">
<link type="text/css" rel="stylesheet" href="office/functional_departments/ad/design/custom_styles.css">
<link type="text/css" rel="stylesheet" href="office/functional_departments/navigation/design/navigationRedactStyle.css">
<link type="text/css" rel="stylesheet" href="office/functional_departments/imagesGallery/design/style.css">
<script type="text/javascript" src="office/subsidiary/js/fullControlElements.func.js"></script>
<script type="text/javascript" src="office/subsidiary/js/collectFormElems.func.js"></script>
<script type="text/javascript" src="office/subsidiary/js/sendAjaxRqst.func.js"></script>
<script type="text/javascript" src="office/functional_departments/settings/js/adminFilesControl.func.js"></script>
<style type="text/css">
#main_content .gallery_element select, .modal_form select {
	width:415px;
	margin-left: 0;
}
.modal_form select {
	font-size: 16px;
	border-color:#002560;
	border-style: groove;
	border-radius: 5px;
}
.modal_form fieldset {
	border-color:#002560;
	border-style: groove;
	border-radius: 5px;
}
.modal_form fieldset legend {
	padding:5px; text-align: left;
	color:#004580;
	font: 22px Verdana,Arial,Helvetica,sans-serif;
	border: 2px #002560 groove; border-radius: 8px;
	background-color: #fff;
}
#main_content .gallery_element select optgroup, .modal_form select optgroup  {
	background-color: #dfd;text-align: center;
}
#main_content .gallery_element select optgroup option, .modal_form select optgroup option{
	background-color: #fff;
	padding:3px;text-align: left;
}
.form_elem select, .modal_form select {
	display:inline-block;
	width: 400px;
}
.form_elem select optgroup, .modal_form select optgroup {
	background-color: #dfd;text-align: center;
}
.form_elem select optgroup option, .modal_form select optgroup  option{
	background-color: #fff;
	padding:3px;text-align: left;
}
</style>
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
	options.control.abortClass="error_class";
	options.control.successClass="success_class";
	options.control.wastePrefix=/page_number_/;

	options.messageWindowProps={};
	options.messageWindowProps.startActMsg="Производится отправка данных для сохранения . . .";
	options.messageWindowProps.id="ajax_window";
	options.messageWindowProps.overburdenId="ajax_overburden";
	options.messageWindowProps.closeButId="ajax_close_button";
	options.messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку.";
	
	options.panelCommand={};
	options.panelCommand.rename_elem={};
	options.panelCommand.rename_elem.typeAction="custom";
	options.panelCommand.rename_elem.customFunction=renameFileInit;
	options.panelCommand.rename_elem.manipulateClass="insert_before_icon";
	options.panelCommand.rename_elem.relatedWindowId="rename_elem_redact_form";
	options.panelCommand.rename_elem.startActionAddress="functional_departments/settings/executors/sameNameSeeker.php";
	options.panelCommand.rename_elem.secondActionAddress="functional_departments/settings/executors/filesWinch.php";
	options.panelCommand.rename_elem.continueButtonId="continue_button";
	options.panelCommand.rename_elem.convertedNameConteinerId="hidden_message";
	options.panelCommand.rename_elem.deniedClass="no_remoove";
	
	options.panelCommand.copy_elem={};
	options.panelCommand.copy_elem.typeAction="custom";
	options.panelCommand.copy_elem.customFunction=standOutFileInit;
	options.panelCommand.copy_elem.manipulateClass="insert_before_icon";
	options.panelCommand.copy_elem.relatedWindowId="copy_elem_redact_form";
	options.panelCommand.copy_elem.startActionAddress="functional_departments/settings/executors/sameNameSeeker.php";
	options.panelCommand.copy_elem.secondActionAddress="functional_departments/settings/executors/filesStandOut.php";
	options.panelCommand.copy_elem.typeStandOut="copy";
	options.panelCommand.copy_elem.continueButtonId="continue_button";
	options.panelCommand.copy_elem.convertedNameConteinerId="hidden_message";
	options.panelCommand.copy_elem.deniedClass="no_remoove";
	
	options.panelCommand.replace_elem={};
	options.panelCommand.replace_elem.typeAction="custom";
	options.panelCommand.replace_elem.customFunction=standOutFileInit;
	options.panelCommand.replace_elem.manipulateClass="insert_before_icon";
	options.panelCommand.replace_elem.relatedWindowId="replace_elem_redact_form";
	options.panelCommand.replace_elem.startActionAddress="functional_departments/settings/executors/sameNameSeeker.php";
	options.panelCommand.replace_elem.secondActionAddress="functional_departments/settings/executors/filesStandOut.php";
	options.panelCommand.replace_elem.typeStandOut="move";
	options.panelCommand.replace_elem.continueButtonId="continue_button";
	options.panelCommand.replace_elem.convertedNameConteinerId="hidden_message";
	options.panelCommand.replace_elem.deniedClass="no_remoove";
	
	options.panelCommand.remove_elem={};
	options.panelCommand.remove_elem.typeAction="custom";
	options.panelCommand.remove_elem.customFunction=deleteFileInit;
	options.panelCommand.remove_elem.manipulateClass="insert_before_icon";
	options.panelCommand.remove_elem.relatedWindowId="remove_elem_dialog";
	options.panelCommand.remove_elem.typeStandOut="move";
	options.panelCommand.remove_elem.continueButtonId="continue_button";
	options.panelCommand.remove_elem.convertedNameConteinerId="hidden_message";
	options.panelCommand.remove_elem.deniedClass="no_remoove";
	
	fullControlElements(options);
});
</script>
