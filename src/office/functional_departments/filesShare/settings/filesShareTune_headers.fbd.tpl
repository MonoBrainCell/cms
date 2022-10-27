<script type="text/javascript" src="office/jq_libs/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="office/specials/jss/attachElemToWindow.plugin.js"></script>
<script type="text/javascript" src="office/specials/jss/navigation_manager.js"></script>
<link type="text/css" rel="stylesheet" href="office/functional_departments/navigation/design/navigationTuneStyle.css">
<link type="text/css" rel="stylesheet" href="office/functional_departments/ad/design/custom_styles.css">
<script type="text/javascript" src="office/subsidiary/js/collectFormElems.func.js"></script>
<script type="text/javascript" src="office/subsidiary/js/sendAjaxRqst.func.js"></script>
<script type="text/javascript" src="office/subsidiary/js/attachEventsToBoxOfFiles.func.js"></script>
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
		$("#view_available_css").click(function(){
			var datas={};
			datas['phpFileInc']="functional_departments/filesShare/executors/filesViewer.php";
			datas.direction="css";
			var messageWindowProps={};
			messageWindowProps.startActMsg="Производится поиск доступных css-файлов . . .";
			messageWindowProps.id="viewer_images_window";
			messageWindowProps.overburdenId="ajax_overburden";
			messageWindowProps.closeButId="ajax_close_button";
			messageWindowProps.errorMsg="Простите, произошла ошибка во время поиска. Обновите страницу в браузере и повторите попытку.";
			
			var attachingParams={};
			attachingParams.pathConteinerSelector=".path_container";
			attachingParams.closeButId="ajax_close_button";
			
			sendAjaxRqst(datas,messageWindowProps,attachEventsToBoxOfFiles,attachingParams);
		});
		$("#view_available_js").click(function(){
			var datas={};
			datas['phpFileInc']="functional_departments/filesShare/executors/filesViewer.php";
			datas.direction="javascript";
			var messageWindowProps={};
			messageWindowProps.startActMsg="Производится поиск доступных javascript-файлов . . .";
			messageWindowProps.id="viewer_images_window";
			messageWindowProps.overburdenId="ajax_overburden";
			messageWindowProps.closeButId="ajax_close_button";
			messageWindowProps.errorMsg="Простите, произошла ошибка во время поиска. Обновите страницу в браузере и повторите попытку.";
			
			var attachingParams={};
			attachingParams.pathConteinerSelector=".path_container";
			attachingParams.closeButId="ajax_close_button";
			
			sendAjaxRqst(datas,messageWindowProps,attachEventsToBoxOfFiles,attachingParams);
		});
});
</script>
 <style type="text/css">
#view_available_css,#view_available_js{
	display: inline-block;
	margin: 5px; padding: 5px;
	color:#333;
	text-decoration: none;
	font-size: 18px;
	border: 2px outset #900;
	border-radius:7px;
	background-color: #fff;
	cursor: pointer;
}
#view_available_css,#view_available_js {
	position: absolute;
	width: 150px; right:0;
	margin: 40px 0;
	text-align: center;
	font-size: 14px;
}
#view_available_js {margin-top: 160px;}
#view_available_css:hover, #view_available_js:hover{
	border-style: ridge; background: #fff none;
}
</style>