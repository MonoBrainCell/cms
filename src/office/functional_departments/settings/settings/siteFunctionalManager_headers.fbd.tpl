<script type="text/javascript" src="office/jq_libs/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="office/specials/jss/attachElemToWindow.plugin.js"></script>
<script type="text/javascript" src="office/specials/jss/navigation_manager.js"></script>
<link type="text/css" rel="stylesheet" href="office/functional_departments/ad/design/redact_banner_style.css">
<link type="text/css" rel="stylesheet" href="office/functional_departments/ad/design/custom_styles.css">
<script type="text/javascript" src="office/subsidiary/js/sendAjaxRqst.func.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("form[target=ajax]").submit(function(){ return false; });
	$("input.site_engine_manager").change(function(){
		var confirmAction=confirm("Уверены, что хотите изменить рабочее состояние сайта?");
		if (confirmAction===true){
			var datas={};
			datas.site_main_engine=$(this).val();
			datas.submit_action="yes";
			datas.phpFileInc="functional_departments/settings/executors/saveSiteEngineStatus.php";
			
			var messageWindowProps={};
			messageWindowProps.startActMsg="Производится отправка данных для сохранения . . .";
			messageWindowProps.id="ajax_window";
			messageWindowProps.overburdenId="ajax_overburden";
			messageWindowProps.closeButId="ajax_close_button";
			messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку.";
			
			sendAjaxRqst(datas,messageWindowProps,false,false);
		}
	});
	$("input.native_functions_engine").change(function(){
		var confirmAction=confirm("Уверены, что хотите изменить рабочее состояние функционала?");
		if (confirmAction===true){
			var datas={};
			datas.function_name=$(this).attr("name");
			datas.function_value=$(this).val();
			datas.submit_action="yes";
			datas.phpFileInc="functional_departments/settings/executors/saveNativeFuncStatus.php";
			
			var messageWindowProps={};
			messageWindowProps.startActMsg="Производится отправка данных для сохранения . . .";
			messageWindowProps.id="ajax_window";
			messageWindowProps.overburdenId="ajax_overburden";
			messageWindowProps.closeButId="ajax_close_button";
			messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку.";
			
			sendAjaxRqst(datas,messageWindowProps,false,false);
		}
	});
	$("input.enclaves_engines").change(function(){
		var confirmAction=confirm("Уверены, что хотите изменить рабочее состояние функционала?");
		if (confirmAction===true){
			var datas={};
			datas.function_name=$(this).attr("name");
			datas.function_value=$(this).val();
			datas.function_desc=$(this).parents('fieldset').children('legend').text();
			datas.submit_action="yes";
			datas.phpFileInc="functional_departments/settings/executors/saveEnclaveStatus.php";
			
			var messageWindowProps={};
			messageWindowProps.startActMsg="Производится отправка данных для сохранения . . .";
			messageWindowProps.id="ajax_window";
			messageWindowProps.overburdenId="ajax_overburden";
			messageWindowProps.closeButId="ajax_close_button";
			messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку.";
			
			sendAjaxRqst(datas,messageWindowProps,false,false);
		}
	});
});
</script>
