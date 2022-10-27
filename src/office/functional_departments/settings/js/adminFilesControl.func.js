// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
function renameFileInit(opts,funcs){
	$("#"+opts.CPCommandName).click(function(){
		var parentId=$("body").data("startElemId");
		var formName=$("#"+opts.relatedWindowId+" form").attr("name");
		$("form[name='"+formName+"']").find("input[name='new_file_name'], input[name='old_file_name']").val(parentId);
		funcs.dialogWindow(opts.relatedWindowId,opts.control.panelId,opts.control.cancelButtonId);
		$("form[name='"+formName+"']").submit(function(){ return false; });
		$("form[name='"+formName+"'] input[name='submit_redaction']").click(function(){
			var datas=collectFormElems(formName);
			datas.phpFileInc=opts.startActionAddress;
			var messageWindowProps={};
			messageWindowProps.startActMsg="Производится отправка данных для . . .";
			messageWindowProps.id="ajax_window";
			messageWindowProps.overburdenId="ajax_overburden";
			messageWindowProps.closeButId="ajax_close_button";
			messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку.";
			sendAjaxRqst(datas,messageWindowProps,firstAjaxResult,false);
			function firstAjaxResult(){
				if($("#"+messageWindowProps.id).find("."+opts.control.successClass).length>0){
					$("#"+opts.continueButtonId).one('click',function(){
						datas.phpFileInc=opts.secondActionAddress;
						$("#"+messageWindowProps.closeButId).triggerHandler('click');
						sendAjaxRqst(datas,messageWindowProps,secondAjaxResult,false);
						function secondAjaxResult(){
							if($("#"+messageWindowProps.id).find("."+opts.control.successClass).length>0){
								var newId=$("#"+messageWindowProps.id).find("#"+opts.convertedNameConteinerId).text();
								if ($("form[name='"+formName+"'] input[name='old_file_name']").val()!=$("form[name='"+formName+"'] input[name='new_file_name']").val()){
									$("[id='"+newId+"']").remove();
									$("[id='"+parentId+"'] .page_name").text(newId);
									var oldHref=$("[id='"+parentId+"'] .page_alias").attr("href");
									$("[id='"+parentId+"'] .page_alias").attr("href",oldHref.replace(parentId,newId));
									$("[id='"+parentId+"']").attr("id",newId);
								}
								$("#"+messageWindowProps.closeButId).click(function(){
									$("#"+opts.control.cancelButtonId).trigger('click');
									$("form[name='"+formName+"'] input[name='submit_redaction']").unbind('click');
								});
							}
						}
					});
				}
			}
		});
	});
}
function standOutFileInit(opts,funcs){
	$("#"+opts.CPCommandName).click(function(){
		var parentId=$("body").data("startElemId");
		var formName=$("#"+opts.relatedWindowId+" form").attr("name");
		$("form[name='"+formName+"']").find("input[name='new_file_name'], input[name='old_file_name']").val(parentId);
		funcs.dialogWindow(opts.relatedWindowId,opts.control.panelId,opts.control.cancelButtonId);
		$("form[name='"+formName+"']").submit(function(){ return false; });
		$("form[name='"+formName+"'] input[name='submit_redaction']").click(function(){
			var datas=collectFormElems(formName);
			datas.phpFileInc=opts.startActionAddress;
			var messageWindowProps={};
			messageWindowProps.startActMsg="Производится отправка данных для . . .";
			messageWindowProps.id="ajax_window";
			messageWindowProps.overburdenId="ajax_overburden";
			messageWindowProps.closeButId="ajax_close_button";
			messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку.";
			sendAjaxRqst(datas,messageWindowProps,firstAjaxResult,false);
			function firstAjaxResult(){
				if($("#"+messageWindowProps.id).find("."+opts.control.successClass).length>0){
					$("#"+opts.continueButtonId).one('click',function(){
						datas.phpFileInc=opts.secondActionAddress;
						if (opts.typeStandOut=="move"){
							datas.action_type="replace";
						}
						else {
							datas.action_type="copy";
						}
						$("#"+messageWindowProps.closeButId).triggerHandler('click');
						sendAjaxRqst(datas,messageWindowProps,secondAjaxResult,false);
						function secondAjaxResult(){
							if ($("form[name='"+formName+"']").find("input[name='old_directory']").val()!=$("form[name='"+formName+"']").find("select[name='directory']").val()){
								if($("#"+messageWindowProps.id).find("."+opts.control.successClass).length>0){
									if (opts.typeStandOut=="move"){
										$("[id='"+parentId+"']").remove();
									}
									$("#"+messageWindowProps.closeButId).click(function(){
										$("#"+opts.control.cancelButtonId).trigger('click');
										$("form[name='"+formName+"'] input[name='submit_redaction']").unbind('click');
									});
								}
							}
						}
					});
				}
			}
		});
	});
}
function deleteFileInit(opts,funcs){
	$("#"+opts.CPCommandName).click(function(){
		var parentId=$("body").data("startElemId");
		var curDir=$("#"+opts.relatedWindowId).text();
		var confirmAction=confirm("Уверены, что хотите удалить файл "+parentId+"?");
		var datas={};
		datas.phpFileInc="functional_departments/settings/executors/filesRemove.php";
		datas.directory=curDir;
		datas.file_name=parentId;
		var messageWindowProps={};
		messageWindowProps.startActMsg="Производится отправка данных для . . .";
		messageWindowProps.id="ajax_window";
		messageWindowProps.overburdenId="ajax_overburden";
		messageWindowProps.closeButId="ajax_close_button";
		messageWindowProps.errorMsg="Простите, произошла ошибка при отправке данных. Обновите страницу в браузере и повторите попытку.";
		function ajaxAnswer(){
			if($("#"+messageWindowProps.id).find("."+opts.control.successClass).length>0){
				$("[id='"+parentId+"']").remove();
				$("#"+messageWindowProps.closeButId).click(function(){
					$("#"+opts.control.cancelButtonId).trigger('click');
				});
			}
		}
		if (confirmAction===true){
			sendAjaxRqst(datas,messageWindowProps,ajaxAnswer,false);
		}
	});
}