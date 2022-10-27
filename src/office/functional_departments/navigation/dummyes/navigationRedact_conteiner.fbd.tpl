<div id="main_content">
<style type="text/css">
li {margin: 7px auto; font-style: italic;}
</style>
	<h1>Редактирование навигации</h1>
	<ul>
	<li>Чтобы переместить элемент в корень основной или дополнительной навигации, используйте функционал <q>Переназначить родителя страницы</q> (определите в качестве родителя, соответственно пункт <q>Основная навигация</q> или <q>Дополнительная навигация</q>)</li>
	<li>При работе с одним разделом навигации, например <q>Основная навигация</q>, можно перемещать элементы в другой раздел навигации, например в <q>Дополнительную навигацию</q>. Используйте функционал <q>Переназначить родителя страницы</q> (определите в качестве родителя, соответственно пункт <q>Основная навигация</q> или <q>Дополнительная навигация</q>)</li>
	<li>При перемещении элементов, содержащих дочерние, в соседние разделы навигации (например, из <q>Основной</q> в <q>Дополнительную</q>) <strong style="color:#900;">настойчиво рекомендуется</strong> обновить страницу редактирования навигации.</li>
	</ul>
	<div class="navigation_conteiner" id='{NAVIGATION_ID}-navigation'>
		<span class='navigation_row no_remoove' id='page_number_advanced'>
			<span class="navigation_elem">
				<span class='page_name no_rewrite'>Дополнительная навигация</span>
				<a class='page_alias no_rewrite' href="#">Дополнительная навигация</a>
				<span class='managment_icon'></span>
			</span>
		</span>
		<span class='navigation_row no_remoove' id='page_number_main'>
			<span class="navigation_elem">
				<span class='page_name no_rewrite'>Основная навигация</span>
				<a class='page_alias no_rewrite' href="#">Основная навигация</a>
				<span class='managment_icon'></span>
			</span>
		</span>
	{VARIABLE_PART}
	</div>
	<div id="alias_redact_form" class="modal_form">
		<form name="change_alias" target="ajax" action="functional_departments/navigation/executors/textRedact.php" method="post">
			<input type="hidden" name="field_type" value='item_alias'>
			<h2>Новый Alias страницы (Адрес страницы)</h2>
			<div class="form_elem">
				<span class="block_with_advice">
					<span class="view_advice" title="Пояснение"></span>
					<span class="advice">Содержание данного поля будет участвовать в формировании адреса страницы. Например, указав alias страницы o_nas, страница будет располагаться по адресу http://domen_name.ru/page/o_nas
						<br>
						<span class="redaction_advice">Пожалуйста заполняйте данное поле латинскими символами и цифрами, также можете использовать: <q> - </q>, <q> _ </q> </span>
					</span>
				</span>
				<input type="text" name="text_to_change" value="" maxlength="150">
				<span class="warning_sintaxis"></span>
				<span class="warning_lenght">Осталось символов: 100</span>
			</div>
			<input type="submit" name="submit_redaction" value="Сохранить изменения">
		</form>
	</div>
	
	<div id="page_name_redact_form" class="modal_form">
		<form name="change_name" target="ajax" action="functional_departments/navigation/executors/textRedact.php" method="post">
			<input type="hidden" name="field_type" value='item_text'>
			<h2>Новое Название страницы в навигации</h2>
			<div class="form_elem">
				<span class="block_with_advice">
					<span class="view_advice" title="Пояснение"></span>
					<span class="advice">Содержание данного поля будет участвовать в формировании навигации по сайту.
						<br>
						<span class="redaction_advice">Пожалуйста заполняйте данное поле кириллическими, латинскими символами и цифрами, можете использовать любые виды скобок, также можете использовать: <q> : </q>, <q> ; </q>, <q> . </q>, <q> , </q>, <q> - </q>, <q> _ </q>, <q> + </q>, <q> * </q>, <q> ! </q>, <q> ? </q></span>
					</span>
				</span>
				<input type="text" name="text_to_change" value="" maxlength="100">
				<span class="warning_sintaxis"></span>
				<span class="warning_lenght">Осталось символов: 100</span>
			</div>
			<input type="submit" name="submit_redaction" value="Сохранить изменения">
		</form>
	</div>
	<div class="modal_form" id="insert_before_dialog">
	<p>Выберите элемент перед которым необходимо разместить выбранный заранее элемент.<br>Доступные для выбора элементы будут иметь иконку в виде дугообразной стрелочки.</p>
	</div>
	<div class="modal_form" id="insert_after_dialog">
	<p>Выберите элемент после которого необходимо разместить выбранный заранее элемент.<br>Доступные для выбора элементы будут иметь иконку в виде дугообразной стрелочки.</p>
	</div>
	<div class="modal_form" id="link_to_page_dialog">
	<p>Выберите элемент который необходимо назначить родительским для заранее выбранного элемента.<br> Доступные для выбора элементы будут иметь иконку в виде канцелярской кнопки.</p>
	</div>
	
	<span id="overlay_content"></span>
	<div id="action_menu">
		<span class="action_menu_elem" id="change_alias" title="Здесь Вы можете изменить адрес страницы, с которым она будет учавствовать в индексации поисковыми системами (Не стоит без крайней необходимости менять этот параметр у страниц существующих на Вашем сайте давно)">Изменить Alias</span>
		<span class="action_menu_elem" id="change_page_name">Изменить Имя страницы в навигации</span>
		<span class="action_menu_elem" id="link_to_page" title="Переназначая родителя, Вы перемещаете страницу в навигационную директорию, соответствующую родительской странице">Переназначить &quot;родителя&quot; страницы</span>
		<span class="action_menu_elem" id="insert_before" title="Перемещаете указанную страницу до выбранной Вами (работает только для страниц находящихся в одной навигационной директории)">Переместить страницу до . . .</span>
		<span class="action_menu_elem" id="insert_after" title="Перемещаете указанную страницу после выбранной Вами (работает только для страниц находящихся в одной навигационной директории)">Переместить страницу после . . .</span>
	</div>
</div>