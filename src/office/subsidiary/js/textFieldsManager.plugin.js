// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
/*
options['errorType'] - тип ошибки: lengthW, symbolsW, dummyW (вместо errorType);
options.errorType.symbsToSearch - шаблон, который будет распознаваться у каждого из полей
options.errorType.alertElemSelector - селектор контейнера для вывода ошибки
options.errorType.alertText - сообщение, которое следует отобразить
*/
(function($){
	jQuery.fn.textFieldsManager=function(options){
		var options = $.extend({},options);
		var addHandlers=function(){
			var elem=$(this);
			totalFunction(elem);
			$(elem).focusin(function(){
				totalFunction(elem);
			});
			$(elem).focusout(function(){
				totalFunction(elem);
			});
			$(elem).keyup(function(){
				totalFunction(elem);
			});
			$(window).mousemove(function(){
				totalFunction(elem);
			});
			$(window).scroll(function(){
				totalFunction(elem);
			});
		}
				
		function lengthMessage(elem,selector){
			if ($(elem).siblings(elem,selector).length>0){
				var symbsLenghtBorder=parseInt($(elem).attr('maxlength'),10);
				var symbsAllreadyExists=parseInt($(elem).val().length,10);
				var symbsLeft=symbsLenghtBorder-symbsAllreadyExists;
				$(elem).siblings(selector).text("Осталось символов:"+symbsLeft);
			}
		}
		function forbSymbsMessage(elem,selector,pattern,text){
			if ($(elem).siblings(selector).length>0){
				var textContent=$(elem).val();
				if (textContent!="" && textContent.match(pattern)!=null){
					$(elem).siblings(selector).html(text);
				}
				else {
					$(elem).siblings(selector).html("");
				}
			}
		}
		function dummySymbsMessage(elem,selector,pattern,text){
			if ($(elem).siblings(selector).length>0){
				var textContent=$(elem).val();
				if (textContent!="" && textContent.match(pattern)==null){
					$(elem).siblings(selector).html(text);
				}
				else {
					$(elem).siblings(selector).html("");
				}
			}
		}
		
		function totalFunction(elem){
			if (options.lengthW!==undefined && options.lengthW.alertElemSelector!==undefined){
				lengthMessage(elem,options.lengthW.alertElemSelector);
			}
			if (
			options.symbolsW!==undefined
			&& options.symbolsW.symbsToSearch!=undefined
			&& options.symbolsW.alertElemSelector!=undefined
			&& options.symbolsW.alertText!=undefined
			){
				forbSymbsMessage(elem,options.symbolsW.alertElemSelector,options.symbolsW.symbsToSearch,options.symbolsW.alertText);
			}
			if (
			options.dummyW!==undefined
			&& options.dummyW.symbsToSearch!=undefined
			&& options.dummyW.alertElemSelector!=undefined
			&& options.dummyW.alertText!=undefined
			){
				forbSymbsMessage(elem,options.dummyW.alertElemSelector,options.dummyW.symbsToSearch,options.dummyW.alertText);
			}
		}
		
		return this.each(addHandlers);
	};
})(jQuery);