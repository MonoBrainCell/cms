//options={};
//options.lengthW={};
//options.lengthW.alertSelector="...";
//options.syntaxis={};
//options.syntaxis.alertSelector="...";
//options.syntaxis.alertText="...";
//options.syntaxis.compare={};
//options.syntaxis.compare.str_match_name(random)=[];
//options.patternStr.alertSelector="...";
//options.patternStr.alertText="...";
//options.patternStr.compare={};
//options.patternStr.compare.str_match_name(random)=[];
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
				for (i in pattern){
					if ($(elem).hasClass('check_syntaxis_'+i)===true){
						if (textContent!="" && textContent.match(pattern[i])!=null){
							$(elem).siblings(selector).html(text);
							break;
						}
						else {
							$(elem).siblings(selector).html("");
						}
					}
				}
			}
		}
		function dummySymbsMessage(elem,selector,pattern,text){
			if ($(elem).siblings(selector).length>0){
				var textContent=$(elem).val();
				for (i in pattern){
					if ($(elem).hasClass('check_patternStr_'+i)===true){
						if (textContent!="" && textContent.match(pattern[i])==null){
							$(elem).siblings(selector).html(text);
						}
						else {
							$(elem).siblings(selector).html("");
						}
					}
				}
			}
		}
		
		function totalFunction(elem){
			if (options.lengthW!==undefined && options.lengthW.alertElemSelector!==undefined){
				lengthMessage(elem,options.lengthW.alertElemSelector);
			}
			if (
			options.syntaxis!==undefined
			&& options.syntaxis.compare!=undefined
			&& options.syntaxis.alertSelector!=undefined
			&& options.syntaxis.alertText!=undefined
			){
				forbSymbsMessage(elem,options.syntaxis.alertSelector,options.syntaxis.compare,options.syntaxis.alertText);
			}
			if (
			options.patternStr!==undefined
			&& options.patternStr.compare!=undefined
			&& options.patternStr.alertSelector!=undefined
			&& options.patternStr.alertText!=undefined
			){
				dummySymbsMessage(elem,options.patternStr.alertSelector,options.patternStr.compare,options.patternStr.alertText);
			}
		}
		
		return this.each(addHandlers);
	};
})(jQuery);