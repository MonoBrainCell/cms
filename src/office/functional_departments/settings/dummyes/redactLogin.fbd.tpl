<div id="main_content">
<h1>Изменить Логин</h1>
<h2>Пользователь: <span style="border-bottom:2px dashed #f00;">{LOGIN}</span><br>Доступ: <span style="border-bottom:2px dashed #f00;">{ACCESS_TYPE}</span></h2>
<span class="main_field">&nbsp;</span> - поле обязательно для заполнения
<form name="develop_content" method="post" action="functional_departments/settings/executors/saveLogin.php" target="ajax">
<input type="hidden" name="old_login" value="{LOGIN}">
<h2 class="main_field">Новый логин</h2>
<div class="form_elem">
<span class="block_with_advice">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Введите Логин, который Вы хотите использовать при входе в админ. часть сайта
	<br>
	<span class="redaction_advice">Используются только латинические буквы, числа и символы <q>_</q>, <q>-</q>, <q>#</q>, <q>!</q>, <q>@</q></span>
	</span>
</span>
<input type="text" name="new_login" maxlength="15">
<span class="warning_sintaxis"></span>
<span class="warning_lenght">Осталось символов: 15</span>
</div>
<h2 class="main_field">Введите пароль Управляющего:</h2>
<div class="form_elem">
<span class="block_with_advice">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Введите пароль Управляющего, чтобы подтвердить права на изменения логина
	<br>
	<span class="redaction_advice">Используются только латинические буквы, числа и символы <q>_</q>, <q>-</q>, <q>#</q>, <q>!</q>, <q>@</q></span>
	</span>
</span>
<input type="password" name="password" maxlength="25">
<span class="warning_sintaxis"></span>
<span class="warning_lenght">Осталось символов: 25</span>
</div>
<input type="submit" name="submit_redaction" value="Сохранить изменения">
</form>
</div>