
document.addEventListener("DOMContentLoaded", function(event) {
    /* FORM */
    $(".contact-feedback").submit(function(event){
        event.preventDefault();

        var form_name = $('#name').val();
        var form_email = $('#form_email').val();
        var form_message = $('#form_message').val();
        var captcha_word = $('#captcha_word').val();
        var captcha_code = $('#captcha_code').val();

        var formData = new FormData(this);
        $.ajax({
            url: "/lib/feedback/mail-form.php",
            type: "post",
            data: formData,
            success: function(data) {
                $('.contact-feedback').find('.feedback-send-message').html('<div class="feedback-send-message">'+data+'</div>');
            },
            error: function(){
                alert("Ваша форма не отправлена! Попробуйте еще раз");
            },
            cache: false,
            contentType: false,
            processData: false
        });

    });
});
