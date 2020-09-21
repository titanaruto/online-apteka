<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$useragent = $_SERVER['HTTP_USER_AGENT'];
if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
    $is_mobile = 'mobile';
} else {
    $is_mobile = 'desktop';
}
if ( $is_mobile == 'desktop') {
    header("Location: /");
    die();
}
?>
<style>
    @font-face {
        font-family: "Neuron-DemiBold";
        src: url("/bitrix/templates/eshop_adapt_MC/fonts/Neuron-DemiBold/Neuron-DemiBold.eot");
        src: url("/bitrix/templates/eshop_adapt_MC/fonts/Neuron-DemiBold/Neuron-DemiBold.otf");
        src: url("/bitrix/templates/eshop_adapt_MC/fonts/Neuron-DemiBold/Neuron-DemiBold.svg");
        src: url("/bitrix/templates/eshop_adapt_MC/fonts/Neuron-DemiBold/Neuron-DemiBold.ttf");
        src: url("/bitrix/templates/eshop_adapt_MC/fonts/Neuron-DemiBold/Neuron-DemiBold.woff");
    }

    @font-face {
        font-family: "PragmaticaC-Bold";
        src: url("/bitrix/templates/eshop_adapt_MC/fonts/PragmaticaC-Bold/PragmaticaC-Bold.eot");
        src: url("/bitrix/templates/eshop_adapt_MC/fonts/PragmaticaC-Bold/PragmaticaC-Bold.otf");
        src: url("/bitrix/templates/eshop_adapt_MC/fonts/PragmaticaC-Bold/PragmaticaC-Bold.svg");
        src: url("/bitrix/templates/eshop_adapt_MC/fonts/PragmaticaC-Bold/PragmaticaC-Bold.ttf");
        src: url("/bitrix/templates/eshop_adapt_MC/fonts/PragmaticaC-Bold/PragmaticaC-Bold.woff"); }

    .bx-header {
        display: none;
    }

    .workarea {
        background: #fff;
    }

    input[name=form_text_23],
    input[name=form_text_24] {
        display: none;
    }

    body {
        background: none;
    }

    .container.bx-content-seection {
        width: 500px;
        padding: 0;
        position: relative;
        border: none;
    }

    .header {
        background-color: #fff;
        -webkit-box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.15);
        height: 55px;
        position: relative;
        z-index: 3;
        width: 500px;
    }

    .fa.fa-phone {
        color: #ff0000;
    }

    .header img {
        margin: 0 auto;
        display: block;
    }

    .tel-top-inner {
        position: absolute;
        right: 10px;
        top: 10px;
        color: red!important;
        white-space: nowrap!important;
        font-family: Neuron-DemiBold;
    }
    .tel-top-inner .fa-phone {
        font-size: 17px;
        color: #ff0000;
        margin-right: 5px; }

    .tel-top {
        font: 600 17px / 1.5 "Neuron-DemiBold", sans-serif;
        color: red!important;
        white-space: nowrap!important;
    }

    .tel-top .fa-phone {
        position: relative;
        bottom: 2px;
    }

    .footer {
        margin-top: 30px; }

    .footer-inner {
        border-top: 3px solid #c8c8c8;
        padding-bottom: 70px;
        padding-top: 30px;
    }
    .footer-inner .btn-top {
        margin-right: 0; }

    .td-footer-hot-line {
        width: 50%;
        padding: 0;
    }

    .td-footer-hot-line span {
        font-family: "Neuron-DemiBold";
        color: #ff0000;
        font-size: 34px;
        text-align: left;
        margin: 0;
    }

    .td-footer-timework span {
        font: 700 13px / 16px "Neuron-BoldItalic", sans-serif;
        color: #6e6e6e;
        text-align: left;
        padding-top: 10px;
        padding-left: 10px;
        margin: 0;
    }

    .td-footer-info-time span {
        font: 400 13px / 16px "Neuron-Italic", sans-serif;
        color: #6e6e6e;
        text-align: left;
        padding-left: 7px;
    }

    .mfeedback {
        position: relative;
        width: 500px;
        padding: 0 20px;
        border: none;
    }

    h1 {
        color: #014b87;
        text-align: center;
        margin-top: 130px;
        font-family: PragmaticaC-Bold;
        text-transform: uppercase;
        font-weight: 100;
        line-height: 1.5em;
        font-size: 30px;
    }

    hr {
        width: 100px;
        border-top: 3px solid #014b87;
        text-align: center;
        margin: 0 auto;
    }

    p {
        margin-top: 20px;
        text-align: center;
        color: #6e6e6e;
        line-height: 1.3em;
    }

    span {
        text-align: center;
        font-weight: bold;
        font-style: italic;
        display: block;
        margin-bottom: 30px;
        color: #6e6e6e;
    }

    p, span {
        font-size: 12px;
    }

    select {
        width: 100%;
    }

    input,
    select {
        /*height: 35px;*/
        margin-bottom: 8px;
        border-radius: 2px;
    }

    #bx_eshop_wrap .mfeedback input[type=text],
    #bx_eshop_wrap .mfeedback select,
    #bx_eshop_wrap .mfeedback textarea {
        border: 1px solid #999;
        border-radius: 3px;
    }

    form[name=SIMPLE_FORM_2] table td {
        color: #6e6e6e;
        font-weight: 100!important;
    }

    #bx_eshop_wrap .mfeedback input[type=text],
    #bx_eshop_wrap .mfeedback textarea,
    #bx_eshop_wrap .mfeedback select {
        padding: 8px 10px;
    }

    .mc-button.letter i {
        /*background: none;*/
        /*display: none;*/
    }

    .mc-button.letter {
        left: 50%;
        margin-left: -50px;
    }

    .mc-button.letter::before {
        /*display: none;*/
    }

    .tel-top-inner-1 {
        font-family: Neuron-DemiBold;
        float: left;
        width: 50%;
        margin-right: 7px;
        height: 150px;
    }

    .fa.fa-phone {
        color: red;
        float: left;
        display: block;
        margin-top: 5px;
    }

    .tel-top {
        display: inline;
        margin-left: 7px;
        color: red!important;
        white-space: nowrap!important;
    }

    .td-fa-phone-foot i {
        font-size: 28px;
        margin: 0!important;
        padding: 0!important;
    }

    .td-fa-phone-foot {
        text-align: left;
        width: 1%;
    }

    #bx_eshop_wrap .mfeedback input::-webkit-input-placeholder {
        color: #111;
        font-size: 14px;
    }

    #bx_eshop_wrap .mfeedback input:-moz-placeholder { /* Firefox 18- */
        color: #111;
        font-size: 14px;
    }

    #bx_eshop_wrap .mfeedback input::-moz-placeholder {  /* Firefox 19+ */
        color: #111;
        font-size: 14px;
    }

    #bx_eshop_wrap .mfeedback input:-ms-input-placeholder {
        color: #111;
        font-size: 14px;
    }

    #bx_eshop_wrap .mfeedback textarea::-webkit-input-placeholder {
        color: #111;
        font-size: 14px;
    }

    #bx_eshop_wrap .mfeedback textarea:-moz-placeholder { /* Firefox 18- */
        color: #111;
        font-size: 14px;
    }

    #bx_eshop_wrap .mfeedback textarea::-moz-placeholder {  /* Firefox 19+ */
        color: #111;
        font-size: 14px;
    }

    #bx_eshop_wrap .mfeedback textarea:-ms-input-placeholder {
        color: #111;
        font-size: 14px;
    }

    #bx_eshop_wrap .mfeedback select {
        color: #777;
        font-weight: 100;
        font-size: 14px;
        background: none;
        margin-bottom: 12px;
    }

    #bx_eshop_wrap .mfeedback input,
    #bx_eshop_wrap .mfeedback textarea {
        margin-bottom: 12px;
        background: none;
    }

    #bx_eshop_wrap .mfeedback table tr br {
        display: none;
    }

    .mfeedback-file-upload {
        width: 100%;
        height: 18px;
        display: inline-block;
    }

    .label_inputfile_text {
        padding-left: 25px;
        font-size: 14px;
        font-style: normal;
        font-weight: 100;
        background: url("/bitrix/templates/eshop_adapt_MC/img/skrepka.png") no-repeat;
    }

    #bx_eshop_wrap .mfeedback .inputfile {
        display: none;
    }

    .mc-button.letter {
        background: none;
        margin-top: -10px;
    }

    #prescription_for_a_doctor_table input[type="submit"] {
        color: #014b87;
        font-size: 26px;
        font-weight: normal;
        text-transform: uppercase;
        width: 100%;
    }

    .mc-button.letter i {
        display: none;
    }

    .mc-button.letter input {
        color: #6e6e6e;
    }

    .mc-button {
        font-size: 14px;
        text-decoration: none;
        position: relative;
        background: #fff;
        border-radius: 5px;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        -o-box-shadow: none;
        box-shadow: none;
        padding: 14px;
        border: 1px solid #014b87!important;
left: 30%!important;
    }

    label.mc-button.letter {
        height: 44px!important;
        width: 200px;
        display: block;
        margin-left: -100px;
    }

    label.mc-button.letter,
    input[type=submit] {
        cursor: text;
    }

    .mc-button.letter:before {
        display: none!important;
    }

    .mc-button:active{
        top: 0;
        background: none!important;
        -webkit-box-shadow: none;
        -moz-box-shadow: 	none;
        -o-box-shadow: 		none;
        box-shadow: none;
    }

    .td-tel-top-foot span {
        text-align: left;
        font-size: 22px;
        color: #ff0000;
        font-style: normal;
    }

    .td-tel-top-foot {
        padding-left: 7px;
    }

    @media screen and (max-width: 480px) {

        .tel-top-inner-1 {
            margin-right: 0;
        }

        .td-tel-top-foot span,
        .td-fa-phone-foot i {
            font-size: 14px!important;
            color: #ff0000!important;
        }

        .container.bx-content-seection,
        .mfeedback,
        .header {
            width: 100%;
        }

        .header img {
            margin: 0 auto;
            display: block;
            height: 120px;
        }

        .td-footer-hot-line span {
            line-height: 1em;
            font-size: 7vw;
            font-style: italic;
            font-weight: normal;
            margin-bottom: 10px;
        }

        .tel-top-top,
        .fa-phone-top {
            font-size: 12px!important;
            color: #ff0000!important;
            margin-left: 0;
            white-space: nowrap!important;
        }

        .header img.logo-main {
            margin-top: -20px;
        }

        div.tel-top-inner {
            margin: 7px -3px 0 0;
            padding-right: 15px;
            color: #ff0000!important;
        }

        h1 {
            font-size: 20px;
            margin-top: 80px;
        }

        label.mc-button.letter {
            height: 50px!important;
        }

        .tel-top-inner-right {
            padding-left: 10px;
            width: 50%;
            float: left;
        }

        .td-footer-timework span {
            text-align: left;
            font-size: 10px;
            font-style: italic;
            margin: 0;
            padding-top: 0;
            /*padding-left: 0;*/
        }

        .td-footer-info-time span {
            text-align: left;
            font-size: 10px;
            font-style: italic;
        }
    }

    p.success_answer {
        font-style: italic;
        font-weight: bold;
        text-align: center;
        font-size: 14px;
        padding-bottom: 30px;
    }

    #filename {
        border: none;
    }

    div[id^="wait_comp"] {
        margin: 0 auto;
        left: 0!important;
        right: 0;
        bottom: 0;
        width: 100px;
        height: 35px;
    }

    .tel-top-inner-2 {
        position: relative!important;
        height: 10px!important;
    }

    .footer table td {
        vertical-align: top;
    }

</style>
<header class="header">
    <a href="/"><img src="<?=SITE_TEMPLATE_PATH?>/img/1170/logo.png" class="logo-main" alt="logo"></a>
    <div class="tel-top-inner">
        <i class="fa fa-phone fa-phone-top" aria-hidden="true"></i>
        <span class="tel-top tel-top-top">
            <a href="tel:0 800 50 52 53">0 800 50 52 53</a></span>
    </div>
</header>
<div class="mfeedback">
<div class="mfeedback-head">
    <h1>Связь с директором<br /> сети аптек «Мед-сервис»</h1>
    <hr />
    <p>Мы несем ответственность перед каждым, кто приходит в наши аптеки. Если у вас есть вопросы, предложения или пожелания, мы всегда готовы вам помочь.</p>
    <span>Ваш отзыв будет отправлен Генеральному директору.</span>
</div>
    <?php
    $APPLICATION->IncludeComponent(
        "sgus:form",
        "callback-form",
        array(
            "AJAX_MODE" => "Y",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "CACHE_TIME" => "3600",
            "CACHE_TIME" => "0",
            "CACHE_TYPE" => "A",
            "CHAIN_ITEM_LINK" => "",
            "CHAIN_ITEM_TEXT" => "",
            "EDIT_ADDITIONAL" => "N",
            "EDIT_STATUS" => "Y",
            "IGNORE_CUSTOM_TEMPLATE" => "N",
            "NOT_SHOW_FILTER" => array(
                0 => "",
                1 => "",
            ),
            "NOT_SHOW_TABLE" => array(
                0 => "",
                1 => "",
            ),
            "RESULT_ID" => 2,
            "SEF_MODE" => "N",
            "SHOW_ADDITIONAL" => "N",
            "SHOW_ANSWER_VALUE" => "N",
            "SHOW_EDIT_PAGE" => "N",
            "SHOW_LIST_PAGE" => "N",
            "SHOW_STATUS" => "Y",
            "SHOW_VIEW_PAGE" => "N",
            "START_PAGE" => "new",
            "SUCCESS_URL" => "",
            "USE_EXTENDED_ERRORS" => "N",
            "WEB_FORM_ID" => "2",
            "COMPONENT_TEMPLATE" => "callback-form",
            "VARIABLE_ALIASES" => array(
                "action" => "action",
            )
        ),
        false
    );

    if (CModule::IncludeModule('iblock')) {
        $arSelect = array(
            'IBLOCK_SECTION_ID',
            'PROPERTY_CITY',
            'PROPERTY_ADRES',
        );
        $arFilter = Array("IBLOCK_ID"=>IntVal(12), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
        $res = CIBlockElement::GetList(Array(), $arFilter, false, "", $arSelect);
        while($ob = $res->GetNextElement()){
            $arFields = $ob->GetFields();
            $arCity [htmlspecialchars($arFields['PROPERTY_CITY_VALUE'])]= $arFields['IBLOCK_SECTION_ID'];
            $arAddress [htmlspecialchars($arFields['PROPERTY_ADRES_VALUE'])]= $arFields['IBLOCK_SECTION_ID'];
        }
    }
    ksort($arCity);
    ksort($arAddress);
    ?>
    <input type="hidden" id="cities" value='<?=json_encode($arCity, JSON_UNESCAPED_UNICODE)?>'>
    <input type="hidden" id="pharmacies" value='<?=json_encode($arAddress, JSON_UNESCAPED_UNICODE)?>'>
    <script>
        $(document).ready(function () {

            $("form[name=SIMPLE_FORM_2] input[type=text]").eq(0).attr("placeholder", "Имя*")
            $("form[name=SIMPLE_FORM_2] input[type=text]").eq(1).attr("placeholder", "E-Mail*")
            $("form[name=SIMPLE_FORM_2] input[type=text]").eq(2).attr("placeholder", "Телефон*")
            $("form[name=SIMPLE_FORM_2] textarea").attr("placeholder", "Сообщение*")

            $("input[name=form_text_28]").parent().hide()
            $("input[name=form_text_29]").parent().hide()

            $("input[name=form_text_8]").parent().parent().after("<tr><td></td></tr><tr><td><select></select></td></tr><tr><td></td></tr><tr><td><select></select></td></tr>")

            var select1 = $("form select").eq(0)
            var select2 = $("form select").eq(1)

            var cities = jQuery.parseJSON($("#cities").val())
            var pharmacies = jQuery.parseJSON($("#pharmacies").val())

            select1.append("<option value='0'>Город*</option>")
            select2.append("<option value='0'>Аптека, которая вас интересует*</option>")

            $.each(cities, function(i,dt){
                select1.append("<option value=" + dt + ">" + i + "</option>")
            });

            select1.on("change", function () {
                var sectionId = $(this).val()
                select2.html("")
                select2.append("<option value=\"0\">Аптека, которая вас интересует*</option>")

                $.each(pharmacies, function(i,dt){
                    if (dt == sectionId) {
                        select2.append("<option value=" + dt + ">" + i + "</option>")
                    }
                });

                $("input[name=form_text_28]").val($(this).find("option:selected").text())
                $("input[name=form_text_29]").val(select2.find("option:selected").text())
            })

            select2.on("change", function () {
                $("input[name=form_text_29]").val($(this).find("option:selected").text())
            })

            var textarea = $("#prescription_for_a_doctor_table textarea:visible")
            var input = $("#prescription_for_a_doctor_table input[type=text]:visible")
            var select = $("#prescription_for_a_doctor_table select:visible")

            var errors

            function getErrors() {
                errors = 6;
                input.each(function () {
                    if ($(this).val().length > 0)
                        errors --
                })

                select.each(function () {
                    if ($(this).val() != 0)
                        errors --
                })

                if (textarea.val().length > 0)
                    errors --

                if (errors == 0) {
                    $(".mc-button").css({
                        background: '#014b87'
                    })

                    $("#prescription_for_a_doctor_table input[type='submit']").css({
                        color: '#fff'
                    })

                    $("label.mc-button.letter, input[type=submit]").css({
                        cursor: "pointer"
                    })
                } else {
                    $(".mc-button").css({
                        background: '#fff'
                    })

                    $("#prescription_for_a_doctor_table input[type='submit']").css({
                        color: '#014b87'
                    })

                    $("label.mc-button.letter, input[type=submit]").css({
                        cursor: "text"
                    })
                }

                return errors
            }

            textarea.on("keyup", function () {
                getErrors()
            })

            input.on("keyup", function () {
                getErrors()
            })

            select.on("change", function () {
                getErrors()
            })

            $("form[name=SIMPLE_FORM_2]").submit(function () {
                input.each(function () {
                    if ($(this).val().length > 0) {
                        $(this).css({
                            border: '1px solid #999'
                        })
                    } else {
                        $(this).css({
                            border: '1px solid red'
                        })
                    }
                })

                select.each(function () {
                    if ($(this).val() != 0) {
                        $(this).css({
                            border: '1px solid #999'
                        })
                    } else {
                        $(this).css({
                            border: '1px solid red'
                        })
                    }
                })

                if (textarea.val().length > 0) {
                    textarea.css({
                        border: '1px solid #999'
                    })
                } else {
                    textarea.css({
                        border: '1px solid red'
                    })
                }

                if (errors == 0) {
                    $(".mfeedback h1, .mfeedback p, .mfeedback-head span, .mfeedback-head hr, .mfeedback form").hide()
                    $(".header").css({
                        marginBottom: '120px'
                    })
                    return true
                } else {
                    $("div[id^=wait_comp]").hide()
                    return false
                }
            })

            $("input[name=form_text_6]").prev().prev().hide()
            $("input[name=form_email_7]").prev().prev().hide()
            $("input[name=form_text_8]").prev().prev().hide()
            $("textarea").prev().prev().hide()

            function fileStyled() {
                $(".inputfile").wrap("<label class='label_inputfile'></label>")
                $(".inputfile").after("<span class='label_inputfile_text'>Загрузить документ или фото</span>")
                $(".label_inputfile").wrap("<div class='mfeedback-file-upload'></div>")

                $(".mfeedback-file-upload input[type=file]").change(function(){
                    $(".mfeedback-file-upload").after('<input type="text" id="filename" class="filename" disabled>')
                    var filename = $(this).val().replace(/.*\\/, "");
                    $("#filename").val(filename);
                    $("#filename").css({
                        border: 'none'
                    })
                });
                $(".prescription-block-chld input[type=text]").not(":disabled").css({
                    padding: '3px'
                })
            }
            fileStyled()

            $("input[name=form_text_8]").mask("+38 (999) 999-99-99")
        })
    </script>
    <footer class="footer">
        <div class="footer-inner clearfix">

            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="td-footer-hot-line"><span>Горячая линия</span></td>
                    <td class="td-footer-timework"><span>с 8:00 до 20:00 (без выходных)</span></td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td class="td-fa-phone-foot"><i class="fa fa-phone"></i></td>
                                <td class="td-tel-top-foot"><span><a href="tel:0 800 50 52 53">0 800 50 52 53</a></span></td>
                            </tr>
                        </table>
                    </td>
                    <td class="td-footer-info-time"><span>Бесплатно со стационарных и мобильных телефонов в Украине</span></td>
                </tr>
            </table>

<!--            <div class="tel-top-inner-1">-->
<!--                <span class="footer-hot-line">Горячая линия</span>-->
<!--                <div class="tel-top-inner-2">-->
<!--                    <i class="fa fa-phone fa-phone-foot"></i>-->
<!--                    <span class="tel-top tel-top-foot">0 800 50 52 53</span>-->
<!--                </div>-->
<!---->
<!--            </div>-->
<!--            <div class="tel-top-inner-right">-->
<!--            <span class="footer-timework">с 8:00 до 21:00 (без выходных)</span>-->
<!--            <span class="footer-info-time">Бесплатно со стационарных и мобильных телефонов в Украине</span>-->
<!--            </div>-->
        </div>
    </footer>
</div>
