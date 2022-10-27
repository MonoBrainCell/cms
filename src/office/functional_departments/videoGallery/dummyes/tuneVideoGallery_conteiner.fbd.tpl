<div id="main_content">
	<h1>Настройки отображения видео-вставок</h1>
	<p class="pay_attention">Обязательно! Ознакомьтесь, прежде чем производить изменения</p>
	<p>
	<span class="variable_name">{VIDEO_NAME}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">названием указанной видео-вставки</span>.
	</p>
	<p>
	<span class="variable_name">{VIDEO_SOURCE}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">адресом видео-ресурса</span>.
	</p>
	<p>
	<span class="variable_name">{VIDEO_WIDTH}</span> - ключевая фраза, которая будет влиять на <span class="pay_attention">ширину окна с отображаемым видео</span>.
	</p>
	<p>
	<span class="variable_name">{VIDEO_HEIGHT}</span> - ключевая фраза, которая будет влиять на <span class="pay_attention">высоту окна с отображаемым видео</span>.
	</p>
	<p>
	Также, важно иметь ввиду, что вставка видео из видео-хостингов в большинстве случаев производится с помощью тега <span class="variable_name">iframe</span>.
	</p>
	<p>При указании html-шаблонов<br><span class="pay_attention">не следует использовать код html-сущностей в оригинальном виде</span>,<br> во избежание некорректного сохранения и дальнейшего использования html-шаблона.<br>
	<span class="pay_attention">Вместо html-сущности</span> следует использовать конструкцию<br><span class="variable_name">&amp;[имя_сущности];</span>. Например &amp;[nbsp];
	</p>
	<form name="tune_widgets" target="ajax" action="functional_departments/videoGallery/executors/saveTune.php" method="post">
	<h3>Html-код рабочих заголовков (для браузера)</h3>
	<span id="view_available_css" title="Формирует список всех файлов каскадных таблиц стилей (CSS), предназначенных для корректного отображения видео-вставки">CSS (Cascading Style Sheets)</span>
	<span id="view_available_js" title="Формирует список всех javascript-файлов, предназначенных для корректного отображения видео-вставки">Javascript</span>
	<div class="form_element">
	<textarea name="browser_headers">{BROWSER_HEADERS}</textarea>
	</div>
	<h3 class="main_field">Html-код видео-вставки</h3>
	<div class="form_element">
	<textarea name="html_video">{VIDEO_CODE}</textarea>
	</div>
	<input type="submit" name="submit_changes" value="Сохранить изменения">
	</form>
</div>