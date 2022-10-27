<div id="main_content">
<style type="text/css">
li {margin:10px auto;}
</style>
	<h1>Редактирование виджета</h1>
	<ul>
		<li>Виджеты могут представлять из себя:
			<ul>
				<li>Только html-вставки рабочих заголовков для браузера (вставляется в контейнер тега head);</li>
				<li>Только html-вставки кода виджета (добавляется в любое место);</li>
				<li>Комбинированную вставку, т.е. будет добавляться html-код рабочих заголовков и html-код самого виджета.</li>
			</ul>
		</li>
		<li>Соответственно чтобы получить необходимый тип виджета заполняйте или оставляйте пустыми соответствующие поля.</li>
		<li>При указании html-шаблонов<br><span style="color:#900;">не следует использовать код html-сущностей в оригинальном виде</span>,<br> во избежание некорректного сохранения и дальнейшего использования html-шаблона.<br>
	<span style="color:#900;">Вместо html-сущности</span> следует использовать конструкцию<br><span style="color:#009;">&amp;[имя_сущности];</span>. Например &amp;[nbsp];
	</li>
	</ul>
	<form name="widget_redact" action="functional_departments/widgets/executors/contentSave.php" method="post" target="ajax">
	<span id="view_available_flashes" title="Формирует список всех Javascript-файлов предназначенных для отображения виджетов">Javascript (Js)</span>
	<span id="view_available_pages" title="Формирует список всех файлов каскадных таблиц стилей (CSS) предназначенных для отображения виджетов">CSS (Cascading Style Sheets)</span>
	<h2 class="main_field">Название виджета</h2>
	<div class="form_elem">
	<input type="hidden" name="widget_id" value="{WIDGET_ID}">
		<span class="block_with_advice">
			<span class="view_advice" title="Пояснение"></span>
			<span class="advice">Содержание данного поля будет отображаться в списке виджетов (требуется для удобного нахождения).
			<br>
			<span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span>
			</span>
		</span>
		<input type="text" name="widget_name" value="{WID_NAME}" maxlength="75">
		<span class="warning_sintaxis"></span>
		<span class="warning_lenght">Осталось символов: 75</span>
	</div>
	
	<h2>Html-код рабочих заголовков (для браузера)</h2>
	<div class="form_elem">
		<span class="block_with_advice" style="margin-right:0;">
			<span class="view_advice" title="Пояснение"></span>
			<span class="advice">Содержание данного поля будет использовано как html-код с рабочими заголовками отправляемыми браузеру для адекватного отображения контента.
			</span>
		</span>
		<textarea name="headers_code">{HEADERS_CODE}</textarea>
	</div>
	<h2>Html-код виджета (для отображения)</h2>
	<div class="form_elem">
		<span class="block_with_advice" style="margin-right:0;">
			<span class="view_advice" title="Пояснение"></span>
			<span class="advice">Содержание данного поля будет использовано как исходный html-код виджета.
			</span>
		</span>
		<textarea name="body_code">{BODY_CODE}</textarea>
	</div>

	<input type="submit" name="submit_redaction" value="Сохранить изменения">
	</form>
</div>