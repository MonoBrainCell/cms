// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
/*
sideOfAttach - сторона окна к которой будет прикрепляться элемент, и по которой соответственно будет проверяться его положение;
maxIndent - максимальный отступ (или если он не указан прорабатывается верхняя или нижняя граница соответственно)
underneathBg - изображение для подложки прикрепляемому элементу
locksOnWindowClass - класс который будет добавляться и удаляться при закреплении к окну и возвращени элемента на место
substituteElemClass - класс, который будет присваиваться замещающему элементу
Event:attachElemToWindow.attachingDone	- событие, которое происходит при изменении ситуации с закрепляемым объектом
		*/
(function($){
	jQuery.fn.attachElemToWindow = function(options){
		var options = $.extend({
			sideOfAttach: "top",//top | bottom
			maxIndent:"self",// self | size (in px)
			underneathBg:"none", // url (according html-file disposition)
			locksOnWindowClass:"attach_elem",
			substituteElemClass:"none" // none | class name (if elem have property position has no value absolute)
		}
		,options);
		var attachTracking = function () {
			if (options.maxIndent=="self"){
				if (options.sideOfAttach=="top"){
					options.maxIndent=parseInt($(this).offset().top,10);
				}
				else if (options.sideOfAttach=="bottom"){
					options.maxIndent=parseInt($(this).offset().top,10)+parseInt($(this).outerHeight(),10);
				}
			}
			var elem=$(this);
			$(window).scroll(function(){
				attaching(elem);
			});
			$(window).resize(function(){
				attaching(elem);
			});
		}
		function attaching (elem) {
			if (parseInt($(window).scrollTop(),10)>=options.maxIndent && $(elem).hasClass(options.locksOnWindowClass)===false){
				$(elem).addClass(options.locksOnWindowClass);
				if (options.substituteElemClass!="none"){
					$(elem).after("<span class='"+substituteElemClass+"' style='"+parseInt($(elem).outerHeight(),10)+"px'>&nbsp;</span>");
				}
				if (options.underneathBg!="none"){
					var positionOfBg={};
					positionOfBg['margin-left']=$(elem).css("margin-left");
					positionOfBg['margin-right']=$(elem).css("margin-right");
					positionOfBg['margin-top']=$(elem).css("margin-top");
					positionOfBg['margin-bottom']=$(elem).css("margin-bottom");
					positionOfBg['left']=$(elem).css("left");
					positionOfBg['right']=$(elem).css("right");
					positionOfBg['top']=$(elem).css("top");
					positionOfBg['bottom']=$(elem).css("bottom");
					positionOfBg['width']=$(elem).outerWidth()+"px";
					positionOfBg['height']=$(elem).outerHeight()+"px";
					positionOfBg['z-index']=parseInt($(elem).css("z-index"),10)-1;
					positionOfBg['position']="fixed";
					positionOfBg['background']="transparent url("+options.underneathBg+") left top repeat";
					var styleString="";
					for (var i in positionOfBg) {
						styleString+=i+":"+positionOfBg[i]+";";
					}
					$(elem).before("<span style='"+styleString+"'>&nbsp;</span>");
				}
				$(elem).trigger("attachElemToWindow.attachingDone");
			}
			else if (parseInt($(window).scrollTop(),10)<options.maxIndent && $(elem).hasClass(options.locksOnWindowClass)===true){
				$(elem).removeClass(options.locksOnWindowClass);
				if (options.substituteElemClass!="none"){
					$(elem).next().remove();
				}
				if (options.underneathBg!="none"){
					$(elem).prev().remove();
				}
				$(elem).trigger("attachElemToWindow.attachingDone");
			}
		};
		return this.each(attachTracking);
	};
})(jQuery);