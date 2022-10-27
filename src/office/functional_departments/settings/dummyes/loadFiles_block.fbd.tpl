<div id="main_content">
<p style='color:#900;'>{ERRORS_MESSAGE}</p>
<h1>Загрузите файлы для различных функций сайта</h1>
<h2>Максимальный размер одного файла: <strong>{MAX_FILESIZE}</strong></h2>
<h2>Максимальный количество файлов: <strong>{MAX_FILES_QUANTITY}</strong></h2>
<h3 style='color:#660;'>Обязательно обращайте внимание на сообщение появляющееся после попытки загрузки файлов. Если попытка была произведена, но никакого сообщения не отобразилось, то скорее всего был превышен общий лимит объёма передаваемых данных. В таком случае имеет место поэтапная загрузка группы файлов.</h3>
<form name="list_redact" action="functional_departments/settings/executors/checkFilesOnSite.php" method="post" target="ajax" enctype="multipart/form-data">
<div id="gallery_redact_block">
<div class="gallery_element">
	<span class="replace_button"></span>
	<div class="form_elem">
		<span class="block_with_advice">
			<span class="view_advice" title="Пояснение"></span>
			<span class="advice">Следует обязательно выбирать функционал для которого загружаются файлы.
			</span>
		</span>
		<input type="file" name="file">
		<select name="directory">
		{DIRECTORIES}
		</select>
	</div>
</div>
<div id="more_images">Добавить файл</div>
</div>

<input type="submit" name="submit_redaction" value="загрузить файлы">
</form>
</div>

<div class="modal_form" id="remove_elem_dialog">
	<p>Удалить данный элемент?</p>
</div>
	
<span id="overlay_content"></span>
<div id="action_menu">
	<span class="action_menu_elem" id="remove_elem" title="Удаляет из списка указанный элемент">Удалить элемент</span>
</div>