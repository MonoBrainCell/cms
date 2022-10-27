//Specification. Start
var orderFunc=false;
var totalPrefix="Итого: ";
var totalPostfix="&nbsp;руб.";
var calcList={};
calcList.factors={};
calcList.factors.quantity=400;
calcList.values={};
calcList.values.item_type=[];
calcList.values.item_type['item1']=950;
calcList.values.item_type['item2']=3100;
calcList.values.item_type['item3']=1200;
calcList.values.item_type['item4']=2250;
calcList.values.item_type['item5']=1900;
calcList.values.quantity=true;
calcList.values.option1=[];
calcList.values.option1['adv_option1']=2500;
calcList.values.option2=[];
calcList.values.option2['adv_option2']=300;
calcList.values.option3=[];
calcList.values.option3['adv_option3']=10000;

function calculator(){
	var MathMem=calculatorInit();
	
	var total=MathMem.item_type+MathMem.option1+MathMem.option2+MathMem.option3+MathMem.quantity;
	
	return total;
}
//-- Specification. End
function calculatorInit(){
	var datas=collectFormElems('cost_engine');
	var mems=[];
	for (i in calcList.values){
		if (datas[i]!==undefined) {
			mems[i]=getMathMembers(datas[i][0],i);
		}
		else {
			mems[i]=0;
		}
	}
	return mems;
}

function getMathMembers(data,key){
	if (data!=""){
			if (calcList.values[key]!==true){
				for (z in calcList.values[key]){
					if (data==z){
						var value=calcList.values[key][z];
						break;
					}
				}
			}
			else {
				if ($('[name='+key+']').val().match(/[^0-9\.]/)!=null){
					var value=0;
				}
				else {
					if ($('[name='+key+']').val().match(/[^\.]/)!=null){
						var value=parseFloat($('[name='+key+']').val(),10);
					}
					else {
						var value=parseInt($('[name='+key+']').val(),10);
					}
				}
			}
			if (calcList.factors[key]!==undefined){
				value=value*calcList.factors[key];
			}
		}
		else {
			var value=0;
		}
		return value;
}

function calculatorResulting(){
	var total=calculator();
	$("#total_value").html(totalPrefix+total+totalPostfix);
}

function trackFieldChanges() {
	for (i in calcList.values){
		$('[name='+i+']').change(function(){calculatorResulting();});
	}
}
function viewCurrentValue(){
	for (i in calcList.values){
		$('[name='+i+']').change(function(){
			var value=getMathMembers($(this).val(),$(this).attr('name'));
			$("#current_value_viewer").remove();
			$(this).before('<span id="current_value_viewer">'+value+totalPostfix+'</span>');
		});
	}
}
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
	fieldSettings.syntaxis.compare.phone=/[^0-9\+\-\(\)]/;
	fieldSettings.syntaxis.compare.numeral=/[^0-9]/;
	fieldSettings.syntaxis.compare.numfloat=/[^0-9\.]/;
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
	if (orderFunc===false){
		$("#hidden_form_part").css('display','none');
		$('form[target=ajax]').submit(function(){return false;});
		$('form[target=ajax] input[type=submit]').submit(function(){return false;});
	}
	else {
		rechargeJs("jq_libs/sendAjaxRqst.func.js",tuneSubmitAction);
	}
calculatorResulting();
trackFieldChanges();
viewCurrentValue();
});