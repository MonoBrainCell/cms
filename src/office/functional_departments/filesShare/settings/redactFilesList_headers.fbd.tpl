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
<script type="text/javascript">
$(document).ready(function(){
	$("form[target=ajax]").submit(function(){ return false; });
	$("form[target=ajax] input[type=submit]").click(function(){
		var formName=$(this).parents("form").attr("name");
		if ($(".gallery_element a").length<1){
			alert("Вы не добавили ни одного файла в список для скачивания");
			return false;
		}
		var filesStr="";
		var descriptions="";
		var filesArray=$("a.preview_image_info");
		for (i=0,z=filesArray.length;i<z;i++){
			if ($(filesArray[i]).filter("[rel]").length>0){
				if (filesStr==""){
					filesStr+=$(filesArray[i]).text();
				}
				else {
					filesStr+="::"+$(filesArray[i]).text();
				}
			}
			else {
				if (filesStr==""){
					filesStr+="0";
				}
				else {
					filesStr+="::0";
				}
			}
		}
				
		var datas=collectFormElems(formName);
		var fileNameStr="";
		for(i in datas.file_name){
			if (datas.file_name[i]==""){
				if (fileNameStr==""){
					fileNameStr+="0";
				}
				else {
					fileNameStr+="::0";
				}
			}
			else {
				if (fileNameStr==""){
					fileNameStr+=datas.file_name[i];
				}
				else {
					fileNameStr+="::"+datas.file_name[i];
				}
			}
		}
		delete datas.file_name;
		datas.file_name=fileNameStr;
		datas.files_list=filesStr;

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
	
	options.panelCommand.insert_after.typeAction="after";
	options.panelCommand.insert_after.manipulateClass="after_this";
	options.panelCommand.insert_after.relatedWindowId="insert_after_dialog";
	options.panelCommand.insert_after.deniedClass="no_remoove";
	
	options.panelCommand.remove_elem.typeAction="remove";
	options.panelCommand.remove_elem.manipulateClass="remove_elem";
	options.panelCommand.remove_elem.relatedWindowId="remove_elem_dialog";
	options.panelCommand.remove_elem.deniedClass="no_remoove";
	
	fullControlElements(options);
	
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
	
	$("body").on('click',"#more_images",function(){
		var datas={};
		datas.phpFileInc="functional_departments/filesShare/executors/filesListViewer.php";
		
		attachingParams.fieldElem="";
		attachingParams.htmlTemplate='<div class="gallery_element"><span class="replace_button"></span>	<h3>Название файла:</h3><div class="form_elem"><span class="block_with_advice"><span class="view_advice" title="Пояснение"></span><span class="advice">Содержание данного поля будет использовано для отображения названия файла, для удобства поиска нужного файла в списке скачивания посетителями сайта.<br><span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span></span></span><input id="{added_field_id}" type="text" name="file_name" maxlength="50"><span class="warning_sintaxis"></span><span class="warning_lenght">Осталось символов: 50</span></div>	<div class="preview_image"><a class="preview_image_info {added_anchor_class}" href="{path_insert}" rel="" title="Файл: {path_insert}" target="windowViewer">{path_insert}</a><span class="manipulate_buttons add_change_button" title="Изменить файл"></span></div><div style="width:auto;height:1px;margin:0;padding:0;overflow:hidden;clear:both;"></div></div>';
		attachingParams.addedFieldId="added_field";
		attachingParams.addedAnchorClass="added_anchor";
		attachingParams.newElem=true;
		attachingParams.addNewElemButId=$(this).attr("id");
		attachingParams.fieldSettings=fieldSettings1;
		
		sendAjaxRqst(datas,messageWindowProps,attachEventsToBoxOfFiles,attachingParams);
	});

	$("body").on('click',".add_change_button",function(){
		var datas={};
		datas.phpFileInc="functional_departments/filesShare/executors/filesListViewer.php";
		
		attachingParams.fieldElem=$(this).siblings("a");
		
		attachingParams.attrToChange={};
		attachingParams.attrToChange.title="Файл: {path_insert}";
		attachingParams.attrToChange.href="{path_insert}";
		
		delete attachingParams.newElem;
		sendAjaxRqst(datas,messageWindowProps,attachEventsToBoxOfFiles,attachingParams);
	});
});
</script>

