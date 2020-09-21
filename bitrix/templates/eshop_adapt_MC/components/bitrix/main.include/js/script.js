$(document).ready(function(){
    var slides = [
        {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/medicart.webp'},
        {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/zakaz.webp'},
    ]
    var jR3DCarousel;
    var carouselProps =  {
        width: 247, 				/* largest allowed width */
        height: 391, 				/* largest allowed height */
        slideLayout : 'fill',     /* "contain" (fit according to aspect ratio), "fill" (stretches object to fill) and "cover" (overflows box but maintains ratio) */
        animation: 'slide3D', 		/* slide | scroll | fade | zoomInSlide | zoomInScroll | slide3D*/
        animationCurve: 'ease',
        animationDuration: 1000,
        animationInterval: 5000,
        //slideClass: 'jR3DCarouselCustomSlide',
        autoplay: true,
        onSlideShow: show,		/* callback when Slide show event occurs */
        //navigation: 'circles',	/* circles | squares */
        slides: slides 			/* array of images source or gets slides by 'slide' class */
    }
    function setUp(){
        jR3DCarousel = $('.jR3DCarouselGallery').jR3DCarousel(carouselProps);
        $('.settings').html('<pre>$(".jR3DCarouselGallery").jR3DCarousel('+JSON.stringify(carouselProps, null, 4)+')</pre>');
    }
    function show(slide){
        //console.log("Slide shown: ", slide.find('img').attr('src'))
    }
    $('.carousel-props input').change(function(){
        if(isNaN(this.value))
            carouselProps[this.name] = this.value || null;
        else
            carouselProps[this.name] = Number(this.value) || null;
        for(var i = 0; i < 999; i++)
            clearInterval(i);
        $('.jR3DCarouselGallery').empty();
        setUp();
        jR3DCarousel.showNextSlide();
    })
    $('[name=slides]').change(function(){
        carouselProps[this.name] = getSlides(this.value);
        for (var i = 0; i < 999; i++)
            clearInterval(i);
        $('.jR3DCarouselGallery').empty();
        setUp();
        jR3DCarousel.showNextSlide();
    });

    function getSlides(no){
        slides = [];
        for ( var i = 0; i < no; i++) {
            slides.push({src: 'https://unsplash.it/'+Math.floor(1366-Math.random()*200)+'/'+Math.floor(768+Math.random()*200)})
        }
        return slides;
    }

    //carouselProps.slides = getSlides(7);
    setUp()

})

$(document).ready(function(){
    var slides = [
        {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/june_2020.png'},
        {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/june_2020.png'},
    ]
    var links = [
        '/catalog/?q=Лактацид&s=',
        '/catalog/?q=Лактацид&s=',
        '/catalog/?q=Лактацид&s=',
        '/catalog/?q=Лактацид&s='
    ]
    var jR3DCarousel_1;
    var carouselProps =  {
        width: 247, 				/* largest allowed width */
        height: 391, 				/* largest allowed height */
        slideLayout : 'fill',     /* "contain" (fit according to aspect ratio), "fill" (stretches object to fill) and "cover" (overflows box but maintains ratio) */
        animation: 'slide3D', 		/* slide | scroll | fade | zoomInSlide | zoomInScroll */
        animationCurve: 'ease',
        animationDuration: 1000,
        animationInterval: 7000,
        //slideClass: 'jR3DCarouselCustomSlide',
        autoplay: true,
        onSlideShow: show,		/* callback when Slide show event occurs */
        //navigation: 'circles',	/* circles | squares */
        slides: slides, 			/* array of images source or gets slides by 'slide' class */
        links: links
    }
    function setUp(){
        jR3DCarousel = $('.jR3DCarouselGallery_1').jR3DCarousel(carouselProps);
        $('.settings').html('<pre>$(".jR3DCarouselGallery_1").jR3DCarousel('+JSON.stringify(carouselProps, null, 4)+')</pre>');
        for (var ii = 0; ii < links.length; ii ++) {
            $(".jR3DCarouselGallery_1 a").eq(ii).attr('href', links[ii])
        }
    }
    var j = 1
    function show(slide){
        if (j++%2 == 1) {
            $(".jR3DCarouselGallery_1 a").eq(0).hide()
            $(".jR3DCarouselGallery_1 a").eq(1).hide()
            $(".jR3DCarouselGallery_1 a").eq(2).show()
            $(".jR3DCarouselGallery_1 a").eq(3).show()
        } else {
            $(".jR3DCarouselGallery_1 a").eq(0).show()
            $(".jR3DCarouselGallery_1 a").eq(1).show()
            $(".jR3DCarouselGallery_1 a").eq(2).hide()
            $(".jR3DCarouselGallery_1 a").eq(3).hide()
        }

        // var elems = slide.find('img').attr('src').split('/')
        // console.log(elems[elems.length-1].split('.')[0])
    }
    $('.carousel-props input').change(function(){
        if(isNaN(this.value))
            carouselProps[this.name] = this.value || null;
        else
            carouselProps[this.name] = Number(this.value) || null;
        for(var i = 0; i < 999; i++)
            clearInterval(i);
        $('.jR3DCarouselGallery_1').empty();
        setUp();
        jR3DCarousel_1.showNextSlide();
    })
    $('[name=slides]').change(function(){
        carouselProps[this.name] = getSlides(this.value);
        for (var i = 0; i < 999; i++)
            clearInterval(i);
        $('.jR3DCarouselGallery_1').empty();
        setUp();
        jR3DCarousel_1.showNextSlide();
    });

    function getSlides(no){
        slides = [];
        for ( var i = 0; i < no; i++) {
            slides.push({src: 'https://unsplash.it/'+Math.floor(1366-Math.random()*200)+'/'+Math.floor(768+Math.random()*200)})
        }
        return slides;
    }
    //carouselProps.slides = getSlides(7);
    setUp()
})

$(document).ready(function(){
    var slides = [
        {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/august_2020_1.jpg'},
        {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/august_2020_2.jpg'},
    ]
    var links = [
        '/catalog/repellenty-dlya-detey/baybaybayt-zashch-sr-vo-sprey-d-detey-ot-komarov-nasekomykh-100ml/',
        '/catalog/repellenty-dlya-detey/baybaybayt-zashch-sr-vo-sprey-d-detey-ot-komarov-nasekomykh-100ml/',
        '/catalog/kosmeticheskie-sredstva-dlya-lecheniya-pedikulyeza/shampun-parazidoz-kosmeticheskiy-protivopedikuleznyy-100-ml/',
        '/catalog/kosmeticheskie-sredstva-dlya-lecheniya-pedikulyeza/shampun-parazidoz-kosmeticheskiy-protivopedikuleznyy-100-ml/'
    ]
    var jR3DCarousel_2;
    var carouselProps =  {
        width: 247, 				/* largest allowed width */
        height: 391, 				/* largest allowed height */
        slideLayout : 'fill',     /* "contain" (fit according to aspect ratio), "fill" (stretches object to fill) and "cover" (overflows box but maintains ratio) */
        animation: 'slide3D', 		/* slide | scroll | fade | zoomInSlide | zoomInScroll */
        animationCurve: 'ease',
        animationDuration: 1000,
        animationInterval: 7000,
        //slideClass: 'jR3DCarouselCustomSlide',
        autoplay: true,
        onSlideShow: show,		/* callback when Slide show event occurs */
        //navigation: 'circles',	/* circles | squares */
        slides: slides, 			/* array of images source or gets slides by 'slide' class */
        links: links
    }
    function setUp(){
        jR3DCarousel = $('.jR3DCarouselGallery_2').jR3DCarousel(carouselProps);
        $('.settings').html('<pre>$(".jR3DCarouselGallery_2").jR3DCarousel('+JSON.stringify(carouselProps, null, 4)+')</pre>');
        for (var ii = 0; ii < links.length; ii ++) {
            $(".jR3DCarouselGallery_2 a").eq(ii).attr('href', links[ii])
        }
    }
    var j = 1
    function show(slide){
        if (j++%2 == 1) {
            $(".jR3DCarouselGallery_2 a").eq(0).hide()
            $(".jR3DCarouselGallery_2 a").eq(1).hide()
            $(".jR3DCarouselGallery_2 a").eq(2).show()
            $(".jR3DCarouselGallery_2 a").eq(3).show()
        } else {
            $(".jR3DCarouselGallery_2 a").eq(0).show()
            $(".jR3DCarouselGallery_2 a").eq(1).show()
            $(".jR3DCarouselGallery_2 a").eq(2).hide()
            $(".jR3DCarouselGallery_2 a").eq(3).hide()
        }
        //console.log("Slide shown: ", slide.find('img').attr('src'))
    }
    $('.carousel-props input').change(function(){
        if(isNaN(this.value))
            carouselProps[this.name] = this.value || null;
        else
            carouselProps[this.name] = Number(this.value) || null;
        for(var i = 0; i < 999; i++)
            clearInterval(i);
        $('.jR3DCarouselGallery_2').empty();
        setUp();
        jR3DCarousel_2.showNextSlide();
    })
    $('[name=slides]').change(function(){
        carouselProps[this.name] = getSlides(this.value);
        for (var i = 0; i < 999; i++)
            clearInterval(i);
        $('.jR3DCarouselGallery_2').empty();
        setUp();
        jR3DCarousel_2.showNextSlide();
    });

    function getSlides(no){
        slides = [];
        for ( var i = 0; i < no; i++) {
            slides.push({src: 'https://unsplash.it/'+Math.floor(1366-Math.random()*200)+'/'+Math.floor(768+Math.random()*200)})
        }
        return slides;
    }
    //carouselProps.slides = getSlides(7);
    setUp()
})

$(document).ready(function(){
    var slides = [
        {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/august_2020_3.jpeg'},
        {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/august_2020_3.jpeg'},
    ]
    var links = [
        '/catalog/sredstva-primenyaemye-pri-zabolevaniyakh-polosti-nosa/tizin-ksilo-sprey-nazal-0-05-fl-10ml/',
        '/catalog/sredstva-primenyaemye-pri-zabolevaniyakh-polosti-nosa/tizin-ksilo-sprey-nazal-0-05-fl-10ml/',
        '/catalog/sredstva-primenyaemye-pri-zabolevaniyakh-polosti-nosa/tizin-ksilo-sprey-nazal-0-05-fl-10ml/',
        '/catalog/sredstva-primenyaemye-pri-zabolevaniyakh-polosti-nosa/tizin-ksilo-sprey-nazal-0-05-fl-10ml/'
    ]
    var jR3DCarousel_3;
    var carouselProps =  {
        width: 247, 				/* largest allowed width */
        height: 391, 				/* largest allowed height */
        slideLayout : 'fill',     /* "contain" (fit according to aspect ratio), "fill" (stretches object to fill) and "cover" (overflows box but maintains ratio) */
        animation: 'slide3D', 		/* slide | scroll | fade | zoomInSlide | zoomInScroll */
        animationCurve: 'ease',
        animationDuration: 1000,
        animationInterval: 7000,
        //slideClass: 'jR3DCarouselCustomSlide',
        autoplay: true,
        onSlideShow: show,		/* callback when Slide show event occurs */
        //navigation: 'circles',	/* circles | squares */
        slides: slides, 			/* array of images source or gets slides by 'slide' class */
        links: links
    }
    function setUp(){
        jR3DCarousel = $('.jR3DCarouselGallery_3').jR3DCarousel(carouselProps);
        $('.settings').html('<pre>$(".jR3DCarouselGallery_3").jR3DCarousel('+JSON.stringify(carouselProps, null, 4)+')</pre>');
        for (var ii = 0; ii < links.length; ii ++) {
            $(".jR3DCarouselGallery_3 a").eq(ii).attr('href', links[ii])
        }
    }
    var j = 1
    function show(slide){
        if (j++%2 == 1) {
            $(".jR3DCarouselGallery_3 a").eq(0).hide()
            $(".jR3DCarouselGallery_3 a").eq(1).hide()
            $(".jR3DCarouselGallery_3 a").eq(2).show()
            $(".jR3DCarouselGallery_3 a").eq(3).show()
        } else {
            $(".jR3DCarouselGallery_3 a").eq(0).show()
            $(".jR3DCarouselGallery_3 a").eq(1).show()
            $(".jR3DCarouselGallery_3 a").eq(2).hide()
            $(".jR3DCarouselGallery_3 a").eq(3).hide()
        }
        //console.log("Slide shown: ", slide.find('img').attr('src'))
    }
    $('.carousel-props input').change(function(){
        if(isNaN(this.value))
            carouselProps[this.name] = this.value || null;
        else
            carouselProps[this.name] = Number(this.value) || null;
        for(var i = 0; i < 999; i++)
            clearInterval(i);
        $('.jR3DCarouselGallery_4').empty();
        setUp();
        jR3DCarousel_3.showNextSlide();
    })
    $('[name=slides]').change(function(){
        carouselProps[this.name] = getSlides(this.value);
        for (var i = 0; i < 999; i++)
            clearInterval(i);
        $('.jR3DCarouselGallery_4').empty();
        setUp();
        jR3DCarousel_3.showNextSlide();
    });

    function getSlides(no){
        slides = [];
        for ( var i = 0; i < no; i++) {
            slides.push({src: 'https://unsplash.it/'+Math.floor(1366-Math.random()*200)+'/'+Math.floor(768+Math.random()*200)})
        }
        return slides;
    }
    //carouselProps.slides = getSlides(7);
    setUp()
})

$(document).ready(function(){
    var slides = [
        {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/june_2019_2.webp'},
        {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/june_2019_1.webp'},
    ]
    var links = [
        '/catalog/?q=%D0%91%D0%B0%D0%BB%D1%8C%D0%B7%D0%B0%D0%BC+Swiss&s=',
        '/catalog/?q=%D0%91%D0%B0%D0%BB%D1%8C%D0%B7%D0%B0%D0%BC+Swiss&s=',
        '/catalog/antidiareynye-preparaty-sredstva-primenyaemye-dlya-lecheniya-infektsionno-vospalitelnykh-zabolevani/sorbeks-kaps-20/',
        '/catalog/antidiareynye-preparaty-sredstva-primenyaemye-dlya-lecheniya-infektsionno-vospalitelnykh-zabolevani/sorbeks-kaps-20/'
    ]
    var jR3DCarousel_4;
    var carouselProps =  {
        width: 247, 				/* largest allowed width */
        height: 391, 				/* largest allowed height */
        slideLayout : 'fill',     /* "contain" (fit according to aspect ratio), "fill" (stretches object to fill) and "cover" (overflows box but maintains ratio) */
        animation: 'slide3D', 		/* slide | scroll | fade | zoomInSlide | zoomInScroll */
        animationCurve: 'ease',
        animationDuration: 1000,
        animationInterval: 7000,
        //slideClass: 'jR3DCarouselCustomSlide',
        autoplay: true,
        onSlideShow: show,		/* callback when Slide show event occurs */
        //navigation: 'circles',	/* circles | squares */
        slides: slides, 			/* array of images source or gets slides by 'slide' class */
        links: links
    }
    function setUp(){
        jR3DCarousel = $('.jR3DCarouselGallery_4').jR3DCarousel(carouselProps);
        $('.settings').html('<pre>$(".jR3DCarouselGallery_4").jR3DCarousel('+JSON.stringify(carouselProps, null, 4)+')</pre>');
        for (var ii = 0; ii < links.length; ii ++) {
            $(".jR3DCarouselGallery_4 a").eq(ii).attr('href', links[ii])
        }
    }
    var j = 1
    function show(slide){
        if (j++%2 == 1) {
            $(".jR3DCarouselGallery_4 a").eq(0).hide()
            $(".jR3DCarouselGallery_4 a").eq(1).hide()
            $(".jR3DCarouselGallery_4 a").eq(2).show()
            $(".jR3DCarouselGallery_4 a").eq(3).show()
        } else {
            $(".jR3DCarouselGallery_4 a").eq(0).show()
            $(".jR3DCarouselGallery_4 a").eq(1).show()
            $(".jR3DCarouselGallery_4 a").eq(2).hide()
            $(".jR3DCarouselGallery_4 a").eq(3).hide()
        }
        //console.log("Slide shown: ", slide.find('img').attr('src'))
    }
    $('.carousel-props input').change(function(){
        if(isNaN(this.value))
            carouselProps[this.name] = this.value || null;
        else
            carouselProps[this.name] = Number(this.value) || null;
        for(var i = 0; i < 999; i++)
            clearInterval(i);
        $('.jR3DCarouselGallery_4').empty();
        setUp();
        jR3DCarousel_4.showNextSlide();
    })
    $('[name=slides]').change(function(){
        carouselProps[this.name] = getSlides(this.value);
        for (var i = 0; i < 999; i++)
            clearInterval(i);
        $('.jR3DCarouselGallery_4').empty();
        setUp();
        jR3DCarousel_4.showNextSlide();
    });

    function getSlides(no){
        slides = [];
        for ( var i = 0; i < no; i++) {
            slides.push({src: 'https://unsplash.it/'+Math.floor(1366-Math.random()*200)+'/'+Math.floor(768+Math.random()*200)})
        }
        return slides;
    }
    //carouselProps.slides = getSlides(7);
    setUp()
})

$(document).ready(function(){
    var slides = [
        {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/484_765_7.webp'},
        {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/484_765_8.webp'},
    ]
    var jR3DCarousel_4;
    var carouselProps =  {
        width: 247, 				/* largest allowed width */
        height: 391, 				/* largest allowed height */
        slideLayout : 'fill',     /* "contain" (fit according to aspect ratio), "fill" (stretches object to fill) and "cover" (overflows box but maintains ratio) */
        animation: 'slide3D', 		/* slide | scroll | fade | zoomInSlide | zoomInScroll */
        animationCurve: 'ease',
        animationDuration: 1000,
        animationInterval: 7000,
        //slideClass: 'jR3DCarouselCustomSlide',
        autoplay: true,
        onSlideShow: show,		/* callback when Slide show event occurs */
        //navigation: 'circles',	/* circles | squares */
        slides: slides 			/* array of images source or gets slides by 'slide' class */
    }
    function setUp(){
        jR3DCarousel = $('.jR3DCarouselGallery_4').jR3DCarousel(carouselProps);
        $('.settings').html('<pre>$(".jR3DCarouselGallery_4").jR3DCarousel('+JSON.stringify(carouselProps, null, 4)+')</pre>');
    }
    function show(slide){
        //console.log("Slide shown: ", slide.find('img').attr('src'))
    }
    $('.carousel-props input').change(function(){
        if(isNaN(this.value))
            carouselProps[this.name] = this.value || null;
        else
            carouselProps[this.name] = Number(this.value) || null;
        for(var i = 0; i < 999; i++)
            clearInterval(i);
        $('.jR3DCarouselGallery_4').empty();
        setUp();
        jR3DCarousel_4.showNextSlide();
    })
    $('[name=slides]').change(function(){
        carouselProps[this.name] = getSlides(this.value);
        for (var i = 0; i < 999; i++)
            clearInterval(i);
        $('.jR3DCarouselGallery_4').empty();
        setUp();
        jR3DCarousel_4.showNextSlide();
    });

    function getSlides(no){
        slides = [];
        for ( var i = 0; i < no; i++) {
            slides.push({src: 'https://unsplash.it/'+Math.floor(1366-Math.random()*200)+'/'+Math.floor(768+Math.random()*200)})
        }
        return slides;
    }
    //carouselProps.slides = getSlides(7);
    setUp()
})