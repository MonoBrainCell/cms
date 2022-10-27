<div id="main_content">
	<h1>Настройки отображения виджетов</h1>
	<p class="pay_attention">Обязательно! Ознакомьтесь, прежде чем производить изменения</p>
	<p>
	<span class="variable_name">{CONTAINER_CONTENT}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">кодом вставляемого виджета</span>.
	</p>
	<p>При указании html-шаблонов<br><span class="pay_attention">не следует использовать код html-сущностей в оригинальном виде</span>,<br> во избежание некорректного сохранения и дальнейшего использования html-шаблона.<br>
	<span class="pay_attention">Вместо html-сущности</span> следует использовать конструкцию<br><span class="variable_name">&amp;[имя_сущности];</span>. Например &amp;[nbsp];
	</p>
	<form name="tune_widgets" target="ajax" action="functional_departments/widgets/executors/saveTune.php" method="post">
	<div class="form_element">
	<h3>Оболочка виджета</h3>
	<textarea name="block_wrapper">{WW_WRAPPER}</textarea>
	</div>
	<input type="submit" name="submit_changes" value="Сохранить изменения">
	</form>
</div>