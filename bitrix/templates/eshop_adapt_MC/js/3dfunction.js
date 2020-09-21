	$(document).ready(function(){
		var slides = [
			{src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/det-pitaniye.png'},
		  {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/lacalut-action.png'},
		]
		var jR3DCarousel_0;
		var carouselProps =  {
			width: 247, 				/* largest allowed width */
			height: 391, 				/* largest allowed height */
			slideLayout : 'fill',     /* "contain" (fit according to aspect ratio), "fill" (stretches object to fill) and "cover" (overflows box but maintains ratio) */
			animation: 'slide3D', 		/* slide | scroll | fade | zoomInSlide | zoomInScroll */
			animationCurve: 'ease',
			animationDuration: 1000,
			animationInterval: 6000,
			//slideClass: 'jR3DCarouselCustomSlide',
			autoplay: true,
			onSlideShow: show,		/* callback when Slide show event occurs */
			//navigation: 'circles',	/* circles | squares */
			slides: slides 			/* array of images source or gets slides by 'slide' class */
		}
		function setUp(){
			jR3DCarousel = $('.jR3DCarouselGallery_0').jR3DCarousel(carouselProps);
			$('.settings').html('<pre>$(".jR3DCarouselGallery_0").jR3DCarousel('+JSON.stringify(carouselProps, null, 4)+')</pre>');		
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
			$('.jR3DCarouselGallery_0').empty();
			setUp();
			jR3DCarousel_0.showNextSlide();
		})
		$('[name=slides]').change(function(){
			carouselProps[this.name] = getSlides(this.value); 
			for (var i = 0; i < 999; i++)
		     clearInterval(i);
			$('.jR3DCarouselGallery_0').empty();
			setUp();
			jR3DCarousel_0.showNextSlide();		
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
			{src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/medicart.png'},
			{src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/kardio.jpg'},
			{src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/counter.jpg'},
			{src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/invitro.jpg'},
			{src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/zakaz.png'},
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

	// $(document).ready(function(){
	// 	var slides = [
	// 	 //  {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/invitro.jpg'},
	// 		// {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/zakaz.png'},
	// 		// {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/sidebar_banner.png'},
	// 		// {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/counter.jpg'},
	// 	]
	// 	var jR3DCarousel_2;
	// 	var carouselProps =  {
	// 		width: 247, 				/* largest allowed width */
	// 		height: 391, 				/* largest allowed height */
	// 		slideLayout : 'fill',     /* "contain" (fit according to aspect ratio), "fill" (stretches object to fill) and "cover" (overflows box but maintains ratio) */
	// 		animation: 'slide3D', 		/* slide | scroll | fade | zoomInSlide | zoomInScroll */
	// 		animationCurve: 'ease',
	// 		animationDuration: 1000,
	// 		animationInterval: 7000,
	// 		//slideClass: 'jR3DCarouselCustomSlide',
	// 		autoplay: true,
	// 		onSlideShow: show,		/* callback when Slide show event occurs */
	// 		//navigation: 'circles',	/* circles | squares */
	// 		slides: slides 			 array of images source or gets slides by 'slide' class 
	// 	}
	// 	function setUp(){
	// 		jR3DCarousel = $('.jR3DCarouselGallery_2').jR3DCarousel(carouselProps);
	// 		$('.settings').html('<pre>$(".jR3DCarouselGallery_2").jR3DCarousel('+JSON.stringify(carouselProps, null, 4)+')</pre>');		
	// 	}
	// 	function show(slide){
	// 		//console.log("Slide shown: ", slide.find('img').attr('src'))
	// 	}
	// 	$('.carousel-props input').change(function(){
	// 		if(isNaN(this.value))
	// 			carouselProps[this.name] = this.value || null; 
	// 		else
	// 			carouselProps[this.name] = Number(this.value) || null; 
	// 		for(var i = 0; i < 999; i++)
	// 	     clearInterval(i);
	// 		$('.jR3DCarouselGallery_2').empty();
	// 		setUp();
	// 		jR3DCarousel_2.showNextSlide();
	// 	})
	// 	$('[name=slides]').change(function(){
	// 		carouselProps[this.name] = getSlides(this.value); 
	// 		for (var i = 0; i < 999; i++)
	// 	     clearInterval(i);
	// 		$('.jR3DCarouselGallery_2').empty();
	// 		setUp();
	// 		jR3DCarousel_2.showNextSlide();		
	// 	});
			
	// 	function getSlides(no){
	// 		slides = [];
	// 		for ( var i = 0; i < no; i++) {
	// 			slides.push({src: 'https://unsplash.it/'+Math.floor(1366-Math.random()*200)+'/'+Math.floor(768+Math.random()*200)})
	// 		}
	// 		return slides;
	// 	}
	// 		//carouselProps.slides = getSlides(7);
	// 		setUp()
	// })

	$(document).ready(function(){
		var slides = [
			{src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/cosmetics.png'},
			{src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/medicals.png'},

		]
		var jR3DCarousel_5;
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
			jR3DCarousel = $('.jR3DCarouselGallery_5').jR3DCarousel(carouselProps);
			$('.settings').html('<pre>$(".jR3DCarouselGallery_5").jR3DCarousel('+JSON.stringify(carouselProps, null, 4)+')</pre>');		
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
			$('.jR3DCarouselGallery_5').empty();
			setUp();
			jR3DCarousel_5.showNextSlide();
		})
		$('[name=slides]').change(function(){
			carouselProps[this.name] = getSlides(this.value); 
			for (var i = 0; i < 999; i++)
		     clearInterval(i);
			$('.jR3DCarouselGallery_5').empty();
			setUp();
			jR3DCarousel_5.showNextSlide();		
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
			{src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/med-tech.png'},
			{src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/twins_optical.png'},

		]
		var jR3DCarousel_6;
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
			jR3DCarousel = $('.jR3DCarouselGallery_6').jR3DCarousel(carouselProps);
			$('.settings').html('<pre>$(".jR3DCarouselGallery_6").jR3DCarousel('+JSON.stringify(carouselProps, null, 4)+')</pre>');		
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
			$('.jR3DCarouselGallery_6').empty();
			setUp();
			jR3DCarousel_6.showNextSlide();
		})
		$('[name=slides]').change(function(){
			carouselProps[this.name] = getSlides(this.value); 
			for (var i = 0; i < 999; i++)
		     clearInterval(i);
			$('.jR3DCarouselGallery_6').empty();
			setUp();
			jR3DCarousel_6.showNextSlide();		
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
		  {src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/lacalut-action.png'},
			{src: '/bitrix/templates/eshop_adapt_MC/images/rotate3d/det-pitaniye.png'},
		]
		var jR3DCarousel_4;
		var carouselProps =  {
			width: 247, 				/* largest allowed width */
			height: 391, 				/* largest allowed height */
			slideLayout : 'fill',     /* "contain" (fit according to aspect ratio), "fill" (stretches object to fill) and "cover" (overflows box but maintains ratio) */
			animation: 'slide3D', 		/* slide | scroll | fade | zoomInSlide | zoomInScroll */
			animationCurve: 'ease',
			animationDuration: 1000,
			animationInterval: 8000,
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


