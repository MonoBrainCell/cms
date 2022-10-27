// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования



function fullControlElements(options){//функция "навешивания" событий на все необходимые элементы
	controlsInit(); 
	for (i in options.panelCommand){
		commandsInit(i,options.panelCommand[i]);
	}
	
	function controlsInit(){// Инициализация, т.е. "навешивание" на селекторы управляющих элементов событий с действиями, которые должны производится при клике на них
		var dialogWindows=[];
		dialogWindows[0]=options.control.panelId;
		$("body").data("dialogWindows",dialogWindows);
		
		$("body").on('click',"#"+options.control.cancelButtonId,function(e){
			e.preventDefault();
			$(this).remove();
			cancelAction(options.control.callMenuClass,options.control.overburdenId);
		});
		$("body").on('click',"."+options.control.callMenuClass+":not('[class!=\""+options.control.callMenuClass+"\"]')",function(e){
			e.preventDefault();
			$(this).parents("."+options.control.elemClass).first().attr("id","first_selected_elem");
			$("#"+options.control.panelId).css("display","block");
			$("#"+options.control.panelId).append("<span id='"+options.control.cancelButtonId+"'>Отмена</span>");
			$("#"+options.control.overburdenId).css({"display":"block",minHeight:$(window).height()+"px"});
		});
		$("body").on('click',"."+options.control.callMenuClass+"."+options.control.abortClass,function(e){
			e.preventDefault();
			return false;
		});
	}
	
	function commandsInit(key,sets){
		var dialogWindows=$("body").data("dialogWindows");
		dialogWindows[dialogWindows.length]=sets.relatedWindowId;
		$("body").data("dialogWindows",dialogWindows);
		if (sets.typeAction=="before" || sets.typeAction=="after" || sets.typeAction=="remove"){
			manipulationInit(key,sets);
		}
	}
	
	function viewDialogWindow(windowId,panelId,cancelButtonId){//Показывает промежуточное диалоговое окно
		$("#"+windowId).css("display","block");
		$("#"+panelId).find("#"+cancelButtonId).remove();
		$("#"+panelId).css("display","none");
		$("#"+windowId).append("<span id='"+cancelButtonId+"'>Отмена</span>");
	}
	
	function cancelAction(callMenuClass,overburden){
		var windowsId=$("body").data("dialogWindows");
		var totalSelector="";
		for (i=0,c=windowsId.length;i<c; i++){
			if (i==0){
				totalSelector+="#"+windowsId[i];
			}
			else {
				totalSelector+=", #"+windowsId[i];
			}
		}
		$(totalSelector).css("display","none");
		$("."+callMenuClass).attr("class",callMenuClass);
		$("#first_selected_elem").removeAttr("id");
		if ($("#"+overburden).css("display")!="none"){
			$("#"+overburden).css("display","none");
		}
	}
	
	function manipulationInit(name,sets){//Инициализируем команды по перемещению элемента
		$("body").on('click',"#"+name,function(e){
			e.preventDefault();
			var startElem="first_selected_elem";
			if (sets.typeAction!="remove"){
				$("#"+startElem).siblings().children("."+options.control.callMenuClass).addClass(sets.manipulateClass);
				$("#"+startElem).children("."+options.control.callMenuClass).addClass(options.control.abortClass);
				$("#"+startElem).siblings("."+options.control.elemClass).children("."+options.control.callMenuClass).addClass(options.control.abortClass);
				viewDialogWindow(sets.relatedWindowId,options.control.panelId,options.control.cancelButtonId);
				$("#"+options.control.overburdenId).css("display","none");
			}
			else {
				var confirmAction=confirm("Уверены, что хотите удалить элемент?");
				if (confirmAction===true){
					replaceElemAction(sets.typeAction,sets.galleryMarker);
				}
				else {
					$("#first_selected_elem").removeAttr("id");
				}
			}
		});
		$("body").on('click',"."+options.control.callMenuClass+"."+sets.manipulateClass,function(e){
			e.preventDefault();
			var confirmAction=confirm("Уверены, что хотите переместить элемент сюда?");
			if (confirmAction===true){
				$(this).parents("."+options.control.elemClass).first().attr("id","end_target_elem");
				replaceElemAction(sets.typeAction,sets.galleryMarker);
			}
		});
	}
	
	function replaceElemAction(type,galleryMarker){
		var startId="first_selected_elem";
		var targetId="end_target_elem";
		var textValue=$("#"+startId).find("input[type=text]").val();
		$("#"+startId).wrap("<div id='skin_to_replace_elem'></div>");
		var html_to_replace=$($("#skin_to_replace_elem").html());
		var inputTextTag=$(html_to_replace).find("input[type=text]");
		/**/
		$("#skin_to_replace_elem").remove();
		if (type=="after" || type=="before"){
			if (type=="after"){
				$("#"+targetId).after(html_to_replace);
			}
			else if (type=="before"){
				$("#"+targetId).before(html_to_replace);
			}
			$(inputTextTag).val(textValue);
			$(inputTextTag).textFieldsManager(options.control.fieldSettings);
			/**/
		}
		else if (type=="remove"){ }
		$("."+options.control.callMenuClass).attr("class",options.control.callMenuClass);
		$("#"+startId+", #"+targetId).removeAttr("id");
		cancelAction(options.control.callMenuClass,options.control.overburdenId);
		$("#"+options.control.cancelButtonId).remove();
	}	
}
/*SEARCH FILES TO VIEW AND CHOISE*/

function attachEventsToBoxOfFiles(params){
	$(params.addIconSelector).click(function(){
		var checkAction=confirm("Вы уверены, что хотите добавить в поле именно этот файл. Как только Вы подтвердите Ваше намерение, текущее окно для выбора файлов будет закрыто.");
		if (checkAction===true){
			if (params.newElem===true){
				var output=$(this).siblings(params.pathConteinerSelector).text();
				var inputHtml=params.htmlTemplate.replace(/{path_insert}/g,output);
				inputHtml=inputHtml.replace(/{added_field_id}/,params.addedFieldId);
				inputHtml=inputHtml.replace(/{added_anchor_class}/g,params.addedAnchorClass);
				$("#"+params.addNewElemButId).before(inputHtml);
				$("#"+params.addedFieldId).textFieldsManager(params.fieldSettings);
				$("#"+params.addedFieldId).removeAttr("id");
				/**/
				$("."+params.addedAnchorClass).removeClass(params.addedAnchorClass);		
			}
			else {
				var output=$(this).siblings(params.pathConteinerSelector).text();
				$(params.fieldElem).text(output);
				for (i in params.attrToChange){
					var text=params.attrToChange[i].replace(/{path_insert}/,output);
					$(params.fieldElem).attr(i,text);
				}					
			}
			$("#"+params.closeButId).trigger('click');
		}
	});
	
	if (params.imageManager!==undefined){
		$("body").data("windowWidth",parseInt($(window).width(),10));
		$("body").data("windowHeight",parseInt($(window).height(),10));
		
		$("#"+params.imageManager.imageId).on('load',function(){
			$(this).css({height:"auto",width:"auto"});
			$(this).data("trueWidth",parseInt($(this).width(),10));
			$(this).data("trueHeight",parseInt($(this).height(),10));
			$(this).css("display","none");
			resizeImage($(this),500,400);
			$("#"+params.imageManager.preloaderId).css("display","none");
			$(this).css("display","inline-block");
			$(params.imageManager.navigateButtons.prevSelector).css("display","inline-block");
			$(params.imageManager.navigateButtons.nextSelector).css("display","inline-block");
		});
		$(params.imageManager.viewIconSelector).click(function(){
			var imageSrc=$(this).siblings(params.pathConteinerSelector).text();
			$(params.imageManager.navigateButtons.prevSelector).css("display","none");
			$(params.imageManager.navigateButtons.nextSelector).css("display","none");
			$("#"+params.imageManager.imageId).attr("src",imageSrc);
			$("#"+params.imageManager.preloaderId).css("display","inline-block");
			$(params.imageManager.rowContainingIconSelector).removeAttr("id");
			$(this).parent(params.imageManager.rowContainingIconSelector).attr("id","viewing_image_path");
		});
		$(window).resize(function(){
			$("body").data("windowWidth",parseInt($(window).width(),10));
			$("body").data("windowHeight",parseInt($(window).height(),10));
			if ($("#"+params.imageManager.imageId+"_clone").length>0){
				resizeImage($("#"+params.imageManager.imageId+"_clone"),$("body").data("windowHeight"),$("body").data("windowWidth"));
				if (parseInt($("#"+params.imageManager.imageId+"_clone").width(),10)<$("body").data("windowWidth")){
					$("#"+params.imageManager.imageId+"_clone").css({marginLeft:(($("body").data("windowWidth")-parseInt($("#"+params.imageManager.imageId+"_clone").width(),10))/2)+"px"});
				}
				if (parseInt($("#"+params.imageManager.imageId+"_clone").height(),10)<$("body").data("windowHeight")){
					$("#"+params.imageManager.imageId+"_clone").css({marginTop:(($("body").data("windowHeight")-parseInt($("#"+params.imageManager.imageId+"_clone").height(),10))/2)+"px"});
				}
			}
		});
		
		$(params.imageManager.navigateButtons.prevSelector).click(function(){
			if ($("#viewing_image_path").prev().length>0){
				var prevElem=$("#viewing_image_path").prev().children(params.imageManager.viewIconSelector);
				$(prevElem).trigger('click');
			}
		});
		$(params.imageManager.navigateButtons.nextSelector).click(function(){
			if ($("#viewing_image_path").next().length>0){
				var nextElem=$("#viewing_image_path").next().children(params.imageManager.viewIconSelector);
				$(nextElem).trigger('click');
			}
		});
		$(params.imageManager.realSizeButSelector).click(function(){
			$("body").append("<span id='"+params.imageManager.undelyingId+"'></span>");
			$("#"+params.imageManager.imageId).clone().attr("id",params.imageManager.imageId+"_clone").appendTo("body");
			$("#"+params.imageManager.imageId+"_clone").css({position:"fixed",top:0,left:0,zIndex:parseInt($("#"+params.imageManager.undelyingId).css("z-index"),10)+1});
			$("#"+params.imageManager.imageId+"_clone").data({"trueWidth":$("#"+params.imageManager.imageId).data("trueWidth"),"trueHeight":$("#"+params.imageManager.imageId).data("trueHeight")});
			resizeImage($("#"+params.imageManager.imageId+"_clone"),$("body").data("windowHeight"),$("body").data("windowWidth"));
			
			if (parseInt($("#"+params.imageManager.imageId+"_clone").width(),10)<$("body").data("windowWidth")){
				$("#"+params.imageManager.imageId+"_clone").css({marginLeft:(($("body").data("windowWidth")-parseInt($("#"+params.imageManager.imageId+"_clone").width(),10))/2)+"px"});
			}
			if (parseInt($("#"+params.imageManager.imageId+"_clone").height(),10)<$("body").data("windowHeight")){
				$("#"+params.imageManager.imageId+"_clone").css({marginTop:(($("body").data("windowHeight")-parseInt($("#"+params.imageManager.imageId+"_clone").height(),10))/2)+"px"});
			}
			$("#"+params.imageManager.imageId+"_clone").click(function(){
				$(this).unbind('click');
				$(this).remove();
				$("#"+params.imageManager.undelyingId).remove();
			});
			$("#"+params.imageManager.undelyingId).click(function(){
				$(this).unbind('click');
				$("#"+params.imageManager.imageId+"_clone").trigger('click');
			});
			
		});
	
	
	}
	
	function resizeImage(image,limitHeight,limitWidth){
		var imgwidth=$(image).data("trueWidth");
		var imgheight=$(image).data("trueHeight");
		
		var imgparentwidth=limitWidth;
		var imgparentheight=limitHeight;
		
		if (imgwidth>imgheight){
			var proportion=Math.round(imgwidth/imgheight*100)/100;
			var proportion_type=1;
		}
		if (imgwidth<imgheight){
			var proportion=Math.round(imgheight/imgwidth*100)/100;
			var proportion_type=2;
		}
		if (imgwidth==imgheight){
			var proportion=1;
			var proportion_type=3;
		}
		if (imgwidth>imgparentwidth && imgheight<=imgparentheight){
			var differwidth=imgwidth-imgparentwidth;
			var differheight=Math.round(differwidth/proportion);
		}
		if (imgheight>imgparentheight && imgwidth<=imgparentwidth){
			var differheight=imgheight-imgparentheight;
			var differwidth=Math.round(differheight/proportion);
		}
		if (imgheight>imgparentheight && imgwidth>imgparentwidth){
			if (proportion_type==1){
				var differwidth=imgwidth-imgparentwidth;
				var differheight=Math.round(differwidth/proportion);
			}
			if (proportion_type==2){
				var differheight=imgheight-imgparentheight;
				var differwidth=Math.round(differheight/proportion);
			}
			if (proportion_type==3){
				var differheight_1=imgheight-imgparentheight;
				var differwidth_1=imgwidth-imgparentwidth;
				if (differheight_1>differwidth_1){
					var differheight=differheight_1;
					var differwidth=Math.round(differheight/proportion);
				}
				if (differheight_1<differwidth_1){
					var differwidth=differwidth_1;
					var differheight=Math.round(differwidth/proportion);
				}
				if (differheight_1==differwidth_1){
					var differwidth=differwidth_1;
					var differheight=differheight_1;
				}
			}
		}
		if (imgheight<imgparentheight && imgwidth<imgparentwidth){
			differwidth=0;
			differheight=0;
		}
		var imgCurrentWidth=imgwidth-differwidth;
		var imgCurrentHeight=imgheight-differheight;
		$(image).css({width:imgCurrentWidth+"px",height:imgCurrentHeight+"px"});
	}
}

/*Specials For Images Gallery ONLY !!!*/
function deleteImage(options){
	if ($(options.deleteButton).siblings("a[data-gallery]").length<1){
		alert("Удаление не осуществимо, т.к. данная позиция в галереи пуста");
		return false;
	}
	else if ($(options.deleteButton).siblings("a").attr("class")=="preview_image_info" && $("body").find("a.preview_image_info[data-gallery]").length<2){
		alert("В галерее должно быть хотя бы одно preview-изображение! Удаление заблокировано.");
		return false;
	}
	else if ($(options.deleteButton).siblings("a").attr("class")=="original_image_info" && $("body").find("a.original_image_info[data-gallery]").length<2){
		alert("В галерее должно быть хотя бы одно оригинальное изображение! Удаление заблокировано.");
		return false;
	}
	else if ($(options.deleteButton).parents("."+options.parentElemClass).find("a[data-gallery]").length<2){
		$(options.deleteButton).parents("."+options.parentElemClass).attr("id","first_selected_elem");
		$("#"+options.removeGroupButtonId).trigger('click');
	}
	else {
		var confirmAction=confirm("Уверены, что хотите удалить это изображение из галереи?");
		if (confirmAction===true){
			$(options.deleteButton).siblings("a").removeAttr("data-gallery");
		}
	}
}