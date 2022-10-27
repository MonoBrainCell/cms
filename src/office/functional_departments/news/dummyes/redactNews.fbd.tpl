<div id="main_content">
<span class="main_field">&nbsp;</span> - поле обязательно для заполнения
<form name="develop_content" method="post" action="functional_departments/news/executors/contentSave.php" target="ajax"> 
<input type="hidden" name="news_id" value="{HIDDEN_ID}">
<h2 class="main_field">Новостной заголовок</h2>
<div class="form_elem">
<span class="block_with_advice">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Содержание данного поля будет отображаться в списке новостей, в качестве заголовка следующего за ним усечённого текста новости, также будет отображаться на странице данной новости в виде основного заголовка.
	<br>
	<span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span>
	</span>
</span>
<input type="text" name="news_header" value="{NEWS_HEADER}" maxlength="150">
<span class="warning_sintaxis"></span>
<span class="warning_lenght">Осталось символов: 150</span>
</div>

<h1 class="main_field">Новостной контент</h1>
<div class="form_elem">
<span class="block_with_advice" style="margin-right:0;">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Содержание данного поля будет отражаться в виде основного контента на странице текущей новости (статья, текст, изображения и т.д.). В списке новостей, также будет использовано данное содержание, усечённое до определённого количества символов.
	</span>
</span>
<span id="view_available_images">Показать доступные изображения</span>
<textarea id="content_text" name="content_text">{CONTENT}</textarea>
</div>
<input type="submit" name="submit_redaction" value="Сохранить изменения">
</form>
</div>