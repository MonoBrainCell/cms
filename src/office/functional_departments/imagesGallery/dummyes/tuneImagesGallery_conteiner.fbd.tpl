<div id="main_content">
	<h1>Настройки отображения вставки галереи изображений</h1>
	<p class="pay_attention">Обязательно! Ознакомьтесь, прежде чем производить изменения</p>
	<p>
	<span class="variable_name">{CONTAINER_CONTENT}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">изображениями определённой галереи</span>.
	</p>
	<p>При указании html-шаблонов<br><span class="pay_attention">не следует использовать код html-сущностей в оригинальном виде</span>,<br> во избежание некорректного сохранения и дальнейшего использования html-шаблона.<br>
	<span class="pay_attention">Вместо html-сущности</span> следует использовать конструкцию<br><span class="variable_name">&amp;[имя_сущности];</span>. Например &amp;[nbsp];
	</p>
	<form name="tune_widgets" target="ajax" action="functional_departments/imagesGallery/executors/saveTune.php" method="post">
	<h3>Html-код рабочих заголовков (для браузера)</h3>
	<span id="view_available_css" title="Формирует список всех файлов каскадных таблиц стилей (CSS), предназначенных для корректного отображения контента галереи">CSS (Cascading Style Sheets)</span>
	<span id="view_available_js" title="Формирует список всех javascript-файлов, предназначенных для корректного отображения контента галереи">Javascript</span>
	<div class="form_element">
	<textarea name="browser_headers">{BROWSER_HEADERS}</textarea>
	</div>
	<h3 class="main_field">Html-код отдельной галереи</h3>
	<div class="form_element">
	<textarea name="html_separate_gallery">{SEPARATE_CODE}</textarea>
	</div>
	<h3 class="main_field">Html-код галереи, встроенной в текст</h3>
	<div class="form_element">
	<textarea name="html_built-in_gallery">{BUILT-IN_CODE}</textarea>
	</div>
	<input type="submit" name="submit_changes" value="Сохранить изменения">
	</form>
</div>