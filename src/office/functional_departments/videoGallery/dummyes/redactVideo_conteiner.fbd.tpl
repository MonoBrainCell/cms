<div id="main_content">
<style type="text/css">
li {margin:10px auto;}
</style>
	<h1>Редактирование видео-вставки</h1>
	<span class="main_field">&nbsp;</span> - поле обязательно для заполнения
	<!--<ul>
		<li>Виджеты могут представлять из себя:
			<ul>
				<li>Только html-вставки рабочих заголовков для браузера (вставляется в контейнер тега head);</li>
				<li>Только html-вставки кода виджета (добавляется в любое место);</li>
				<li>Комбинированную вставку, т.е. будет добавляться html-код рабочих заголовков и html-код самого виджета.</li>
			</ul>
		</li>
		<li>Соответственно чтобы получить необходимый тип виджета заполняйте или оставляйте пустыми соответствующие поля.</li>
	</ul>-->
	<form name="video_redact" action="functional_departments/videoGallery/executors/contentSave.php" method="post" target="ajax">
	<input type="hidden" name="video_id" value="{VIDEO_ID}">
	<h2 class="main_field">Название видео-вставки</h2>
	<div class="form_elem">
		<span class="block_with_advice">
			<span class="view_advice" title="Пояснение"></span>
			<span class="advice">Содержание данного поля будет отображаться в списке видео-вставок (требуется для удобного нахождения). А также, при отображении видео на сайте (если в настройках была использована специальная строка)
			<br>
			<span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span>
			</span>
		</span>
		<input type="text" name="video_name" value="{VIDEO_NAME}" maxlength="75">
		<span class="warning_sintaxis"></span>
		<span class="warning_lenght">Осталось символов: 75</span>
	</div>
	<h2 class="main_field">Ширина видео-вставки</h2>
	<div class="form_elem">
		<span class="block_with_advice">
			<span class="view_advice" title="Пояснение"></span>
			<span class="advice">Содержание данного поля будет влиять на ширину видео-ставки на сайте. Демо видео-вставка на этой странице не подчиняется этой настройке.
			<br>
			<span class="redaction_advice">Пожалуйста заполняйте данное поле только цифрами</span>
			</span>
		</span>
		<input type="text" name="video_width" value="{VIDEO_WIDTH}">
		<span class="warning_sintaxis"></span>
	</div>
	<h2 class="main_field">Высота видео-вставки</h2>
	<div class="form_elem">
		<span class="block_with_advice">
			<span class="view_advice" title="Пояснение"></span>
			<span class="advice">Содержание данного поля будет влиять на высоту видео-ставки на сайте. Демо видео-вставка на этой странице не подчиняется этой настройке.
			<br>
			<span class="redaction_advice">Пожалуйста заполняйте данное поле только цифрами</span>
			</span>
		</span>
		<input type="text" name="video_height" value="{VIDEO_HEIGHT}">
		<span class="warning_sintaxis"></span>
	</div>
	{VARIABLE_PART}
	<input type="submit" name="submit_redaction" value="Сохранить изменения">
	</form>
</div>