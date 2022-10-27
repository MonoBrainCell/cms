<div id="main_content">
<h1>Создать новую учетную запись администратора</h1>
<span class="main_field">&nbsp;</span> - поле обязательно для заполнения
<form name="develop_content" method="post" action="functional_departments/settings/executors/saveNewAccess.php" target="ajax">
<h2 class="main_field">Пароль Управляющего</h2>
<div class="form_elem">
<span class="block_with_advice">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Введите пароль Управляющего сайтом. Требуется для подтверждения прав на изменение авторизационных данных.
	<br>
	<span class="redaction_advice">Используются только латинические буквы, числа и символы <q>_</q>, <q>-</q>, <q>#</q>, <q>!</q>, <q>@</q></span>
	</span>
</span>
<input type="password" name="password" maxlength="25">
<span class="warning_sintaxis"></span>
<span class="warning_lenght">Осталось символов: 25</span>
</div>
<h2 class="main_field">Тип доступа</h2>
<div class="form_elem">
	<span class="block_with_advice">
		<span class="view_advice" title="Пояснение"></span>
		<span class="advice">Выберите тип доступа, который будет характерен в дальнейшем для этой учетной записи.
		<br>
		<span class="redaction_advice">При изменении типа доступа произойдёт изменение в функциональных возможностях работы с сайтом, указанной учетной записи</span>
		</span>
	</span>
	<select name="access_type">
		{OPTIONS}
	</select>
</div>
<h2 class="main_field">Логин</h2>
<div class="form_elem">
<span class="block_with_advice">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Введите Логин, который Вы хотите использовать при входе в админ. часть сайта
	<br>
	<span class="redaction_advice">Используются только латинические буквы, числа и символы <q>_</q>, <q>-</q>, <q>#</q>, <q>!</q>, <q>@</q></span>
	</span>
</span>
<input type="text" name="login" maxlength="15">
<span class="warning_sintaxis"></span>
<span class="warning_lenght">Осталось символов: 15</span>
</div>

<h2 class="main_field">Введите пароль:</h2>
<div class="form_elem">
<span class="block_with_advice">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Введите пароль. Если изменение будет произведено успешно, то именно этот пароль в сочетании с указанным выше логином будет обеспечивать доступ в админ. часть сайта.
	<br>
	<span class="redaction_advice">Используются только латинические буквы, числа и символы <q>_</q>, <q>-</q>, <q>#</q>, <q>!</q>, <q>@</q></span>
	</span>
</span>
<input type="password" name="new_password" maxlength="25">
<span class="warning_sintaxis"></span>
<span class="warning_lenght">Осталось символов: 25</span>
</div>
<h2 class="main_field">Повторите пароль:</h2>
<div class="form_elem">
<span class="block_with_advice">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Повторите пароль. Необходимо для того, чтобы удостоверится, что при введении пароля не закралась ошибка.
	<br>
	<span class="redaction_advice">Используются только латинические буквы, числа и символы <q>_</q>, <q>-</q>, <q>#</q>, <q>!</q>, <q>@</q></span>
	</span>
</span>
<input type="password" name="repeat_new_password" maxlength="25">
<span class="warning_sintaxis"></span>
<span class="warning_lenght">Осталось символов: 25</span>
</div>
<input type="submit" name="submit_redaction" value="Сохранить изменения">
</form>

<h3>Генерирование случайного пароля</h3>
<form name="gen_password" method="post" action="functional_departments/settings/executors/genRandomPassword.php" target="ajax">
<h2>Укажите количество символов (минимум 8, максимум 25):</h2>
<div class="form_elem">
<input type="text" name="quantity" maxlength="2">
</div>
<input type="submit" name="submit_redaction" value="Сгенерировать пароль">
</form>
</div>