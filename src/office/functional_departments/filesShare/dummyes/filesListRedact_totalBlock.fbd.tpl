<div id="main_content">
{H1}
<form name="list_redact" action="functional_departments/filesShare/executors/saveFilesList.php" method="post" target="ajax">
<input type="hidden" name="files_list_id" value="{FL_ID}">
<h3 class='main_field'>Имя списка файлов для скачивания:</h3>
<div class="form_elem">
<span class="block_with_advice">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Содержание данного поля будет видно как название списка файлов (требуется для удобной ориентации в реестре списков файлов)
	<br>
	<span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span>
	</span>
</span>
<input type="text" name="list_name" maxlength="50" value="{FL_NAME}">
<span class="warning_sintaxis"></span>
<span class="warning_lenght">Осталось символов: 50</span>
</div>
<div id="gallery_redact_block">
{VARIABLE_PART}
<div id="more_images">Добавить файл</div>
</div>

<input type="submit" name="submit_redaction" value="Сохранить изменения">
</form>
</div>

<div class="modal_form" id="insert_before_dialog">
	<p>Выберите элемент перед которым необходимо разместить выбранный заранее элемент.<br>Доступные для выбора элементы будут иметь иконку в виде дугообразной стрелочки.</p>
</div>
<div class="modal_form" id="insert_after_dialog">
	<p>Выберите элемент после которого необходимо разместить выбранный заранее элемент.<br>Доступные для выбора элементы будут иметь иконку в виде дугообразной стрелочки.</p>
</div>
<div class="modal_form" id="remove_elem_dialog">
	<p>Удалить данный элемент?</p>
</div>
	
<span id="overlay_content"></span>
<div id="action_menu">
	<span class="action_menu_elem" id="insert_before" title="Перемещаете указанный элемент до выбранного Вами">Переместить элемент до . . .</span>
	<span class="action_menu_elem" id="insert_after" title="Перемещаете указанный элемент после выбранного Вами">Переместить элемент после . . .</span>
	<span class="action_menu_elem" id="remove_elem" title="Удаляет из списка указанный элемент">Удалить элемент</span>
</div>