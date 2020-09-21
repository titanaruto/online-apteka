$(document).ready(function () {

    $("#vote-question").click(function () {
        $.ajax({
            url: '/bitrix/templates/eshop_adapt_MC/components/bitrix/voting.form/.default/form_vote_question.php',
            success: function (result) {
                $("#result-question").css('padding', 0);
                $("#result-question").html(result);
                let menuElement = document.getElementById('vote-question');
                let titleElement = menuElem.nextElementSibling;
                titleElement.classList.toggle('open');
                // alert(result);
            }
        });
    });

    function fileStyled() {
        $("#prescription_for_a_doctor_table .inputfile").wrap("<label class='label_inputfile'></label>")
        $("#prescription_for_a_doctor_table .inputfile").after("<span>Выберите файл</span>")
        $("#prescription_for_a_doctor_table .label_inputfile").wrap("<div class='file-upload'></div>")
        $("#prescription_for_a_doctor_table .file-upload").after('<input type="text" id="filename" class="filename" disabled>')
        $(".file-upload input[type=file]").change(function () {
            var filename = $(this).val().replace(/.*\\/, "");
            $("#filename").val(filename);
        });
        $(".prescription-block-chld input[type=text]").not(":disabled").css({
            padding: '3px'
        })
    }

    $('.feedback .closes, .massage_return .closes, .overlay, .prescription-block .closes').click(function () {
        $('.feedback, .overlay, .massage_returnn .prescription-block').css('opacity', '0');
        $('.feedback, .overlay, .massage_return, .prescription-block').css('visibility', 'hidden');
    });
    $('.request_a_call').click(function () {
        $('.overlay, .feedback').css('opacity', '1');
        $('.overlay, .feedback').css('visibility', 'visible');
    });

    function callPrescription(event) {
        if (event.target.tagName != 'DIV') {
            $('.overlay, .prescription-block').css('opacity', '1');
            $('.overlay, .prescription-block').css('visibility', 'visible');
            $(".prescription-block-chld").html("")
            $(".prescription-block-chld").prepend("<img src='/bitrix/components/sgus/wait.gif'>")
            $(".prescription-block-chld img").css({
                display: 'block',
                margin: '0 auto'
            })
            $.ajax({
                type: "POST",
                url: '/bitrix/components/sgus/prescription_ajax_form.php',
                cache: false,
                data: "token=111",
                success: function (data) {
                    $(".prescription-info-prscr span.close_prscr").parent().fadeOut('slow')
                    $(".prescription-block-chld img").hide()
                    $(".prescription-block-chld").append(data)
                    fileStyled()
                    $("div[data-property-id-row=10], div[data-property-id-row=11], div[data-property-id-row=12], div[data-property-id-row=13]").hide()
                    $(".mc-button.letter").on("click", function () {
                        setTimeout(fileStyled, 1000)
                    })
                }
            });
        }
    }

    window.onload = function (event) {
        if (location.href.indexOf("#prescription") != -1)
            callPrescription(event)
    }

    $("a.prescription, .close_prscr").on('click', function (event) {
        if ($(this).attr("class") == "close_prscr") {
            $(".prescription-info-prscr").remove()
            return false
        } else {
            callPrescription(event)
        }
    })

    $(".prescription-info-prscr span[id=close]").on("click", function () {
        $(this).parent().hide()
        return false
    })
})