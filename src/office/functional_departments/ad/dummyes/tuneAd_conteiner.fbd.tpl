<div id="main_content">
	<h1>Настройки блока баннеров (рекламного блока)</h1>
	<p class="pay_attention">Обязательно! Ознакомьтесь, прежде чем производить изменения</p>
	<p>
	<span class="variable_name">{CONTAINER_CONTENT}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">содержимым блока баннеров (для <q>Оболочка блока баннеров</q>) или кодом баннера (для <q>Оболочка баннера</q>)</span>.
	</p>
	<p>
	<span class="variable_name">{TARGET_HREF}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">адресом страницы, на которую указывает баннер.</span>
	</p>
	<p>
	<span class="variable_name">{TITLE_HINT}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">атрибутом для отображения всплывающей подсказки (title)</span> (т.е. текст который будет появляться при наведении курсора мыши на баннер).
	</p>
	<p>
	<span class="variable_name">{HINT}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">Рекламным текстом баннера (для изображения этот текст рекомендуется использовать, как значение атрибута alt)</span>.
	</p>
	<p>
	<span class="variable_name">{IMAGE_ADDR}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">адресом изображения, являющегося визуальным баннером</span> (необходим только для &quot;Баннеров-изображений&quot;).
	</p>
	<p>При указании html-шаблонов<br><span class="pay_attention">не следует использовать код html-сущностей в оригинальном виде</span>,<br> во избежание некорректного сохранения и дальнейшего использования html-шаблона.<br>
	<span class="pay_attention">Вместо html-сущности</span> следует использовать конструкцию<br><span class="variable_name">&amp;[имя_сущности];</span>. Например &amp;[nbsp];
	</p>
	
	<form name="tune_banners" target="ajax" action="functional_departments/ad/executors/saveTune.php" method="post">
	<div class="form_element">
	<h3>Оболочка блока баннеров</h3>
	<textarea name="block_wrapper">{BW_WRAPPER}</textarea>
	</div>
	<div class="form_element">
	<h3>Оболочка баннера (какие теги будут сопутствовать или содержать сгенерированный баннер)</h3>
	<textarea name="banner_wrapper">{BAN_WRAPPER}</textarea>
	</div>
	<div class="form_element">
	<h3>Шаблон &quot;баннер-изображение&quot;</h3>
	<textarea name="banner_image_skin">{BAN_IMG_SKIN}</textarea>
	</div>
	<div class="form_element">
	<h3>Шаблон &quot;текстовый баннер&quot;</h3>
	<textarea name="banner_text_skin">{BAN_TEXT_SKIN}</textarea>
	</div>
	<input type="submit" name="submit_changes" value="Сохранить изменения">
	</form>
</div>