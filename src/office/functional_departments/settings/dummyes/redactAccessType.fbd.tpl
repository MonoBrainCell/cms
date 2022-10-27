<div id="main_content">
<h1>Изменить Логин</h1>
<h2>Пользователь: <span style="border-bottom:2px dashed #f00;">{LOGIN}</span><br>Доступ: <span style="border-bottom:2px dashed #f00;">{ACCESS_TYPE}</span></h2>
<span class="main_field">&nbsp;</span> - поле обязательно для заполнения
<form name="develop_content" method="post" action="functional_departments/settings/executors/saveAccessType.php" target="ajax">
<input type="hidden" name="login" value="{LOGIN}">
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
<h2 class="main_field">Введите пароль Управляющего:</h2>
<div class="form_elem">
<span class="block_with_advice">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Необходимо для подтверждения того, что Вы является Управляющим администратором сайта (Для администраторов другого типа данный функционал заблокирован).
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