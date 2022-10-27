// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
function relinkInit(opts,funcs){
	$("body").on('click',"#"+opts.CPCommandName,function(){
		var startElem=$("body").data("startElemId");
		if ($("#"+startElem).hasClass(opts.deniedClass)===false){
			$("."+opts.control.callMenuClass).not("#"+startElem+" ."+opts.control.callMenuClass).addClass(opts.manipulateClass);
			$("#"+startElem+" ."+opts.control.callMenuClass).addClass(opts.control.abortClass);
			funcs.dialogWindow(opts.relatedWindowId,opts.control.panelId,opts.control.cancelButtonId);
			$("#"+opts.control.overburdenId).css("display","none");
		}
		else {
			alert("Нельзя перемещать выбранный Вами элемент");
			return false;
		}
	});
	$("body").on('click',"."+opts.control.callMenuClass+"."+opts.manipulateClass,function(){
		if ($(this).parents("."+opts.control.parentElemClass).hasClass(opts.deniedTargetClass)===true){
			alert("Этот элемент нельзя назначить родителем выбранному ранее");
			return false;
		}
		var confirmAction=confirm("Уверены, что хотите определить выбранный ранее элемент, как дочерний для этого элемента?");
		if (confirmAction===true){
			var targetElem=$(this).parents("."+opts.control.parentElemClass).first().attr("id");
			$("body").data("targetElemId",targetElem);
			datas={};
			datas.phpFileInc=opts.phpFileInc;
			datas.startElem=$("body").data("startElemId").replace(opts.control.wastePrefix,"");
			datas.targetElem=$("body").data("targetElemId").replace(opts.control.wastePrefix,"");

			sendAjaxRqst(datas,opts.messageWindowProps,replaceElemAction,false);
		}
	});
	
	function replaceElemAction(){
		if ($("#"+opts.messageWindowProps.id).find("."+opts.control.successClass).length>0){
			var startId=$("body").data("startElemId");
			var targetId=$("body").data("targetElemId");
			var exeType=$("#"+opts.messageWindowProps.id).find("."+opts.control.successClass).attr("id");
			if (exeType=="to_another_chapter" && targetId.replace(opts.control.wastePrefix,"")=="advanced"){
				if ($("#"+startId).children(".navigation_row").length>0){
					$("#"+startId+" > *").not(".navigation_row").remove();
					$("#"+startId).children(".navigation_row:first").unwrap();
				}
				else {
					$("#"+startId).remove();
				}
				
			}
			else if (exeType=="to_another_chapter" && targetId.replace(opts.control.wastePrefix,"")!="advanced"){
				$("#"+startId).remove();
			}
			else if (exeType=="self_chapter"){}
			else {
				$("#"+startId).wrap("<span id='skin_to_replace_elem'></span>");
				var html_to_replace=$("#skin_to_replace_elem").html();
				$("#skin_to_replace_elem").remove();
				if (targetId.replace(opts.control.wastePrefix,"")=="advanced" || targetId.replace(opts.control.wastePrefix,"")=="main"){
					$(".navigation_conteiner").append(html_to_replace);
				}
				else {
					$("#"+targetId).append(html_to_replace);
				}
			}
		}
		funcs.ajaxButtonInit(opts.messageWindowProps.closeButId,opts.control.callMenuClass,opts.control.overburdenId,funcs.cancel,opts.control.cancelButtonId);
	}
}
function aliasChangeInit(opts,funcs){
		var formName=$("#"+opts.relatedWindowId).find("form[target=ajax]").attr("name");
		$("body").on('click',"#"+opts.CPCommandName,function(){
			if ($("#"+ $("body").data("startElemId")).find(opts.manipulateClass).hasClass(opts.deniedClass)===false){
				funcs.dialogWindow(opts.relatedWindowId,opts.control.panelId,opts.control.cancelButtonId);
			}
			else {
				alert("Нельзя изменить данное поле у выбранного Вами элемента");
			}
		});
		$("form[name="+formName+"]").submit(function(){
			return false;	
		});
		$("form[name="+formName+"] input[type=submit]").click(function(){
			var confirmAction=confirm("Уверены, что хотите изменить указанный текст?");
			if (confirmAction===true){
				var startElem=$("body").data("startElemId").replace(opts.control.wastePrefix,"");
				var datas=collectFormElems(formName);
				datas.startElem=startElem;
				datas.phpFileInc=$(this).parents("form").attr("action");
				changeParams={};
				changeParams.text=$("form[name="+formName+"] input[type=text]").val();
				changeParams.changeClass=opts.manipulateClass;
				sendAjaxRqst(datas,opts.messageWindowProps,changeText,changeParams);
			}
		});

	function changeText(params){
		if ($("#"+opts.messageWindowProps.id).find("."+opts.control.successClass).length>0){
			var startId=$("body").data("startElemId");
			$("#"+startId).find("."+params.changeClass).text(params.text);
			$("#"+startId).find("."+params.changeClass).attr("href",params.text);
			funcs.ajaxButtonInit(opts.messageWindowProps.closeButId,opts.control.callMenuClass,opts.control.overburdenId,funcs.cancel,opts.control.cancelButtonId);
		}
	}
}
