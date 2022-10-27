// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
function sendAjaxRqst(formDatas,messageWindowProps,callBackFunc,callBackFuncParams){
//messageWindowProps:
//messageWindowProps.startActMsg="Текст в качестве прелоадера"
//messageWindowProps.id="Селектор окна, в котором появится результат обработки скрипта"
//messageWindowProps.overburdenId="Селектор слоя перекрывающего основной контент страницы"
//messageWindowProps.closeButId="Селектор кнопки, которая скроет окно и перекрывающий слой"
//messageWindowProps.errorMsg="Текст оповещающий об ошибке ajax-запроса"
function attachCloseButHandler(){
	$("#"+messageWindowProps.closeButId).click(function(){
		$("#"+messageWindowProps.closeButId).remove();
		$("#"+messageWindowProps.id).remove();
		$("#"+messageWindowProps.overburdenId).remove();
	});
}
function reCalcParams() {
	var AjaxWindowHeight=parseInt($("#"+messageWindowProps.id).height(),10);
	var windowHeight=parseInt($(window).height(),10);
	var marginTopFromBorder=(windowHeight-AjaxWindowHeight)/2;
	$("#"+messageWindowProps.id).css({marginTop:marginTopFromBorder+"px"});
	$("#"+messageWindowProps.overburdenId).css({height:windowHeight+"px"});
}
$.ajax("office/execAjaxRqst.php",{
	type:"post",
	data:formDatas,
	beforeSend:function(){
		$("body").append("<div id='"+messageWindowProps.overburdenId+"'></div><div id='"+messageWindowProps.id+"'></div>");
		$("#"+messageWindowProps.id).text(messageWindowProps.startActMsg);
		reCalcParams();
	},
	error: function(){
		$("#"+messageWindowProps.id).html(messageWindowProps.errorMsg+"<br>");
		$("#"+messageWindowProps.id).append("<span id='"+messageWindowProps.closeButId+"'>Вернуться к редактированию страницы</span>");
		reCalcParams();
		attachCloseButHandler();
	},
	success: function(result){
		$("#"+messageWindowProps.id).html(result);
		reCalcParams();
		attachCloseButHandler();
		
		if (callBackFunc!==false){
			if (callBackFuncParams!==false){
				callBackFunc(callBackFuncParams);
			}
			else {
				callBackFunc();
			}
		}
		
	}
});
}