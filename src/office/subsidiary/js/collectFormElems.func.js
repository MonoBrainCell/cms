// Программное Обеспечение для управления сайтом "Adept Illusion"
// Данное ПО является открытым для его изменения и использования
function collectFormElems(formName){
	var elemsValues={};
	var fixedValues=$("form[name="+formName+"] input[type=checkbox], form[name="+formName+"] input[type=radio]");
	var textValues=$("form[name="+formName+"] input[type!=checkbox][type!=radio], form[name="+formName+"] textarea");
	var selectValues=$("form[name="+formName+"] select");
	if ($(fixedValues).length>0){
		for (var a=0, b=$(fixedValues).length;a<b;a++){
			if ($(fixedValues[a]).is("input[type=checkbox]:checked")===true || $(fixedValues[a]).is("input[type=radio]:checked")===true){
				var elemName=$(fixedValues[a]).attr("name");
				if (elemsValues[elemName]!=undefined){
					elemsValues[elemName][elemsValues[elemName].length]=$(fixedValues[a]).val();
				}
				else {
					elemsValues[elemName]=[];
					elemsValues[elemName][0]=$(fixedValues[a]).val();
				}
			}
		}
	}
	if ($(textValues).length>0){
		for (var a=0,b=$(textValues).length;a<b;a++){
			var elemName=$(textValues[a]).attr("name");
			if (elemsValues[elemName]!=undefined){
				elemsValues[elemName][elemsValues[elemName].length]=$(textValues[a]).val();
			}
			else {
				elemsValues[elemName]=[];
				elemsValues[elemName][0]=$(textValues[a]).val();
			}
		}
	}
	if ($(selectValues).length>0){
		for (var a=0, b=$(selectValues).length;a<b;a++){
			var elemName=$(selectValues[a]).attr("name");
			if ($(selectValues[a]).is("[multiple]")===true){
				var valuesGroup=$(selectValues[a]).val();
				if ($(valuesGroup).length>0){
					for (var c=0, d=$(valuesGroup).length;c<d;c++){
						if (elemsValues[elemName]!=undefined){
							elemsValues[elemName][elemsValues[elemName].length]=valuesGroup[c];
						}
						else {
							elemsValues[elemName]=[];
							elemsValues[elemName][0]=valuesGroup[c];
						}
					}
				}
			}
			else {
				if (elemsValues[elemName]!=undefined){
					elemsValues[elemName][elemsValues[elemName].length]=$(selectValues[a]).val();
				}
				else {
					elemsValues[elemName]=[];
					elemsValues[elemName][0]=$(selectValues[a]).val();
				}
			}
		}
	}
	return elemsValues;
}