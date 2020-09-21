$(document).ready(function(){
    $('a[href*=#]').bind("click", function(e){
        let anchor = $(this);
        $('a').css({
            textDecoration: 'none'
        })
        anchor.css({
            textDecoration: 'underline'
        })
        $('html, body').stop().animate({
            scrollTop: $(anchor.attr('href')).offset().top - 86
        }, 800);
        e.preventDefault();
    });
    return false;
});