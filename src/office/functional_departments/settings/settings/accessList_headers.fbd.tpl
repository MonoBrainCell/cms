<script type="text/javascript" src="office/jq_libs/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="office/specials/jss/attachElemToWindow.plugin.js"></script>
<script type="text/javascript" src="office/specials/jss/navigation_manager.js"></script>
<script type="text/javascript" src="office/subsidiary/js/sendAjaxRqst.func.js"></script>
<link type="text/css" rel="stylesheet" href="office/functional_departments/contentEdit/design/style.css">
<link type="text/css" rel="stylesheet" href="office/functional_departments/contentEdit/design/custom_styles.css">
<script type="text/javascript">
$(document).ready(function(){
	$('.remove_datas').click(function(){
		var pageName=$(this).parent().siblings(".page_name").children("em").text();
		var deleteAgree=prompt("Вы уверены, что хотите удалить аккаунт '"+pageName+"'? Введите пароль Управляющего сайтом, чтобы подтвердить права и намерение удалить аккаунт.");
		if (deleteAgree!==null){
			var pageNumber=$(this).attr('id');
			pageNumber=pageNumber.replace(/page_number_/,"");
			var datas={};
			datas.pageNum=pageNumber;
			datas.submitBut='Agree to delete';
			datas.password=deleteAgree;
			datas.phpFileInc="functional_departments/settings/executors/deleteAccess.php";
			
			var messageWindowProps={};
			messageWindowProps.startActMsg="Производится отправка данных для сохранения . . .";
			messageWindowProps.id="ajax_window";
			messageWindowProps.overburdenId="ajax_overburden";
			messageWindowProps.closeButId="ajax_close_button";
			messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку.";
			
			sendAjaxRqst(datas,messageWindowProps,false,false);
		}
		else {
			return false;
		}
	});
});
</script>
<style type="text/css">
.redact_login {
	background: transparent url(office/functional_departments/settings/design/images/login_but.png) center center no-repeat;
}
.redact_password {
	background: transparent url(office/functional_departments/settings/design/images/password_but.png) center center no-repeat;
}
.redact_access_type {
	background: transparent url(office/functional_departments/settings/design/images/admission_but.png) center center no-repeat;
}
.element_cell {padding-bottom: 50px;}
</style>