// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
function attachEventsToBoxOfFiles(params){
	$(params.addIconSelector).click(function(){
		if ($(params.fieldElem).length>0){
			var checkAction=confirm("Вы уверены, что хотите добавить в поле именно этот файл. Как только Вы подтвердите Ваше намерение текущее окно для выбора файлов будет закрыто.");
			if (checkAction===true){
				var output=$(this).siblings(params.pathConteinerSelector).text();
				$(params.fieldElem).val(output);
				$("#"+params.closeButId).trigger('click');
			}
		}
		else {
			alert("Поле для добавления адреса не найдено");
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