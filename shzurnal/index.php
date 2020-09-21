<?
include_once $_SERVER["DOCUMENT_ROOT"].'/redirect.php';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог акций");
?>

<script>
	$(function(){
       $('#demo5').scrollbox({
	    	direction: 'h',
	    	//distance: 200
	  	});
	   $('#demo5-backward').click(function () {
	    	$('#demo5').trigger('backward');
	  	});
	   $('#demo5-forward').click(function () {
	    	$('#demo5').trigger('forward');
	   });
	});
</script>
<style>
    #slides {display: none;}
    #slides .slidesjs-navigation {
   		margin-top:3px;
    	display: block !important;
    	position: absolute;
    	top: 50%;
    }
    #slides .slidesjs-previous {
      margin-right: 5px;
      float: left;
      width: 128px;
      height: 128px;
      background: url("arrow_top_slider.png") no-repeat;
      left: -150px;
    }

    #slides .slidesjs-next {
      margin-right: 5px;
      float: left;
      width: 128px;
      height: 128px;
      background: url("arrow_top_slider.png") no-repeat;
      right: -155px;
      -webkit-transform: rotade(-180deg);
      -moz-transform: rotate(-180deg);
      -ms-transform: rotate(-180deg);
      -o-transform: rotate(-180deg);
      transform: rotate(-180deg);
    }
    .slidesjs-pagination {
      margin: 6px 0 0;
      float: right;
      list-style: none;
      display: none;
    }
    .slidesjs-pagination li {
      float: left;
      margin: 0 1px;
    }
    .slidesjs-pagination li a {
      display: block;
      width: 13px;
      height: 0;
      padding-top: 13px;
      background-image: url(img/pagination.png);
      background-position: 0 0;
      float: left;
      overflow: hidden;
    }

    .slidesjs-pagination li a.active,
    .slidesjs-pagination li a:hover.active {
      background-position: 0 -13px
    }

    .slidesjs-pagination li a:hover {
      background-position: 0 -26px
    }

    #slides a:link,
    #slides a:visited {
      color: #333;
      display: none;
    }

    #slides a:hover,
    #slides a:active {
      color: #9e2020
    }

    .navbar {overflow: hidden;}
    #slides {display: none;}
</style>


  <script src="jquery.slides.min.js"></script>

  <script>
   /* $(function() {
      $('#slides').slidesjs({
        width: 638,
        height: 900,
        navigation: false
      });
    });*/
  </script>

<style>
iframe{margin: 20px 0;}
</style>



<div class="jurnal">
	<a class="last-jurnal" target="_blank" href="jurnal_18/jurnal_18.php" >
		<img src="preview_jurmal/18.png" alt="8" class="jurnal__preview-img" />
	</a>
	<div class="wrap-scroll-img">
		<div id="demo5" class="scroll-img">
			<ul>
				<!--li>
					<a target="blank" href="jurnal.php" class="overlaw">
						<img src="preview_jurmal/1.jpg" alt="" />
					</a>
					<!--<a href="" title="" class="download"></a>
				</li-->

                <li>
                    <a target="_blank" href="jurnal_18/jurnal_18.php" class="overlaw">
                        <img src="preview_jurmal/18.png" alt="" />
                    </a>
                    <a href="/shzurnal/download/jurnal_18.pdf" title="" class="download"></a>
                </li>

                <li>
                    <a target="_blank" href="jurnal_17/jurnal_17.php" class="overlaw">
                        <img src="preview_jurmal/17.png" alt="" />
                    </a>
                    <a href="/shzurnal/download/jurnal_17.pdf" title="" class="download"></a>
                </li>

                <li>
                    <a target="_blank" href="jurnal_16/jurnal_16.php" class="overlaw">
                        <img src="preview_jurmal/16.png" alt="" />
                    </a>
                    <a href="/shzurnal/download/jurnal_16.pdf" title="" class="download"></a>
                </li>

                <li>
                    <a target="_blank" href="jurnal_15/jurnal_15.php" class="overlaw">
                        <img src="preview_jurmal/15.webp" alt="" />
                    </a>
                    <a href="/shzurnal/download/jurnal_15.pdf" title="" class="download"></a>
                </li>

<!--				<li>-->
<!--					<a target="_blank" href="jurnal_2/jurnal_2.php" class="overlaw">-->
<!--						<img src="preview_jurmal/2.jpg" alt="" />-->
<!--					</a>-->
<!--					<a href="/shzurnal/download/jurnal_2.pdf" title="" class="download"></a>-->
<!--				</li>-->
<!--				<li>-->
<!--					<a target="_blank" href="jurnal_3/jurnal_3.php" class="overlaw">-->
<!--						<img src="preview_jurmal/3.jpg" alt="" />-->
<!--					</a>-->
<!--					<a href="/shzurnal/download/jurnal_3.pdf" title="" class="download"></a>-->
<!--				</li>-->
<!--				<li>-->
<!--					<a target="_blank" href="jurnal_4/jurnal_4.php" class="overlaw">-->
<!--						<img src="preview_jurmal/4.jpg" alt="" />-->
<!--					</a>-->
<!--					<a href="/shzurnal/download/jurnal_4.pdf" title="" class="download"></a>-->
<!--				</li>-->
<!---->
<!--				<li>-->
<!--					<a target="_blank" href="jurnal_5/jurnal_5.php" class="overlaw">-->
<!--						<img src="preview_jurmal/5.jpg" alt="" />-->
<!--					</a>-->
<!--					<a href="/shzurnal/download/jurnal_5.pdf" title="" class="download"></a>-->
<!--				</li>-->
<!---->
<!--				<li>-->
<!--					<a target="_blank" href="jurnal_6/jurnal_6.php" class="overlaw">-->
<!--						<img src="preview_jurmal/6.jpg" alt="" />-->
<!--					</a>-->
<!--					<a href="/shzurnal/download/jurnal_6.pdf" title="" class="download"></a>-->
<!--				</li>-->
<!---->
<!--				<li>-->
<!--					<a target="_blank" href="jurnal_7/jurnal_7.php" class="overlaw">-->
<!--						<img src="preview_jurmal/7.jpg" alt="" />-->
<!--					</a>-->
<!--					<a href="/shzurnal/download/jurnal_7.pdf" title="" class="download"></a>-->
<!--				</li>-->
<!---->
<!--				<li>-->
<!--					<a target="_blank" href="jurnal_8/jurnal_8.php" class="overlaw">-->
<!--						<img src="preview_jurmal/8.jpg" alt="" />-->
<!--					</a>-->
<!--					<a href="/shzurnal/download/jurnal_8.pdf" title="" class="download"></a>-->
<!--				</li>-->
<!---->
<!--				<li>-->
<!--					<a target="_blank" href="jurnal_9/jurnal_9.php" class="overlaw">-->
<!--						<img src="preview_jurmal/9.jpg" alt="" />-->
<!--					</a>-->
<!--					<a href="/shzurnal/download/jurnal_9.pdf" title="" class="download"></a>-->
<!--				</li>-->
<!---->
<!--				<li>-->
<!--					<a target="_blank" href="jurnal_10/jurnal_10.php" class="overlaw">-->
<!--						<img src="preview_jurmal/10.jpg" alt="" />-->
<!--					</a>-->
<!--					<a href="/shzurnal/download/jurnal_10.pdf" title="" class="download"></a>-->
<!--				</li>-->
<!---->
<!--                <li>-->
<!--                    <a target="_blank" href="jurnal_11/jurnal_11.php" class="overlaw">-->
<!--                        <img src="preview_jurmal/11.jpg" alt="" />-->
<!--                    </a>-->
<!--                    <a href="/shzurnal/download/jurnal_11.pdf" title="" class="download"></a>-->
<!--                </li>-->
<!---->
<!--                <li>-->
<!--                    <a target="_blank" href="jurnal_12/jurnal_12.php" class="overlaw">-->
<!--                        <img src="preview_jurmal/12.jpg" alt="" />-->
<!--                    </a>-->
<!--                    <a href="/shzurnal/download/jurnal_12.pdf" title="" class="download"></a>-->
<!--                </li>-->
<!---->
<!--                <li>-->
<!--                    <a target="_blank" href="jurnal_13/jurnal_13.php" class="overlaw">-->
<!--                        <img src="preview_jurmal/13.jpg" alt="" />-->
<!--                    </a>-->
<!--                    <a href="/shzurnal/download/jurnal_13.pdf" title="" class="download"></a>-->
<!--                </li>-->
<!---->
<!--                <li>-->
<!--                    <a target="_blank" href="jurnal_14/jurnal_14.php" class="overlaw">-->
<!--                        <img src="preview_jurmal/14.png" alt="" />-->
<!--                    </a>-->
<!--                    <a href="/shzurnal/download/jurnal_14.pdf" title="" class="download"></a>-->
<!--                </li>-->

			</ul>
		</div>

		<div id="demo5-btn" class="text-center">
		   <button class="btn" id="demo5-backward"></button>
		   <button class="btn" id="demo5-forward"></button>
		</div>
	</div>
</div>


<?/*
<form method="POST" id="formx" action="javascript:void(null);" onsubmit="call()" class="form">
	<h2>Узнавайте первыми <br /> о новых выпусках нашего журнала </h2>
	<p>Заполните, пожалуйста, анкету и получайте бесплатную информацию о здоровье, <br />
		свежие новости и выгодные предложения
	</p>

	<div class="half">
		<input type="text" id="name" value="" placeholder="ФИО" name="name"/>
		<input type="text" id="city" value="" placeholder="ГОРОД" name="town"/>
		<input type="text" id="index" value="" placeholder="ПОЧТОВЫЙ ИНДЕКС" name="index"/>
		<input type="text" id="email" value="" placeholder="EMAIL" name="email"/>
	</div>
	<div class="half">
		<input type="text" id="region" value="" placeholder="ОБЛАСТЬ" name="region"/>
		<input type="text" id="adress" value="" placeholder="АДРЕС" name="adress"/>
		<input type="text" id="phone" value="" placeholder="ТЕЛЕФОН" name="phone"/>
		<div>
			<input type="checkbox" />
			<span>я даю согласие на обработку моих персональных данных и получение последних новостей об акциях и скидках в сети аптек "мед-сервис" на указаную электронную почту</span>
		</div>
	</div>
		<input class="submit" type="submit" value="подписаться"/>
	</div>
</form>

<!--div class="results">вывод</div-->
<script type="text/javascript" language="javascript">
 	function call() {
 	  var msg   = $('#formx').serialize();
 	  //alert(msg);
        $.ajax({
          type: 'POST',
          url: '/skidki-i-akcii/shzurnal/podpiska/',
          data: msg,
          success: function(data) {
            $('.results').html(data);
          },
          error:  function(xhr, str){
	    alert('Возникла ошибка: ' + xhr.responseCode);
          }
        });

    }
</script>
*/?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>