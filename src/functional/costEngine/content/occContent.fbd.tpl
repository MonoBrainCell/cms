<div id='cost_engine_block'>
<form target='ajax' method='post' name='cost_engine' action='functional/costEngine/php/orderItem.fbd.php'>
<div class='form_elem'>
<p class='field_header'>Наименование:</p>
<select name='item_type'>
<option value='item1'>Позиция 1</option>
<option value='item2'>Позиция 2</option>
<option value='item3'>Позиция 3</option>
<option value='item4'>Позиция 4</option>
<option value='item5'>Позиция 5</option>
</select>
</div>
<div class='form_elem'>
<span class="block_with_advice">
<span class="view_advice" title="Пояснение"> ? </span>
<span class="advice">
<span class="redaction_advice">Пожалуйста заполняйте данное поле только цифрами, без любых других символов</span>
</span>
</span>
<p class='field_header'>Количество:</p>
<input class='check_syntaxis_numeral' type='text' name='quantity' maxlength='8'>
<span class='symbs_warning'></span>
<span class='warning_length'></span>
</div>

<div class='form_elem'>
<input type='checkbox' name='option1' value='adv_option1' id='adv_option1'><label for='adv_option1'>Доп. опция 1</label>
</div>
<div class='form_elem'>
<input type='checkbox' name='option2' value='adv_option2' id='adv_option2'><label for='adv_option2'>Доп. опция 2</label>
</div>
<div class='form_elem'>
<input type='checkbox' name='option3' value='adv_option3' id='adv_option3'><label for='adv_option3'>Доп. опция 3</label>
</div>
<p id='total_value'></p>
<div id='hidden_form_part'>
<div class='form_elem'>
<span class="block_with_advice">
<span class="view_advice" title="Пояснение"> ? </span>
<span class="advice">
Поле обязательно для заполнения
<br>
<span class="redaction_advice">Пожалуйста заполняйте данное поле цифрами, кириллическими или латинскими символами, а также: <q>-</q> , <q>.</q>, <q>(</q>, <q>)</q></span>
</span>
</span>
<p class='field_header'>Введите Ваше имя: <span class='red_star'>*</span> </p>
<input type='text' name='name' maxlength='60' class='check_syntaxis_text'>
<span class='symbs_warning'></span>
<span class='warning_length'>Осталось символов: 60</span>
</div>
<div class='form_elem'>
<span class="block_with_advice">
<span class="view_advice" title="Пояснение"> ? </span>
<span class="advice">
<span class="redaction_advice">Пожалуйста заполняйте данное поле цифрами или латинскими символами, а также: <q>-</q>, <q>@</q> <q>.</q>, <q>_</q><br></span>
<span class="redaction_advice">Указывайте свой реальный e-mail</span>
</span>
</span>
<p class='field_header'>Введите Ваш E-mail:</p>
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
<span class="redaction_advice">Пожалуйста заполняйте данное поле цифрами, а также: <q>-</q> , <q>+</q>, <q>(</q>, <q>)</q></span>
</span>
</span>
<p class='field_header'>Введите Ваш Телефон: <span class='red_star'>*</span> </p>
<input type='text' name='phone' maxlength='30' class='check_syntaxis_phone'>
<span class='symbs_warning'></span>
<span class='warning_length'>Осталось символов: 30</span>
</div>
<div class='form_elem'>
<span class="block_with_advice">
<span class="view_advice" title="Пояснение"> ? </span>
<span class="advice">
<span class="redaction_advice">Пожалуйста заполняйте данное поле цифрами, кириллическими или латинскими символами, а также: <q>.</q> , <q>,</q> , <q>!</q> , <q>?</q> , <q>+</q> , <q>-</q> , <q>_</q> , <q>*</q> , <q>(</q> , <q>)</q> , <q>=</q> , <q>:</q> , <q>;</q> , <q>'</q> , <q>&quot;</q> , <q>&prime;</q> , <q>&lt;</q> , <q>&gt;</q></span>
</span>
</span>
<p class='field_header'>Введите Ваше Сообщение:</p>
<[textfield] name='message' maxlength='200' class='check_syntaxis_message'></[textfield]>
<span class='symbs_warning'></span>
<span class='warning_length'>Осталось символов: 200</span>
</div>
<div class='form_elem'>
<input type='submit' name='send_datas' value='Сделать заказ'>
</div>

</div>

</form>
</div>