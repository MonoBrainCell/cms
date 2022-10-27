<h2 class="main_field">Название страницы в навигации</h2>
<div class="form_elem">
<span class="block_with_advice">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Содержание данного поля будет участвовать в формировании навигации по сайту.
	<br>
	<span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span>
	</span>
</span>
<input type="text" name="page_navigation_name" value="{PAGE_NAME}" maxlength="100">
<span class="warning_sintaxis"></span>
<span class="warning_lenght">Осталось символов: 100</span>
</div>

<h2 class="main_field">Alias страницы (Адрес страницы)</h2>
<div class="form_elem">
<span class="block_with_advice">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Содержание данного поля будет участвовать в формировании адреса страницы. Например, указав alias страницы o_nas, страница будет располагаться по адресу http://domen_name.ru/page/o_nas
	<br>
	<strong>Если Вы не не указали alias, при создании новой страницы, то он будет сформирован автоматически из содержания поля <q>Название страницы в навигации</q></strong>
	<br>
	<span class="redaction_advice">Пожалуйста заполняйте данное поле латинскими символами и цифрами, также можете использовать: <q> - </q>, <q> _ </q> </span>
	</span>
</span>
<input type="text" name="page_alias" value="{ALIAS}" maxlength="150">
<span class="warning_sintaxis"></span>
<span class="warning_lenght">Осталось символов: 150</span>
</div>

<h2 class="main_field">Родительская страница</h2>
<div class="form_elem">
<span class="block_with_advice" style="margin-right:0;">
	<span class="view_advice" title="Пояснение"></span>
	<span class="advice">Определяет к какой странице текущая будет привязана и из какой страницы будет доступна.
	</span>
</span>
<select name="page_parent" size="{COUNT_PAGES}">
{PAGES_LIST}
</select>
</div>