<div id="main_content">
<style type="text/css">
li {margin:10px auto;}
</style>
	<h1>Редактирование html-кода активного шаблона</h1>
	<p>При указании html-шаблонов<br><span style="color:#900;">не следует использовать код html-сущностей в оригинальном виде</span>,<br> во избежание некорректного сохранения и дальнейшего использования html-шаблона.<br>
	<span style="color:#900;">Вместо html-сущности</span> следует использовать конструкцию<br><span  style="color:#009;">&amp;[имя_сущности];</span>. Например &amp;[nbsp];
	</p>
	<form name="widget_redact" action="functional_departments/design/executors/contentSave.php" method="post" target="ajax">
	<div class="form_elem">
		<span class="block_with_advice" style="margin-right:0">
			<span class="view_advice" title="Пояснение"></span>
			<span class="advice">В этом поле можно отредактировать html-шаблон выбранного дизайна.<br>
			<span style="color: #900;">Не следует проводить редактирование не имея знаний в области вёрстки страниц сайта (html и css)</span>
			</span>
		</span>
		<textarea name="html_code" style="height: 800px;">{HTML_CODE}</textarea>
	</div>
	<input type="submit" name="submit_redaction" value="Сохранить изменения">
	</form>
</div>