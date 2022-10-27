<script type="text/javascript" src="office/jq_libs/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="office/specials/jss/attachElemToWindow.plugin.js"></script>
<script type="text/javascript" src="office/specials/jss/navigation_manager.js"></script>
<link type='text/css' rel='stylesheet' href='office/functional_departments/navigation/design/startPageDesign.css'>

<link type="text/css" rel="stylesheet" href="office/functional_departments/contentEdit/design/redact_page_style.css">
<link type="text/css" rel="stylesheet" href="office/functional_departments/contentEdit/design/custom_styles.css">

<script type="text/javascript" src="office/subsidiary/js/sendAjaxRqst.func.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#restoreSitemap").click(function(){
		var confirmation=confirm("Уверены что хотите полностью перезаписать файл sitemap.xml?");
		if (confirmation===true){
			datas={};
			datas.phpFileInc="functional_departments/settings/executors/rewriteSitemap.php";
			datas.action_agree="yes";
			datas.from_admin_page="1";
			
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