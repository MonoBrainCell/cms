<style type="text/css">
#main_content fieldset {
	margin-top: 15px;
}
#main_content .form_elem input[type='radio']{
	display: inline-block;
	width: auto;
	margin-left:20px;
}
#main_content .form_elem label {
	display: inline-block;
	margin-left:5px;
	cursor: pointer;
}
#main_content .form_elem input[type='radio'][disabled] + label {color: #888;}
</style>
<div id='main_content'>
<h1>Управление функционалом сайта</h1>
<div class="form_elem">
<fieldset>
<legend>Сайт</legend>
<input class='site_engine_manager' type="radio" name='site_main_engine' value="on" id='site_main_engine_on'{SITE_ENGINE_ON}><label for="site_main_engine_on">Вкл.</label>
<input class='site_engine_manager' type="radio" name='site_main_engine' value="off" id='site_main_engine_off'{SITE_ENGINE_OFF}><label for="site_main_engine_off">Выкл.</label>
</fieldset>
</div>
{VARIABLE_PART}
</div>