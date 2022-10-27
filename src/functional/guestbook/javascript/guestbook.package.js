function rechargeJs(jsFile,custFunc){
	function viewError() {
		alert("Простите, произошла ошибка работы формы. Пожалуйста, обновите страницу и повторите попытку заполнения!");
	}
	$.ajax(jsFile,{
		type: "GET",
		dataType: "script",
		success: function(){
			if (custFunc!==false){
				custFunc();
			}
		},
		error:function (){
			viewError();
		},
	});
}
var tuneFields=function (){
	var fieldSettings={};
	fieldSettings.lengthW={};
	fieldSettings.lengthW.alertElemSelector=".warning_length";
	fieldSettings.syntaxis={};
	fieldSettings.syntaxis.alertSelector=".symbs_warning";
	fieldSettings.syntaxis.alertText="Введён запрещённый символ &frasl; ";
	fieldSettings.syntaxis.compare={};
	fieldSettings.syntaxis.compare.text=/[^0-9а-яА-Яa-zA-Z\-\.\(\)\s]/;
	fieldSettings.syntaxis.compare.email=/[^0-9a-zA-Z\-\.\_\@]/;
	fieldSettings.syntaxis.compare.message=/[^0-9а-яА-Яa-zA-Z\.\,\!\+\-\_\*\(\)\=\:\;\'\"\<\>\?\s]/;
	fieldSettings.patternStr={};
	fieldSettings.patternStr.alertSelector=".pattern_warning";
	fieldSettings.patternStr.alertText="Не соответствует реальному &frasl; ";
	fieldSettings.patternStr.compare={};
	fieldSettings.patternStr.compare.email=/^[a-zA-Z0-9\.\-\_]+\@[a-zA-Z0-9\-]+\.[a-zA-Z]+/;
	$("input[type=text], textarea").textFieldsManager(fieldSettings);
}

var tuneSubmitAction=function (){
	$("form[target=ajax]").submit(function(){return false;});
	$("form[target=ajax] input[type=submit]").click(function(){
		var curForm=$(this).parents('form[target=ajax]');
		var datas=collectFormElems($(curForm).attr('name'));
		datas['phpFileInc']=$(curForm).attr("action");
		
		var messageWindowProps={};
		messageWindowProps.startActMsg="Производится отправка данных для сохранения . . .";
		messageWindowProps.id="ajax_window";
		messageWindowProps.overburdenId="ajax_overburden";
		messageWindowProps.closeButId="ajax_close_button";
		messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку.";
		
		sendAjaxRqst(datas,messageWindowProps,false,false);
	});
}
$(document).ready(function(){
	rechargeJs("jq_libs/textFieldsManager.plugin.js",tuneFields);
	rechargeJs("jq_libs/collectFormElems.func.js",false);
	rechargeJs("jq_libs/sendAjaxRqst.func.js",tuneSubmitAction);

});