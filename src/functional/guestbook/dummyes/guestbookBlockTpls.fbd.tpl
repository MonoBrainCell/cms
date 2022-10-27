<script type="text/javascript" src="
functional/guestbook/javascript/guestbook.package.js
" name='guestbook'></script>
<link type='text/css' rel='stylesheet' href='functional/guestbook/css/style.css'>|--|<div id='guestbook'>
{NUMERAL_NAVIGATION}
<div class='records_list'>
{CONTAINER_CONTENT}
</div>
{NUMERAL_NAVIGATION}
<div id='feedback_block'>
<h3>Добавить запись в гостевую книгу</h3>
{GUESTBOOK_FORM}
</div>
</div>
<div id='dialog_window'></div>|--|<div class="numeral_navigation">
{CONTAINER_CONTENT}
</div>
|--|<a href="{PAGE_ADDRESS}">{PAGE_NUMBER}</a>
|--|<span title="Вы сейчас на этой странице">{PAGE_NUMBER}</span>
|--|<form name="feedback" method="post" action="functional/guestbook/php/saveRecord.fbd.php" target="ajax"> 
<div class='form_elem'>
<span class="block_with_advice">
<span class="view_advice" title="Пояснение"> ? </span>
<span class="advice">
Поле обязательно для заполнения
<br>
<span class="redaction_advice">Пожалуйста заполняйте данное поле цифрами, кириллическими или латинскими символами, а также: <q>-</q> , <q>.</q>, <q>(</q>, <q>)</q></span>
</span>
</span>
<p class='field_header'>Введите Ваше имя:<span class='red_star'>*</span> </p>
<input type='text' name='name' maxlength='60' class='check_syntaxis_text'>
<span class='symbs_warning'></span>
<span class='warning_length'>Осталось символов: 60</span>
</div>
<div class='form_elem'>
<span class="block_with_advice">
<span class="view_advice" title="Пояснение"> ? </span>
<span class="advice">
Поле обязательно для заполнения
<br>
<span class="redaction_advice">Пожалуйста заполняйте данное поле цифрами или латинскими символами, а также: <q>-</q>, <q>@</q> <q>.</q>, <q>_</q><br></span>
<span class="redaction_advice">Указывайте свой реальный e-mail</span>
</span>
</span>
<p class='field_header'>Введите Ваш E-mail:<span class='red_star'>*</span> </p>
<input type='text' name='email' maxlength='120' class='check_syntaxis_email check_patternStr_email'>
<span class='pattern_warning'></span>
<span class='symbs_warning'></span>
<span class='warning_length'>Осталось символов: 120</span>
</div>
<div class='form_elem'>
<span class="block_with_advice">
<span class="view_advice" title="Пояснение"> ? </span>
<span class="advice">
Поле обязательно для заполнения
<br>
<span class="redaction_advice">Пожалуйста заполняйте данное поле цифрами, кириллическими или латинскими символами, а также: <q>.</q> , <q>,</q> , <q>!</q> , <q>?</q> , <q>+</q> , <q>-</q> , <q>_</q> , <q>*</q> , <q>(</q> , <q>)</q> , <q>=</q> , <q>:</q> , <q>;</q> , <q>'</q> , <q>&[quot];</q> , <q>&[lt];</q> , <q>&[gt];</q></span>
</span>
</span>
<p class='field_header'>Введите Ваше Сообщение:<span class='red_star'>*</span> </p>
<[textfield] name='message' maxlength='200' class='check_syntaxis_message'></[textfield]>
<span class='symbs_warning'></span>
<span class='warning_length'>Осталось символов: 200</span>
</div>
<div class='form_elem'>
<input type='submit' name='send_datas' value='Отправить сообщение'>
</div>
</form>