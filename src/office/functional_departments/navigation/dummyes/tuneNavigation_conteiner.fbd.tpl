<div id="main_content">
	<h1>Дополнительные настройки навигации по сайту</h1>
	<p class="pay_attention">Обязательно! Ознакомьтесь, прежде чем производить изменения</p>
	<p>
	<span class="variable_name">{CONTAINER_CONTENT}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">группой ссылок являющихся дочерними для данного контейнера</span>.
	</p>
	<p>
	<span class="variable_name">{ANCHOR_NAME}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">названием страницы на которую ведёт данная ссылка</span> (т.е. текст с которым ссылка будет отображаться в навигации).
	</p>
	<p>
	<span class="variable_name">{ANCHOR_HREF}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">адресом страницы, на которую указывает данная ссылка.</span>
	</p>
	<p>При указании html-шаблонов<br><span class="pay_attention">не следует использовать код html-сущностей в оригинальном виде</span>,<br> во избежание некорректного сохранения и дальнейшего использования html-шаблона.<br>
	<span class="pay_attention">Вместо html-сущности</span> следует использовать конструкцию<br><span class="variable_name">&amp;[имя_сущности];</span>. Например &amp;[nbsp];
	</p>
	<form name="tune_navigation" target="ajax" action="functional_departments/navigation/executors/saveTune.php" method="post">
	<input type="hidden" name="type_of_tune" value="{NAVIGATION_ID}">
	{VARIABLE_PART}
	<input type="submit" name="submit_changes" value="Сохранить изменения">
	</form>
</div>