<script type="text/javascript" src="office/jq_libs/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="office/specials/jss/attachElemToWindow.plugin.js"></script>
<script type="text/javascript" src="office/specials/jss/navigation_manager.js"></script>
<link type="text/css" rel="stylesheet" href="office/functional_departments/navigation/design/navigationTuneStyle.css">
<script type="text/javascript" src="office/subsidiary/js/collectFormElems.func.js"></script>
<script type="text/javascript" src="office/subsidiary/js/sendAjaxRqst.func.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	messageWindowProps={};
	messageWindowProps.startActMsg="Производится отправка данных для сохранения . . .";
	messageWindowProps.id="ajax_window";
	messageWindowProps.overburdenId="ajax_overburden";
	messageWindowProps.closeButId="ajax_close_button";
	messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку.";
	$("form[name=tune_widgets]").submit(function(){
			return false;	
		});
		$("form[name=tune_widgets] input[type=submit]").click(function(){
			var confirmAction=confirm("Хотите сохранить изменения?");
			if (confirmAction===true){
				var datas=collectFormElems("tune_widgets");
				datas.phpFileInc=$(this).parents("form").attr("action");
				sendAjaxRqst(datas,messageWindowProps,false,false);
			}
		});
});
</script>
 