$(document).ready(function(){
$("#main_navigation").attachElemToWindow({maxIndent:65,underneathBg:"office/adminTemplate/style/images/navigation_bg_substrate.png",locksOnWindowClass:"fixed_to_top"});
$("#main_navigation .item_title").click(function(){
	if ($(this).next(".second_floor").length>0 && $(this).next(".second_floor").css('display')=="none"){
		$(this).next(".second_floor").slideDown(500).css("display","inline-block");
	}
	else if ($(this).next(".second_floor").length>0 && $(this).next(".second_floor").css('display')!="none"){
		$(this).next(".second_floor").slideUp(500);
	}
});
});