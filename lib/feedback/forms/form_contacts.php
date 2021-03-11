<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/captcha.php');
$cpt = new CCaptcha();
$captchaPass = COption::GetOptionString('main', 'captcha_password', '');
if (strlen($captchaPass) <= 0) {
    $captchaPass = randString(8);
    COption::SetOptionString('main', 'captcha_password', $captchaPass);
}
$cpt->SetCodeCrypt($captchaPass);

?>
<div class="form-block">
    <div class="form-block__head">ОБРАТНАЯ СВЯЗЬ</div>
    <form method="post" class="contact-feedback">
        <div class="form__group field">
            <input type="input" class="form__field" placeholder="Имя*" maxlength="50" name="form_name" id='name' required />
            <label for="name" class="form__label">Имя*</label>
        </div>
        <div class="form__group field">
            <input type="input" class="form__field" placeholder="E-mail*" name="form_email" id='form_email' required />
            <label for="name" class="form__label">E-mail*</label>
        </div>
        <div class="form__group field">
            <textarea wrp-cols="30" maxlength="500" rows="5" type="input" class="form__field" placeholder="Сообщение*" name="form_message" id='form_message' required></textarea>
            <label for="name" class="form__label">Сообщение*</label>
        </div>
        <div>Для смены символов - обновите страницу</div>
        <input id="captcha_code" name="captcha_code" value="<?php echo htmlspecialchars($cpt->GetCodeCrypt()); ?>" type="hidden">
        <img src="/bitrix/tools/captcha.php?captcha_code=<?= htmlspecialchars($cpt->GetCodeCrypt()); ?>">
        <input id="captcha_word" name="captcha_word" type="text">
        <button type="submit" class="general-form__button ">Отправить</button>
        <div class="feedback-send-message">
        </div>
    </form>
</div>

