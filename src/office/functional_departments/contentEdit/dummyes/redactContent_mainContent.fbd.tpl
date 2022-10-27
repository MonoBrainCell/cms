<div id="main_content">
<span class="main_field">&nbsp;</span> - поле обязательно для заполнения
<form name="develop_content" method="post" action="functional_departments/contentEdit/executors/contentSave.php" target="ajax"> 
<input type="hidden" name="page_id" value="{HIDDEN_ID}">
<div class="left_block">

<h2 class="main_field">Название страницы (title)</h2>
<div class="form_elem">
<span class="block_with_advice">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Содержание данного поля будет видно как название вкладки браузера, в которой открыта страница сайта.
	<br>
	<span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span>
	</span>
</span>
<input type="text" name="page_name" value="{TITLE}" maxlength="200">
<span class="warning_sintaxis"></span>
<span class="warning_lenght">Осталось символов: 200</span>
</div>

<h2 class="main_field">Основной заголовок страницы (h1)</h2>
<div class="form_elem">
<span class="block_with_advice">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Содержание данного поля будет отражаться в содержании страницы в виде главного заголовка, вначале контента страницы сайта (НО место размещения данной информации может быть изменено веб-верстальщиком создававшим дизайн Вашего сайта).
	<br>
	<span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span>
	</span>
</span>
<input type="text" name="page_h1" value="{H1}" maxlength="200">
<span class="warning_sintaxis"></span>
<span class="warning_lenght">Осталось символов: 200</span>
</div>
</div>

<div class="right_block">
<h3>Ключевые слова страницы</h3>
<div class="form_elem">
<span class="block_with_advice">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Содержание данного поля возможно будет анализироваться поисковыми системами на наличие слов поискового запроса на данной странице.
	<br>
	Каждое отдельное слово или словосочетание отделяйте запятыми.
	<br>
	<span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span>
	</span>
</span>
<input type="text" name="page_kwords" value="{KEYWORDS}" maxlength="300">
<span class="warning_sintaxis"></span>
<span class="warning_lenght">Осталось символов: 300</span>
</div>

<h3>Описание страницы</h3>
<div class="form_elem">
<span class="block_with_advice">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Содержание данного поля будет анализироваться поисковыми системами и участвовать в раскрытии информации по данной странице, в случае её попадения в выдачу ответов на поисковый запрос пользователя поисковой системы.
	<br>
	<span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span>
	</span>
</span>
<input type="text" name="page_desc" value="{DESCRIPTION}" maxlength="300">
<span class="warning_sintaxis"></span>
<span class="warning_lenght">Осталось символов: 300</span>
</div>
</div>
<div style="width: 100%; margin:0 auto; overflow: hidden; height: 15px; clear: both;"></div>
<p><span style='text-transform: uppercase; color: #900'>Обратите внимание!</span>
<br>
При добавлении в контент строк специального типа (таких как: <em style='color:#900'>{*код определённого функционала*}</em>),
<br>
используйте функцию на панели управления называющуюся <q style='color:#900'>Вставить как текст</q> (1-я строка, 3-я иконка слева)
</p>
<h1 class="main_field">Контент страницы</h1>
<div class="form_elem">
<span class="block_with_advice" style="margin-right:0;">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Содержание данного поля будет отражаться в виде основного контента данной страницы (статья, текст, изображения и т.д.).
	</span>
</span>
<span id="view_available_images">Показать доступные изображения</span>
<textarea id="content_text" name="content">{CONTENT}</textarea>
</div>
{VARIABLE_PART}
<input type="submit" name="submit_redaction" value="Сохранить изменения">
</form>
</div>