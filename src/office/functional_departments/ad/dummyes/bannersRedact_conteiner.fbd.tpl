<div id="main_content">
<style type="text/css">
li {margin:10px auto;}
</style>
	<h1>Редактирование баннера</h1>
	<ul>
	<li>При переключении типа редактирования баннера, изменения, которые были внесены в данный момент не сохраняются;</li>
	<li>Чтобы создать в конструкторе текстовый баннер не заполняйте поле <q>Адрес изображения для баннера</q>;</li>
	<li>С помощью конструктора можно создать только &quot;баннеры-изображения&quot;(использующие статичную графику - изображения) и текстовые баннеры. Анимированные баннеры можно создавать только указав html-код данного баннера.</li>
	<li>При просмотре flash-файлов в новом окне следует учесть, что данный файл открывается не в своих истинных размерах, а &quot;подгоняется&quot; под размеры окна браузера, следовательно возможны искажения, при пободном просмотре.</li>
	<li>При указании html-шаблонов<br><span style="color:#900;">не следует использовать код html-сущностей в оригинальном виде</span>,<br> во избежание некорректного сохранения и дальнейшего использования html-шаблона.<br>
	<span style="color:#900;">Вместо html-сущности</span> следует использовать конструкцию<br><span style="color:#009;">&amp;[имя_сущности];</span>. Например &amp;[nbsp];
	</li>
	</ul>
	<form name="banner_redact" action="functional_departments/ad/executors/contentSave.php" method="post" target="ajax">
	<span id="view_available_images" title="Формирует список всех изображений предназначенных для использования в качестве баннеров. Также возможна вставка адреса данного изображения в соответствующее поле Конструктора баннера.">Показать доступные изображения</span>
	<span id="view_available_flashes" title="Формирует список всех flash-файлов (анимации) предназначенных для использования в качестве баннеров">Показать доступные flash-файлы (анимация)</span>
	<span id="view_available_pages" title="Формирует список всех страниц сайта. Также возможна вставка адреса данной страницы в соответствующее поле Конструктора баннера.">Показать страницы сайта</span>
	<h2 class="main_field">Название баннера</h2>
	<div class="form_elem">
	<input type="hidden" name="banner_id" value="{BAN_ID}">
		<span class="block_with_advice">
			<span class="view_advice" title="Пояснение"></span>
			<span class="advice">Содержание данного поля будет отображаться в списке баннеров (требуется для удобного нахождения).
			<br>
			<span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span>
			</span>
		</span>
		<input type="text" name="banner_name" value="{BAN_NAME}" maxlength="100">
		<span class="warning_sintaxis"></span>
		<span class="warning_lenght">Осталось символов: 100</span>
	</div>
	<h2 class="main_field">Тип редактирования баннера</h2>
	<div class="form_elem" id="redaction_dialog">
		<span class="block_with_advice">
			<span class="view_advice" title="Пояснение"></span>
			<span class="advice">Выбирая из этой пары Вы определяете с каким типом диалогового окна редактирования баннера Вы будете работать.<br>Конструктор - позволяет вводя ключевые и значимые элементы сгенерировать баннер автоматически (работает с текстовыми баннерами и баннерами-изображениями).<br>Введение кода - позволяет добавить любой html-код, который будет добавлен в качестве баннера, в соответствующий блок.
			</span>
		</span>
		<input type="radio" name="banner_type" value="constructor" id="constructor"{CONSTR_CHECKED}><label for="constructor">Конструктор</label>
		<input type="radio" name="banner_type" value="code" id="code"{CODE_CHECKED}><label for="code">Введение кода</label>
	</div>
	{VARIABLE_PART}
	<h2 class="main_field">Страницы для размещения баннера</h2>
	<div class="form_elem">
		<span class="block_with_advice" style="margin-right:0;">
			<span class="view_advice" title="Пояснение"></span>
			<span class="advice">Определяет на какой странице будет отображаться данный баннер.
			</span>
		</span>
		<select name="page_to_view" size="{COUNT_PAGES}" multiple="multiple">
		{PAGES_LIST}
		</select>
	</div>
	<input type="submit" name="submit_redaction" value="Сохранить изменения">
	</form>
</div>