<div id="main_content">
<style type="text/css">
#view_available_images,#view_available_flashes,#view_available_pages {margin-top: 20px;}
#view_available_images {margin-right:360px;}
.pay_attention {color:#900; text-transform: uppercase;}
.variable_name {color:#009; font-weight: 700;}
</style>
	<h1>Редактирование формы on-line калькулятора</h1>
	<p style="color: #b00;">Работа с кодом on-line калькулятора стомости (изменение) предполагает наличие навыков и знаний в области html-вёрстки.</p>
	<p>При заполнении данных о html-форме<br><span class="pay_attention">не следует использовать тег textarea</span>,<br> во избежание некорректного сохранения и дальнейшего использования формы.<br>
	<span class="pay_attention">Вместо названия тега textarea</span> следует использовать конструкцию <span class="variable_name">[textfield]</span>.
	</p>
	<p>При указании html-шаблонов<br><span class="pay_attention">не следует использовать код html-сущностей в оригинальном виде</span>,<br> во избежание некорректного сохранения и дальнейшего использования html-шаблона.<br>
	<span class="pay_attention">Вместо html-сущности</span> следует использовать конструкцию<br><span class="variable_name">&amp;[имя_сущности];</span>. Например &amp;[nbsp];
	</p>
	<form name="feedback_redact" action="functional_departments/costEngine/executors/contentSave.php" method="post" target="ajax">
	<span id="view_available_images" title="Формирует список всех Javascript-файлов предназначенных для отображения виджетов">Php-обработчики</span>
	<span id="view_available_flashes" title="Формирует список всех Javascript-файлов предназначенных для отображения виджетов">Javascript (Js)</span>
	<span id="view_available_pages" title="Формирует список всех файлов каскадных таблиц стилей (CSS) предназначенных для отображения виджетов">CSS (Cascading Style Sheets)</span>
	<div style="width:auto; height:100px; overflow:hidden;"></div>
	<h2>Html-код рабочих заголовков (для браузера)</h2>
	<div class="form_elem">
		<span class="block_with_advice" style="margin-right:0;">
			<span class="view_advice" title="Пояснение"></span>
			<span class="advice">Содержание данного поля будет использовано как html-код с рабочими заголовками отправляемыми браузеру для адекватного отображения контента.
			</span>
		</span>
		<textarea name="headers_code">{HEADERS_CODE}</textarea>
	</div>
	<h2>Html-код формы калькулятора (для отображения)</h2>
	<div class="form_elem">
		<span class="block_with_advice" style="margin-right:0;">
			<span class="view_advice" title="Пояснение"></span>
			<span class="advice">Содержание данного поля будет использовано как исходный html-код формы калькулятора.
			</span>
		</span>
		<textarea name="body_code">{BODY_CODE}</textarea>
	</div>

	<input type="submit" name="submit_redaction" value="Сохранить изменения">
	</form>
</div>