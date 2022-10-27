<div id="main_content">
<style type="text/css">
li {margin:10px auto;}
</style>
	<h1>Выбор дизайна сайта</h1>
	<form name="widget_redact" action="functional_departments/design/executors/selectedDesignSave.php" method="post" target="ajax">
	<div class="form_elem">
		<span class="block_with_advice" style="margin-right:0">
			<span class="view_advice" title="Пояснение"></span>
			<span class="advice">Выберите в выпадающем списке дизайн сайта</span>
			</span>
		</span>
		<select name="design">
		{DESIGNS}
		</select>
	</div>
	<input type="submit" name="submit_redaction" value="Сохранить изменения">
	</form>
</div>