<div id="main_content">
<h1>Редактирование записи из гостевой книги</h1>
<span class="main_field">&nbsp;</span> - поле обязательно для заполнения
<form name="develop_content" method="post" action="functional_departments/guestbook/executors/contentSave.php" target="ajax"> 
<input type="hidden" name="rec_id" value="{HIDDEN_ID}">
<h3>Дата добавления записи: <strong>{ADDED_DATE}</strong></h3>
<h3>E-mail гостя: <strong>{GUEST_EMAIL}</strong></h3>
<h2 class="main_field">Имя гостя</h2>
<div class="form_elem">
<span class="block_with_advice">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Здесь можно провести корректировку ИМЕНИ гостя, оставившего данное сообщение.
	<br>
	<span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span>
	</span>
</span>
<input type="text" name="guest_name" value="{GUEST_NAME}" maxlength="50">
<span class="warning_sintaxis"></span>
<span class="warning_lenght">Осталось символов: 50</span>
</div>

<h2 class="main_field">Содержание записи</h2>
<div class="form_elem">
<span class="block_with_advice" style="margin-right:0;">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Содержание данного поля - это сообщение оставленное гостем. Его можно корректировать, внося изменения в текст выведенный внутри соответствующего поля.
	<br>
	<span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span>
	</span>
	</span>
</span>
<textarea id="content_text" name="content_text" maxlength="150" style="margin-left:0">{CONTENT}</textarea>
<span class="warning_sintaxis"></span>
<span class="warning_lenght">Осталось символов: 150</span>
</div>
<input type="submit" name="submit_redaction" value="Сохранить изменения">
</form>
</div>