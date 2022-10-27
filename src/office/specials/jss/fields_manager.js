// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
var patterns={
	'log_pas':{
		'type':'exception',
		'pattern':/[а-яА-Я\`\~\$\%\^\&\*\{\}\[\]\(\)\=\+\,\.\?\'\"\:\;\№\<\>\/]/,
		'errorDesc':"Используйте только Кириллические символы, без синтаксических символов"
	}
};
// function checkPattern(elemForChecking,pattern){
	// if (patterns[pattern]['type']=="exception"){
		// if (elemForChecking.match(patternsArray[pattern]['pattern'])==1){
			// return patternsArray[$pattern]['errorDesc'];}
		// else{
			// return false;}
	// }
	// else if (patterns[pattern]['type']=="rule"){
		// if (elemForChecking.match(patternsArray[pattern]['pattern'])==0){
			// return patternsArray[pattern]['errorDesc'];}
		// else{
			// return false;}
	// }
// }
function checkAndViewErrors(elemName,patternsObject){
	var colorOfAlert="#a00";
	var text=$("[name='"+elemName+"']").val();
	var maxLength=$("[name='"+elemName+"']").attr("maxlength");
	var symbolsLeft=parseInt(maxLength,10)-parseInt(text.length,10);
	for (i in patternsObject){
		if (patterns[patternsObject[i]]['type']=="exception"){
			if (text.match(patterns[patternsObject[i]]['pattern'])!=null){
				$("#"+elemName+"_symbols_warning").html("Присутствует запрещенный символ!");
				$("#"+elemName+"_symbols_warning").css({"color":colorOfAlert});
			}
			else {
				$("#"+elemName+"_symbols_warning").html("&nbsp;");
			}
		}
		else if (patterns[patternsObject[i]]['type']=="rule"){
			if (text.match(patterns[patternsObject[i]]['pattern'])==null){
				$("#"+elemName+"_template_warning").html("Введенный контент не соответствует указанному шаблону!");
				$("#"+elemName+"_template_warning").css({"color":colorOfAlert});
			}
			else {
				$("#"+elemName+"_template_warning").html("&nbsp;");
			}
		}
	}
	$("#"+elemName+"_length_warning").html("Осталось символов: "+symbolsLeft);
}

$(document).ready(function(){
function startPosition(){
	all_func_login();all_func_pasw();
}
startPosition();
/*>>><<<*/
$("input[type=pasw], textarea").focus(function(){
	$(this).prev("span").children("sup").css("color","#009");
});
$("input[type=pasw], textarea").blur(function(){
	$(this).prev("span").children("sup").css("color","#000");
});
$("body").on({'keyup':all_func_login,'focus':all_func_login,'blur':all_func_login},"[name=login]");
function all_func_login(){
	checkAndViewErrors("login",{"0":'log_pas'});
}
$("body").on({'keyup':all_func_pasw,'focus':all_func_pasw,'blur':all_func_pasw},"[name=pasw]");
function all_func_pasw(){
	checkAndViewErrors("pasw",{"0":'log_pas'});
}
});
