<div id="main_content">
	<h1>Настройки новостного контента</h1>
	<p class="pay_attention">Обязательно! Ознакомьтесь, прежде чем производить изменения</p>
	<p>
	<span class="variable_name">{CONTAINER_CONTENT}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">генерируемым контентом</span>:
	<br>
	 Для <span class="pay_attention"><q>Шаблона оболочки списка новостей</q></span> - это весь список новостей для данной страницы;
	<br>
	Для <span class="pay_attention"><q>Шаблона отображения краткого содержания новости</q></span> - это урезанный до определённой длины новостной текст;
	<br>
	Для <span class="pay_attention"><q>Шаблона оболочки навигации по новостным страницам</q></span> - это номера всех доступных страниц со списком новостей;
	<br>
	Для <span class="pay_attention"><q>Шаблона отображения новости</q></span> - это полный контент новости, со всеми элементами вёрстки текста;
	</p>
	<p>
	<span class="variable_name">{TIME}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">датой добавления указанной новости</span>.
	</p>
	<p>
	<span class="variable_name">{NEWS_HEADER}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">новостным заголовком.</span>
	</p>
	<p>
	<span class="variable_name">{ANCHOR_TO_FULL_NEWS}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">ссылкой на полный контент новости.</span> (В случае его наличия)
	</p>
	<p>
	<span class="variable_name">{NEWS_ITEM_ADDRESS}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">адресом, где располагается полный контент указанной новости.</span>
	</p>
	<p>
	<span class="variable_name">{NUMERAL_NAVIGATION}</span> - ключевая фраза, которая будет замещаться в оболочке списка новостей <span class="pay_attention">сгенерированной навигацией по страницам новостного блока</span>.
	</p>
	<p>
	<span class="variable_name">{PAGE_NUMBER}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">номером страницы списка новостей.</span>
	</p>
	<p>
	<span class="variable_name">{PAGE_ADDRESS}</span> - ключевая фраза, которая будет замещаться <span class="pay_attention">адресом, на другие страницы списка новостей.</span>
	</p>
	<p>При указании html-шаблонов<br><span class="pay_attention">не следует использовать код html-сущностей в оригинальном виде</span>,<br> во избежание некорректного сохранения и дальнейшего использования html-шаблона.<br>
	<span class="pay_attention">Вместо html-сущности</span> следует использовать конструкцию<br><span class="variable_name">&amp;[имя_сущности];</span>. Например &amp;[nbsp];
	</p>
	<form name="tune_news" target="ajax" action="functional_departments/news/executors/saveTune.php" method="post">
	<input type="hidden" name="type_of_tune" value="{NAVIGATION_ID}">
	<span id="view_available_css" title="Формирует список всех файлов каскадных таблиц стилей (CSS), предназначенных для корректного отображения новостного контента">CSS (Cascading Style Sheets)</span>
	<span id="view_available_js" title="Формирует список всех javascript-файлов, предназначенных для корректного отображения новостного контента">Javascript</span>
	{VARIABLE_PART}
	<input type="submit" name="submit_changes" value="Сохранить изменения">
	</form>
</div>