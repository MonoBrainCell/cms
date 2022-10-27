<script type="text/javascript" src="office/jq_libs/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="office/specials/jss/attachElemToWindow.plugin.js"></script>
<script type="text/javascript" src="office/specials/jss/navigation_manager.js"></script>
<link type="text/css" rel="stylesheet" href="office/functional_departments/ad/design/redact_banner_style.css">
<link type="text/css" rel="stylesheet" href="office/functional_departments/ad/design/custom_styles.css">
<link type="text/css" rel="stylesheet" href="office/functional_departments/navigation/design/navigationRedactStyle.css">
<link type="text/css" rel="stylesheet" href="office/functional_departments/imagesGallery/design/style.css">
<script type="text/javascript" src="office/subsidiary/js/collectFormElems.func.js"></script>
<script type="text/javascript" src="office/subsidiary/js/sendAjaxRqst.func.js"></script>
<script type="text/javascript" src="office/subsidiary/js/textFieldsManager.plugin.js"></script>
<script type="text/javascript" src="office/subsidiary/js/galleryControl.func.js"></script>
<style type="text/css">
#main_content .gallery_element select {
	width:415px;
	margin-left: 0;
}
#main_content .gallery_element select optgroup {
	background-color: #dfd;text-align: center;
}
#main_content .gallery_element select optgroup option {
	background-color: #fff;
	padding:3px;text-align: left;
}
</style>
<script type="text/javascript">
function prepareToSendDatas(ajaxWindowId){
	if ($("#"+ajaxWindowId).find(".success_class").length>0){
		$("input[name=file]").attr("name","file[]");
		$("select[name=directory]").attr("name","directory[]");
		var inputs=$("input[name='file[]']");
		for (i=0,z=inputs.length;i<z;i++){
			var inputValue=$(inputs[i]).val();
			$(inputs[i]).after("<input type='hidden' name='controlElem[]' value='"+inputValue+"'>");
		}
		$("form[target=ajax]").attr('id','ajax_tmpr_block');
		$("form#ajax_tmpr_block").removeAttr('target');
		$("form#ajax_tmpr_block").attr("action","office/index.php?dep=settings&branch=loadFiles.php");
		$("form#ajax_tmpr_block").unbind('submit');
	}
}
$(document).ready(function(){
	$("body").on('click',".abort_loading_files",function(){
		$("input[name='file[]']").attr("name","file");
		$("input[name='directory[]']").attr("name","directory");
		$("input[name=file]").next("input[type=hidden]").remove();
		$("form#ajax_tmpr_block").attr("target","ajax");
		$("form[target=ajax]").removeAttr('id');
		$("form[target=ajax]").attr("action","functional_departments/settings/executors/checkFilesOnSite.php");
		$("form[target=ajax]").submit(function(){ return false; });
	});
	$("body").on('click',"#continue_button",function(){
		$("form#ajax_tmpr_block").trigger('submit');
	});
	$("form[target=ajax]").submit(function(){ return false; });
	$("form[target=ajax] input[type=submit]").click(function(){
		var formName=$(this).parents("form").attr("name");
		var datas=collectFormElems(formName);
		datas.phpFileInc=$(this).parents("form").attr("action");
		var messageWindowProps={};
		messageWindowProps.startActMsg="Производится отправка данных для сохранения . . .";
		messageWindowProps.id="ajax_window";
		messageWindowProps.overburdenId="ajax_overburden";
		messageWindowProps.closeButId="ajax_close_button";
		messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку.";
		sendAjaxRqst(datas,messageWindowProps,prepareToSendDatas,messageWindowProps.id);
	});

	//---	
	var options={};
	options.control={};
	options.control.panelId ="action_menu";
	options.control.overburdenId="overlay_content";
	options.control.cancelButtonId="cancel_action";
	options.control.callMenuClass="replace_button";
	options.control.elemClass="gallery_element";
	options.control.abortClass="abort_action";
	
	options.panelCommand={};
	options.panelCommand.remove_elem={};
	
	options.panelCommand.remove_elem.typeAction="remove";
	options.panelCommand.remove_elem.manipulateClass="remove_elem";
	options.panelCommand.remove_elem.relatedWindowId="remove_elem_dialog";
	options.panelCommand.remove_elem.deniedClass="no_remoove";
	
	fullControlElements(options);
	
	$("body").on('click',"#more_images",function(){
		var htmlCode=$(this).prev(".gallery_element").html();
		$(this).before("<div class='gallery_element'>"+htmlCode+"</div>");
	});
});
</script>
