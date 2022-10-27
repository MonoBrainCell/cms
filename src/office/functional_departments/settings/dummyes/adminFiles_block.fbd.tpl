<div id="main_content">
<h1>Управление файлами папки &quot; {DIRECTORY_NAME} &quot;</h1>
<form name="view_directory_content" action="office/index.php?dep=settings&branch=adminFiles.php" method="post">
	<fieldset>
		<legend>Показать содержимое папки:</legend>
		<div class="form_elem">
			<select name="directory_view">
				{DIRECTORIES}
			</select>
			<input type="submit" name="submit_redaction" value="показать">
		</div>
	</fieldset>
</form>
{FILES_ELEMENT}
</div>

<div id="rename_elem_redact_form" class="modal_form">
	<form name="change_name" target="ajax" action="#" method="post">
	<input type="hidden" name="old_file_name" value="">
	<input type="hidden" name="directory" value="{DIRECTORY}">
		<h2>Новое Название файла</h2>
		<div class="form_elem">
			<span class="block_with_advice">
				<span class="view_advice" title="Пояснение"></span>
				<span class="advice">При указании нового имени файла ОБЯЗАТЕЛЬНО указывайте его расширение.
					<br>
					<span class="redaction_advice">Пожалуйста заполняйте данное поле латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> . </q>, <q> - </q>, <q> _ </q></span>
				</span>
			</span>
			<input type="text" name="new_file_name" value="" maxlength="1000">
			<span class="warning_sintaxis"></span>
			<span class="warning_lenght">Осталось символов: 1000</span>
		</div>
		<input type="submit" name="submit_redaction" value="Сохранить изменения">
	</form>
</div>
<div id="copy_elem_redact_form" class="modal_form">
	<form name="copy_file" target="ajax" action="#" method="post">
	<input type="hidden" name="old_file_name" value="">
	<input type="hidden" name="new_file_name" value="">
	<input type="hidden" name="old_directory" value="{DIRECTORY}">
		<fieldset>
			<legend>Скопировать файл в папку:</legend>
			<select name="directory">
				{DIRECTORIES}
			</select>
			<br><br>
			<input type="submit" name="submit_redaction" value="скопировать">
		</fieldset>
	</form>
</div>
<div id="replace_elem_redact_form" class="modal_form">
	<form name="replace_file" target="ajax" action="#" method="post">
	<input type="hidden" name="old_file_name" value="">
	<input type="hidden" name="new_file_name" value="">
	<input type="hidden" name="old_directory" value="{DIRECTORY}">
		<fieldset>
			<legend>Переместить файл в папку:</legend>
			<select name="directory">
				{DIRECTORIES}
			</select>
			<br><br>
			<input type="submit" name="submit_redaction" value="переместить">
		</fieldset>
	</form>
</div>
<div class="modal_form" id="remove_elem_dialog">{DIRECTORY}</div>
	
<span id="overlay_content"></span>
<div id="action_menu">
	<span class="action_menu_elem" id="rename_elem" title="Переименовывает файл">Переименовать файл</span>
	<span class="action_menu_elem" id="copy_elem" title="Копирует файл из текущей директории в указанную">Скопировать файл</span>
	<span class="action_menu_elem" id="replace_elem" title="Перемещает файл из текущей директории в указанную">Переместить файл</span>
	<span class="action_menu_elem" id="remove_elem" title="Удаляет файл из директории">Удалить файл</span>
</div>