<div id="main_content">
	<h1>Настройки гостевой книги</h1>
	<p class="pay_attention">Обязательно! Ознакомьтесь, прежде чем производить изменения</p>
	<p style="color: #b00;">
   Работа с кодом гостевой книги предполагает наличие навыков и знаний в области html-вёрстки.
	</p>
	<p>
	<span class="variable_name">{CONTAINER_CONTENT}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">генерируемым контентом</span>:
	<br>
	 Для <span class="pay_attention"><q>Шаблона оболочки списка записей</q></span> - это весь список записей для данной страницы;
	<br>
	Для <span class="pay_attention"><q>Шаблона оболочки навигации по страницам гостевой книги</q></span> - это номера всех доступных страниц книги;
	<br>
	Для <span class="pay_attention"><q>Шаблона отображения записи в гостевой книге</q></span> - это полный текст гостевой записи;
	</p>
	<p>
	<span class="variable_name">{GUESTBOOK_FORM}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">html-формой, позволяющей посетителю сайта добавить своё сообщение в гостевую книгу</span>.
	</p>
	<p>
	<span class="variable_name">{NUMERAL_NAVIGATION}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">сгенерированной навигацией по страницам гостевой книги</span>.
	</p>
	<p>
	<span class="variable_name">{PAGE_NUMBER}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">номером страницы гостевой книги.</span>
	</p>
	<p>
	<span class="variable_name">{PAGE_ADDRESS}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">адресом, на другие страницы гостевой книги.</span>
	</p>
	<p>При заполнении данных о html-форме<br><span class="pay_attention">не следует использовать тег textarea</span>,<br> во избежание некорректного сохранения и дальнейшего использования формы.<br>
	<span class="pay_attention">Вместо названия тега textarea</span> следует использовать конструкцию <span class="variable_name">[textfield]</span>.
	</p>
	<hr>
	<p>
	<span class="variable_name">{GUEST_NAME}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">именем посетителя добавившего запись в книгу</span>.
	</p>
	<p>
	<span class="variable_name">{TIME}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">датой добавления указанной записи</span>.
	</p>
	<p>При указании html-шаблонов<br><span class="pay_attention">не следует использовать код html-сущностей в оригинальном виде</span>,<br> во избежание некорректного сохранения и дальнейшего использования html-шаблона.<br>
	<span class="pay_attention">Вместо html-сущности</span> следует использовать конструкцию<br><span class="variable_name">&amp;[имя_сущности];</span>. Например &amp;[nbsp];
	</p>
	<form name="tune_guestbook" target="ajax" action="functional_departments/guestbook/executors/saveTune.php" method="post">
	<input type="hidden" name="type_of_tune" value="{NAVIGATION_ID}">
	<span id="view_available_css" title="Формирует список всех файлов каскадных таблиц стилей (CSS), предназначенных для корректного отображения гостевой книги">CSS (Cascading Style Sheets)</span>
	<span id="view_available_js" title="Формирует список всех javascript-файлов, предназначенных для корректного отображения гостевой книги">Javascript</span>
	<span id="view_available_php" title="Формирует список всех php-файлов, предназначенных для обработки получаемых записей">PHP</span>
	{VARIABLE_PART}
	<input type="submit" name="submit_changes" value="Сохранить изменения">
	</form>
</div>