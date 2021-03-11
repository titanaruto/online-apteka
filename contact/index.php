<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("КОНТАКТЫ");?><style>
.feedback input[type="text"] {
    border: 1px solid #dde4ea;
    display: block;
    color: #a9a9a9;
    width: 100%;
    margin: 0 auto;
    height: 30px;
    padding-left: 20px;
    font-weight: normal;
}
div.mf-name, div.mf-email, div.mf-captcha, div.mf-message {
	float: left;
    margin: 10px 0;
}
.mf-text{
	float: left;
	width: 150px;
}
textarea.contact-area{
	width: 60%;
}
.mf-req{
	color: #ed1c24;
}
div.mf-name input,
div.mf-email input,
div.mf-captcha input, 
div.mf-message input{
	border: 1px solid #dde4ea;
    display: block;
    color: #a9a9a9;
    width: 60%;
     height: 30px;
    padding-left: 20px;
    font-weight: normal;
}
input[type="submit"] {
    border: 0;
    background: #ed1c24;
    color: #fff;
    border-radius: 5px 5px 5px 0;
    padding: 4px 11px;
    margin-top: 10px;
    cursor: pointer;
    display: block;
}






</style>
<div class="row">
    <div class="col-xs-12">
        <br>
        Адрес: Украина, 49000, г. Днепропетровск, ул. Андрея Фабра, 4 (ул. Серова, 4)<br>
        <br>
        Телефон горячей линии: 0&nbsp;800 50 52 53<br>
        <br>
        <a href="mailto:online-apteka@med-service.com.ua">E-mail: online-apteka@med-service.com.ua</a>
        <br>
        График работы Call-центра 08:00 до 20:00<br>
        <div class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d330.6733105340958!2d35.03621398816436!3d48.46829993918595!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40dbe2e507aef097:0xb7d0bd9259788198!2z0YPQuy4g0JDQvdC00YDQtdGPINCk0LDQsdGA0LAsIDQsINCU0L3QuNC_0YDQviwg0JTQvdC10L_RgNC-0L_QtdGC0YDQvtCy0YHQutCw0Y8g0L7QsdC70LDRgdGC0YwsIDQ5MDAw!5e0!3m2!1sru!2sua!4v1612435380584!5m2!1sru!2sua" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
        </div>
        <div class="form-block">
            <div class="form-block__head">ОБРАТНАЯ СВЯЗЬ</div>
            <form action="">
                <div class="form__group field">
                    <input type="input" class="form__field" placeholder="Имя*" name="name" id='name' required />
                    <label for="name" class="form__label">Имя*</label>
                </div>
                <div class="form__group field">
                    <input type="input" class="form__field" placeholder="E-mail*" name="name" id='name' required />
                    <label for="name" class="form__label">E-mail*</label>
                </div>
                <div class="form__group field">
                    <textarea wrp-cols="30" rows="5" type="input" class="form__field" placeholder="Сообщение*" name="name" id='name' required></textarea>
                    <label for="name" class="form__label">Сообщение*</label>
                </div>
                <button class="general-form__button">Отправить</button>
            </form>
        </div>
    </div>
</div>
 <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>