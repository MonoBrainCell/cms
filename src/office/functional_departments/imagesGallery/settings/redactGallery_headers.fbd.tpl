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

<script type="text/javascript" src="functional/imagesGallery/rebox/jquery-litelighter.js"></script>
<script type="text/javascript" src="functional/imagesGallery/rebox/jquery-rebox.js"></script>
<link type="text/css" rel="stylesheet" href="functional/imagesGallery/rebox/jquery-rebox.css">
<script type='text/javascript'>
$(document).ready(function(){
	$("body").rebox({
		selector:"a[data-gallery^='rebox_']",
		prev:"&lArr;",
		next:"&rArr;",
		close:"X",
		captionAttr:'data-gallery-desc'
	});
});
</script>
<script type="text/javascript">
$(document).ready(function(){
	$("form[target=ajax]").submit(function(){ return false; });
	$("form[target=ajax] input[type=submit]").click(function(){
		var formName=$(this).parents("form").attr("name");
		if ($("a[data-gallery^='rebox_']").length<1){
			alert("Вы не добавили ни одного изображения в галерею");
			return false;
		}
		var previewImagesStr="";
		var originalImagesStr="";
		var descriptions="";
		var previewImages=$("a.preview_image_info");
		var originalImages=$("a.original_image_info");
		for (i=0,z=previewImages.length;i<z;i++){
			if ($(previewImages[i]).filter("[data-gallery^='rebox_']").length>0){
				if (previewImagesStr==""){
					previewImagesStr+=$(previewImages[i]).text();
				}
				else {
					previewImagesStr+="::"+$(previewImages[i]).text();
				}
			}
			else {
				if (previewImagesStr==""){
					previewImagesStr+="0";
				}
				else {
					previewImagesStr+="::0";
				}
			}
		}
		//datas
		for (i=0,z=previewImages.length;i<z;i++){
			if ($(originalImages[i]).filter("[data-gallery^='rebox_']").length>0){
				if (originalImagesStr==""){
					originalImagesStr+=$(originalImages[i]).text();
				}
				else {
					originalImagesStr+="::"+$(originalImages[i]).text();
				}
			}
			else {
				if (originalImagesStr==""){
					originalImagesStr+="0";
				}
				else {
					originalImagesStr+="::0";
				}
			}
		}
		
		var datas=collectFormElems(formName);
		var groupNameStr="";
		for(i in datas.group_name){
			if (datas.group_name[i]==""){
				if (groupNameStr==""){
					groupNameStr+="0";
				}
				else {
					groupNameStr+="::0";
				}
			}
			else {
				if (groupNameStr==""){
					groupNameStr+=datas.group_name[i];
				}
				else {
					groupNameStr+="::"+datas.group_name[i];
				}
			}
		}
		delete datas.group_name;
		datas.group_name=groupNameStr;
		datas.preview_images=previewImagesStr;
		datas.original_images=originalImagesStr;
		datas.phpFileInc=$(this).parents("form").attr("action");
		var messageWindowProps={};
		messageWindowProps.startActMsg="Производится отправка данных для сохранения . . .";
		messageWindowProps.id="ajax_window";
		messageWindowProps.overburdenId="ajax_overburden";
		messageWindowProps.closeButId="ajax_close_button";
		messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку.";
		
		sendAjaxRqst(datas,messageWindowProps,false,false);
		
	});

	//---
	var fieldSettings1={};
	fieldSettings1.lengthW={};
	fieldSettings1.lengthW.alertElemSelector=".warning_lenght";
	fieldSettings1.symbolsW={};
	fieldSettings1.symbolsW.symbsToSearch=/[\&\^\~\`\@\#\'\"\<\>]/;
	fieldSettings1.symbolsW.alertElemSelector=".warning_sintaxis";
	fieldSettings1.symbolsW.alertText="Введён запрещённый символ &frasl; ";
	
	$("input[type=text]").textFieldsManager(fieldSettings1);
	
	var options={};
	options.control={};
	options.control.panelId ="action_menu";
	options.control.overburdenId="overlay_content";
	options.control.cancelButtonId="cancel_action";
	options.control.callMenuClass="replace_button";
	options.control.elemClass="gallery_element";
	options.control.abortClass="abort_action";
	options.control.fieldSettings=fieldSettings1;
	
	options.panelCommand={};
	options.panelCommand.insert_before={};
	options.panelCommand.insert_after={};
	options.panelCommand.remove_elem={};
	
	options.panelCommand.insert_before.typeAction="before";
	options.panelCommand.insert_before.manipulateClass="before_this";
	options.panelCommand.insert_before.relatedWindowId="insert_before_dialog";
	options.panelCommand.insert_before.deniedClass="no_remoove";
	options.panelCommand.insert_before.galleryMarker=true;
	
	options.panelCommand.insert_after.typeAction="after";
	options.panelCommand.insert_after.manipulateClass="after_this";
	options.panelCommand.insert_after.relatedWindowId="insert_after_dialog";
	options.panelCommand.insert_after.deniedClass="no_remoove";
	options.panelCommand.insert_after.galleryMarker=true;
	
	options.panelCommand.remove_elem.typeAction="remove";
	options.panelCommand.remove_elem.manipulateClass="remove_elem";
	options.panelCommand.remove_elem.relatedWindowId="remove_elem_dialog";
	options.panelCommand.remove_elem.deniedClass="no_remoove";
	
	fullControlElements(options);
	
	$("body").on('click',".remove_button",function(){
		var deleteOptions={};
		deleteOptions.deleteButton=$(this);
		deleteOptions.parentElemClass="gallery_element";
		deleteOptions.removeGroupButtonId=options.panelCommand.remove_elem.manipulateClass;
		deleteImage(deleteOptions);
	});

	var messageWindowProps={};
	messageWindowProps.startActMsg="Производится поиск доступных изображений . . .";
	messageWindowProps.id="viewer_images_window";
	messageWindowProps.overburdenId="ajax_overburden";
	messageWindowProps.closeButId="ajax_close_button";
	messageWindowProps.errorMsg="Простите, произошла ошибка во время поиска. Обновите страницу в браузере и повторите попытку.";
	
	attachingParams={};
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
	
	$("body").on('click',"#more_images",function(){
		var datas={};
		datas.phpFileInc="functional_departments/imagesGallery/executors/imagesListViewer.php";
		datas.imagesFolderName="original";
		
		attachingParams.fieldElem="";
		attachingParams.htmlTemplate='<div class="gallery_element"><span class="replace_button"></span>	<h3>Поясняющий текст:</h3><div class="form_elem"><span class="block_with_advice"><span class="view_advice" title="Пояснение"></span><span class="advice">Содержание данного поля будет использовано для отображения текста, при просмотре изображений исходного размера и, если имеется preview-изображений, то при наведении на него<br><span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span></span></span><input id="{added_field_id}" type="text" name="group_name" maxlength="50"><span class="warning_sintaxis"></span><span class="warning_lenght">Осталось символов: 50</span></div>	<div class="preview_image"><a class="preview_image_info {added_anchor_class}" href="" title="PREVIEW - изображение:"></a><span class="manipulate_buttons add_change_button" title="Добавить / изменить PREVIEW - изображение"></span><span class="manipulate_buttons remove_button" title="Удалить из галереи PREVIEW - изображение"></span></div><div class="original_image"><a class="original_image_info {added_anchor_class}" href="{path_insert}" title="ОРИГИНАЛЬНОЕ изображение: {path_insert}" data-gallery="rebox_0" data-gallery-desc="ОРИГИНАЛЬНОЕ изображение: {path_insert}">{path_insert}</a>		<span class="manipulate_buttons add_change_button" title="Добавить / изменить ОРИГИНАЛЬНОЕ изображение"></span><span class="manipulate_buttons remove_button" title="Удалить из галереи ОРИГИНАЛЬНОЕ изображение"></span></div><div style="width:auto;height:1px;margin:0;padding:0;overflow:hidden;clear:both;"></div></div>';
		attachingParams.addedFieldId="added_field";
		attachingParams.addedAnchorClass="added_anchor";
		attachingParams.newElem=true;
		attachingParams.addNewElemButId=$(this).attr("id");
		attachingParams.fieldSettings=fieldSettings1;
		
		sendAjaxRqst(datas,messageWindowProps,attachEventsToBoxOfFiles,attachingParams);
	});
	
	$("body").on('click',".add_change_button",function(){
		var datas={};
		datas.phpFileInc="functional_departments/imagesGallery/executors/imagesListViewer.php";
		
		attachingParams.fieldElem=$(this).siblings("a");
		
		attachingParams.attrToChange={};
		if ($(attachingParams.fieldElem).attr("class")=="original_image_info"){
			datas.imagesFolderName="original";
			attachingParams.attrToChange.title="ОРИГИНАЛЬНОЕ изображение: {path_insert}";
			attachingParams.attrToChange["data-gallery-desc"]="ОРИГИНАЛЬНОЕ изображение: {path_insert}";
		}
		else {
			datas.imagesFolderName="preview";
			attachingParams.attrToChange.title="PREVIEW - изображение: {path_insert}";
			attachingParams.attrToChange["data-gallery-desc"]="PREVIEW - изображение: {path_insert}";
		}
		attachingParams.attrToChange["data-gallery"]="rebox_0";
		attachingParams.attrToChange.href="{path_insert}";
		
		delete attachingParams.newElem;
		sendAjaxRqst(datas,messageWindowProps,attachEventsToBoxOfFiles,attachingParams);
	});
});
</script>

