// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования

// 3 Встроенных функции, привязывющиеся к иконкам (по определённым селекторам) событиями клика по ним. (Перемещение ДО указанного элемента, Перемещение ПОСЛЕ указанного элемента, Изменение содержания определённого тега)
// Дополнительные функции встраиваются отдельно, через соответствующие параметры передаваемые ГЛАВНОЙ функции.

// control - объект с общими настройками
//// control.panelId - идентификатор панели команд
//// control.overburdenId - идентификатор перекрывающего слоя
//// control.cancelButtonId - идентификатор кнопки "сброса" действий над элементом
//// control.callMenuClass - класс иконки вызывающей панель команд действий на элементом
//// control.elemClass - класс элемента в котором находится кнопка
//// control.parentElemClass - класс оболочки, хранящей его id
//// control.abortClass - класс иконки блокирующей действия иконки на элементом
//// control.successClass - класс, который должно иметься в окне вывода результатов запроса, как определитель успешности произведённой манипуляции
//// control.wastePrefix - отсекаемый префикс у идентификатора родителя (для получения его числового id соответствующего ему элемента в файле)

// messageWindowProps - настройки диалогового окна отправки запроса Ajax
//// messageWindowProps.startActMsg="Производится отправка данных для сохранения . . ." (Оповещение об отправке данных)
//// messageWindowProps.id="ajax_window" (идентификатор окна состояния обработки данных)
//// messageWindowProps.overburdenId="ajax_overburden" (идентификатор перекрывающего слоя)
//// messageWindowProps.closeButId="ajax_close_button" (идентификатор кнопки закрытия окна состояния)
//// messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку." (Сообщение об ошибке)

// panelCommand - селектор команды на панели
////panelCommand."commandId" ...
// --- Для встроенных функций перемещения элемента: ---
//// panelCommand."commandId".typeAction - тип действия (будет вызывать определённую функцию) (изменение  текстового содержания определённого элемента, перемещение элемента между его соседями ИЛИ Пользовательская функция, на случай необходимости добавления ещё обработчиков)
//// panelCommand."commandId".manipulateClass - класс отвечающий за манипуляцию над элементами
//// panelCommand."commandId".relatedWindowId - идентификатор промежуточного диалогового окн
//// panelCommand."commandId".deniedClass - класс запрещающий указывать этот элемент, как якорь перемещения
//// panelCommand."commandId".phpFileInc - адрес куда отправятся данные для обработки

// --- Для встроенной функции изменения поля: ---
//// panelCommand."commandId".typeAction
//// panelCommand."commandId".manipulateClass - класс с изменяемым контентом
//// panelCommand."commandId".deniedClass - класс запрещающий изменять контент
//// panelCommand."commandId".relatedWindowId - идентификатор промежуточного диалогового окна




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
			var elemId=$(this).parents("."+options.control.parentElemClass).first().attr("id");
			$("body").data("startElemId",elemId);
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
		if (sets.typeAction=="before" || sets.typeAction=="after"){
			manipulationInit(key,sets);
		}
		else if (sets.typeAction=="textRedact"){
			changeTextInit(key,sets);
		}
		else {
			exeCustomFunction(key,sets);
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
		$("body").removeData("startElemId");
		if ($("#"+overburden).css("display")!="none"){
			$("#"+overburden).css("display","none");
		}
	}
	
	function supplemAjaxButtonInit(closeButId,callMenuClass,overburden,cancelFunction,cancelButton){
		$("#"+closeButId).click(function(){
			$("#"+cancelButton).remove();
			cancelFunction(callMenuClass,overburden);
			$("body").removeData("targetElemId");
		});
	}
	
	function manipulationInit(name,sets){//Инициализируем команды по перемещению элемента
		$("body").on('click',"#"+name,function(e){
			e.preventDefault();
			var startElem=$("body").data("startElemId");
			if ($("#"+startElem).hasClass(sets.deniedClass)===false){
				$("#"+startElem).siblings().children("."+options.control.elemClass).children("."+options.control.callMenuClass).addClass(sets.manipulateClass);
				$("#"+startElem).parent().children("."+options.control.elemClass).children("."+options.control.callMenuClass).addClass(options.control.abortClass);
				$("#"+startElem).parent().siblings().children("."+options.control.elemClass).children("."+options.control.callMenuClass).addClass(options.control.abortClass);
				viewDialogWindow(sets.relatedWindowId,options.control.panelId,options.control.cancelButtonId);
				$("#"+options.control.overburdenId).css("display","none");
			}
			else {
				alert("Нельзя перемещать выбранный Вами элемент");
				return false;
			}
		});
		$("body").on('click',"."+options.control.callMenuClass+"."+sets.manipulateClass,function(e){
			e.preventDefault();
			var confirmAction=confirm("Уверены, что хотите переместить элемент сюда?");
			if (confirmAction===true){
				var targetElem=$(this).parents("."+options.control.parentElemClass).first().attr("id");
				$("body").data("targetElemId",targetElem);
				datas={};
				datas.phpFileInc=sets.phpFileInc;
				datas.startElem=$("body").data("startElemId").replace(options.control.wastePrefix,"");
				datas.targetElem=$("body").data("targetElemId").replace(options.control.wastePrefix,"");
				datas.type=sets.typeAction;

				sendAjaxRqst(datas,options.messageWindowProps,replaceElemAction,datas.type);
			}
		});
	}
	
	function replaceElemAction(type){
		if ($("#"+options.messageWindowProps.id).find("."+options.control.successClass).length>0){
			var startId=$("body").data("startElemId");
			var targetId=$("body").data("targetElemId");
			$("#"+startId).wrap("<span id='skin_to_replace_elem'></span>");
			var html_to_replace=$("#skin_to_replace_elem").html();
			$("#skin_to_replace_elem").remove();
			if (type=="after"){
				$("#"+targetId).after(html_to_replace);
			}
			else if (type=="before"){
				$("#"+targetId).before(html_to_replace);
			}
		}
		supplemAjaxButtonInit(options.messageWindowProps.closeButId,options.control.callMenuClass,options.control.overburdenId,cancelAction,options.control.cancelButtonId);
	}
	
	function changeTextInit(name,sets){//Изменяет текстовое содержимое указанного элемента
		var formName=$("#"+sets.relatedWindowId).find("form[target=ajax]").attr("name");
		$("body").on('click',"#"+name,function(e){
			e.preventDefault();
			if ($("#"+ $("body").data("startElemId")).find("."+sets.manipulateClass).hasClass(sets.deniedClass)===false){
				viewDialogWindow(sets.relatedWindowId,options.control.panelId,options.control.cancelButtonId);
			}
			else {
				alert("Нельзя изменить данное поле у выбранного Вами элемента");
				return false;
			}
		});
		$("form[name="+formName+"]").submit(function(){
			return false;	
		});
		$("form[name="+formName+"] input[type=submit]").click(function(){
			var confirmAction=confirm("Уверены, что хотите изменить указанный текст?");
			if (confirmAction===true){
				var startElem=$("body").data("startElemId").replace(options.control.wastePrefix,"");
				var datas=collectFormElems(formName);
				datas.startElem=startElem;
				datas.phpFileInc=$(this).parents("form").attr("action");
				changeParams={};
				changeParams.text=$("form[name="+formName+"] input[type=text]").val();
				changeParams.changeClass=sets.manipulateClass;
				sendAjaxRqst(datas,options.messageWindowProps,changeText,changeParams);
			}
		});
	}
	
	function changeText(params){
		if ($("#"+options.messageWindowProps.id).find("."+options.control.successClass).length>0){
			var startId=$("body").data("startElemId");
			$("#"+startId).find("."+params.changeClass).text(params.text);
			supplemAjaxButtonInit(options.messageWindowProps.closeButId,options.control.callMenuClass,options.control.overburdenId,cancelAction,options.control.cancelButtonId);
		}
	}
	
	function exeCustomFunction(name,sets){
		sets.CPCommandName=name;
		var customFunction=sets.customFunction;
		delete sets.customFunction;
		sets.messageWindowProps=options.messageWindowProps;
		sets.control=options.control;
		embFuncs={};
		embFuncs.cancel=cancelAction;
		embFuncs.dialogWindow=viewDialogWindow;
		embFuncs.ajaxButtonInit=supplemAjaxButtonInit;
		customFunction(sets,embFuncs);
	}
}