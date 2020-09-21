<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
//pre($_SESSION['RELATIVE_BLOG']);
//pre($_SESSION['BRAND_ELEMENT_VALUE_GOODS']);
//pre('11');

if(true === isset($_SESSION['RELATIVE_BLOG']['ID'])
	&& $_SESSION['RELATIVE_BLOG']['DETAIL']==='Y') {
	$VALUES = array();
	$res = CIBlockElement::GetProperty(
		$_SESSION['RELATIVE_BLOG']['IBLOCK_ID'],
		$_SESSION['RELATIVE_BLOG']['ID'],
		"sort",
		"asc",
		array("CODE" => "RELATED_GOODS"));
	while ($ob = $res->GetNext()) {
		$VALUES[] = $ob['VALUE'];
	}
	//pre($VALUES);

	$arFilter = Array("IBLOCK_ID"=>IntVal(2),"ACTIVE"=>"Y","ID"=>$VALUES);
	$res = CIBlockElement::GetList(array("SORT"=>"ASC"), $arFilter);
	while($ar_fields = $res->GetNext()) {
		?><div><div><?
			echo CFile::ShowImage(
				$ar_fields['DETAIL_PICTURE'],
				200,
				200,
				"border=0",
				$ar_fields['DETAIL_PAGE_URL']
			);?></div>
		<p><a href="<?=$ar_fields['DETAIL_PAGE_URL']?>"><?=$ar_fields['NAME']?></a></p>
		</div>
		<?
	}
} else {
	//BRAND
	if(true === !empty($_SESSION['BRAND_ELEMENT_VALUE_GOODS'])) {
		$PRICE_TYPE_ID = 1;
		$title =  "%". explode(" ", $APPLICATION->sDocTitle)[0] ."%";

		$arFilter = array(
			'IBLOCK_ID' => intval(2),
			//'PROPERTY_BRAND'=>$_SESSION['BRAND_ELEMENT_VALUE_GOODS']
			'SECTION_ID' => $_SESSION['BRAND_ELEMENT_SECTION_ID'],
			'ACTIVE' => 'Y',
            'NAME' => $title,
		);
		$arSelect = array('IBLOCK_ID','ID','NAME','DETAIL_PICTURE','DETAIL_PAGE_URL','PROPERTY_BRAND', 'SECT_PARENT_ID', 'XML_ID', 'SORT');
		$CIBlockElement = new CIBlockElement;
		$rsElements = $CIBlockElement->GetList(
		//array('ID' => 'ASC'),
			array('SORT' => 'ASC'),
			$arFilter,
			false,
			array('nTopCount'=>25),
			$arSelect
		);
		$arElements=array();
		//}
		?><div class="related_goods">
			<script>
				function slideImg(bgBoxId,width,height,pause) {
					//Начальная настройка .firstChild;
					let wrap = document.querySelector('ul.slide-only');
					if(wrap) {
						if (wrap.classList.contains('slide-only')) {
							let bg = document.getElementById(bgBoxId);//получаем корневой слой слайдшоу
							let dspwidth=width; //ширина
							let dspheight=height; //высота
							let imgcount=bg.children[0].getElementsByClassName('slide-only').length;
							bg.style.width=dspwidth+'px'; //устанавливаем ширину и высоту для дисплея и слоя с изображениями
							bg.style.height=dspheight+'px';
							bg.children[0].style.width=(imgcount*dspwidth)+'px';
							bg.children[0].style.height=dspheight+'px';
							bg.children[0].style.left='0px'; //устанавливаем координату в 0

							iid=window.setInterval(function() { //устанавливаем интервал смены изображений
								let minleft=-1*(dspwidth*(imgcount-1)); //вычисляем максимальное смещение
								if(minleft>=parseInt(bg.children[0].style.left)) { //проверяем не пора ли перемотать в начало
									bg.children[0].style.left='0px';//если пора - перематываем
								} else {
									bg.children[0].style.left=(parseInt(bg.children[0].style.left)-253)+'px'; //сдвигаем слой на ширину изображения
								}
							}, pause);
						}
					}
				}
				window.onload = function(e) {
					slideImg('ss1',253,'100%',4000);
				}
			</script>
		</div>
		<?php
        $url .= 'https://'.$_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        $result = parse_url($url);
        $pieces = explode("/", $result['path']);
        array_shift($pieces);
        array_pop($pieces);
        for($i = 0; $i < count($pieces); ++$i){
            //pre($pieces[0]);
            $pieces[$i];
        }

        if (count($pieces) >= 2 && $pieces[0] == 'catalog'){

//		if ( $isCatalogPage === true){

			echo '<div class="right-slide-wrap">';
			echo '<div class="ssdisplay" id="ss1">';
			echo '<div class="ssimages">';
			while ($arElem = $rsElements->GetNext()){
				//<Начало>Выводим link элемента
				echo '<ul class="slide-only">';
				$D = 0;
				while ($arElem = $rsElements->GetNext()){
                    $arElem["PHARM_LEFTOVERS"] = CIBlockElement::GetProperty(2, $arElem["ID"], "sort", "asc", array("CODE" => "PHARM_LEFTOVERS"))->GetNext()["VALUE"];
                    $arElem["LAST_AVAILABILITY"] = CIBlockElement::GetProperty(2, $arElem["ID"], "sort", "asc", array("CODE" => "LAST_AVAILABILITY"))->GetNext()["VALUE"];
                    $rsPrices = CPrice::GetList(array(), array('PRODUCT_ID' => $arElem['ID']))->Fetch();
                    $buy_status = Tools::isVisibleButtonForOrder($arElem["PHARM_LEFTOVERS"], $arElem["LAST_AVAILABILITY"], $rsPrices["PRICE"]);
                    $D++;
					echo '<li data-log="' . print_r([
                            $arElem["PHARM_LEFTOVERS"],
                            $arElem["ID"],
                            $arElem["XML_ID"],
                            $arElem["LAST_AVAILABILITY"],
                            $buy_status,
                            $rsPrices["PRICE"]
                        ], 1) . '" class="right-list">';
					echo '<a target="blank" class="slider_catalog_item_images" href="'.$arElem['DETAIL_PAGE_URL'].'">';
					//<Начало>Выводим link элемента
					//<Начало>Выводим картинку элемента
					if(!empty($arElem['DETAIL_PICTURE'])){
						$file = CFile::ResizeImageGet(
							$arElem['DETAIL_PICTURE'],
							array('width'=>253, 'height'=>256),
							BX_RESIZE_IMAGE_PROPORTIONAL,
							true
						);
						$img = '<div class="wrapper_img_slide" style="width:253px;">
												<div style="" class="inner_img_slide">
													<img alt="' . $arElem["NAME"] . '" src="'.$file['src'].'" width="'.$file['width'].'" height="'.$file['height'].'" />
												</div>
											</div>';
						echo $img;
					}else{
						$img = '<div class="wrapper_img_slide" style="width:253px;">
											<div class="inner_img_slide">
												<img src="/upload/a_obmen/JPG/no_photo.png" style="width:253px;height:256px;" />
											</div>
										</div>';
						echo $img;
					}
					//<Конец>Выводим картинку элемента
					//<Начало>Выводим Наименование элемента
					echo '<span class="name_element">'.$arElem['NAME'].'</span>'.'<br/>';
					//<Конец>Выводим Наименование элемента

					//<Начало>Выводим цену элемента
					$CPrice = new CPrice;
					$res = $CPrice->GetList(
						array(),
						array(
							"PRODUCT_ID" => $arElem['ID'],
							"CATALOG_GROUP_ID" => $PRICE_TYPE_ID
						)
					);
                    if (!in_array($buy_status, [3, 4])) {
                        if ($arr = $res->Fetch()) {
                            $arElem['PRICE'] = $arr;
                            if ($arElem['PRICE']['PRICE'] > 0) {
                                echo '<span class="element_price">' . $arElem['PRICE']['PRICE'] . ' грн.' . '</span>';
                            }
                        }
                    }
					//<Конец>Выводим цену элемента
					$arElements[]=$arElem;
                if ($buy_status == 1) {?>
					<button onclick="add2basket(<? echo $arElem['ID']; ?>); return false;" href="javascript:void(0)" class="mc-button" rel="nofollow">В корзину</button>
                    <div id="BX_BTN_<? echo $arElem['ID']; ?>"></div>
					<?php } else if (in_array($buy_status, [2, 3])) {?>
                        <script id="bx24_form_button" data-skip-moving="true">
                            (function (w, d, u, b) {
                                w['Bitrix24FormObject'] = b;
                                w[b] = w[b] || function () {
                                    arguments[0].ref = u;
                                    (w[b].forms = w[b].forms || []).push(arguments[0])
                                };
                                if (w[b]['forms']) return;
                                let s = d.createElement('script');
                                s.async = 1;
                                s.src = u + '?' + (1 * new Date());
                                let h = d.getElementsByTagName('script')[0];
                                h.parentNode.insertBefore(s, h);
                            })(window, document, 'https://b24-wn3zq2.bitrix24.ua/bitrix/js/crm/form_loader.js', 'b24form');

                            b24form({
                                "id": "8",
                                "lang": "ua",
                                "sec": "aw079k",
                                "type": "button",
                                "click": "",
                                "presets": {
                                    "my_param1": "<?=$arElem["NAME"];?>",
                                    "my_param2": "<?=$arElem["XML_ID"];?>",
                                    "my_param3": "<?=$arElem['PRICE']['PRICE'] ?>"
                                }
                            });
                        </script>
                        <button onclick="return false;" class="b24-web-form-popup-btn-8 mc-button is-not-store-right"> Под заказ </button>
                    <?php } else if (in_array($buy_status, [4])) {?>
                        <button onclick="return false;" class="mc-button is-not-store"> Нет в наличии </button>
                    <?php }
					//<Конец>Выводим link элемента
					echo '</a>';
					//<Конец>Выводим link элемента
					echo '</li>';
					if ($D%5 == 0){
						echo '</ul><ul class="slide-only">';
					}
				}
				echo '</ul>';
				//<Конец>Выводим все рекомендованные элементы
			}
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
	}
//	else {
    if (count($pieces) < 2 || $pieces[0] != 'catalog'){
		?>
		<div class="sidebar_banner">

			<!--
                            <div class="images_wrap">
                                <div class="jR3DCarouselGallery_0"></div>
                            </div>-->

<!--            <div class="images_wrap">-->
<!--                <div class="jR3DCarouselGallery_2">-->
<!--                    <a class='jR3DCarouselGallery_a' href=''></a>-->
<!--                    <a class='jR3DCarouselGallery_a_1' href=''></a>-->
<!--                    <a class='jR3DCarouselGallery_a' href=''></a>-->
<!--                    <a class='jR3DCarouselGallery_a_1' href=''></a>-->
<!--                </div>-->
<!--            </div>-->

            <div class="images_wrap">
                <div class="jR3DCarouselGallery_3">
                    <a class='jR3DCarouselGallery_a' href=''></a>
                    <a class='jR3DCarouselGallery_a_1' href=''></a>
                    <a class='jR3DCarouselGallery_a' href=''></a>
                    <a class='jR3DCarouselGallery_a_1' href=''></a>
                </div>
            </div>

            <div class="images_wrap">
                <div class="jR3DCarouselGallery_1">
                    <a class='jR3DCarouselGallery_a' href=''></a>
                    <a class='jR3DCarouselGallery_a_1' href=''></a>
                    <a class='jR3DCarouselGallery_a' href=''></a>
                    <a class='jR3DCarouselGallery_a_1' href=''></a>
                </div>
            </div>
<!---->
<!--            <div class="images_wrap">-->
<!--                <div class="jR3DCarouselGallery_2">-->
<!--                    <a class='jR3DCarouselGallery_a' href=''></a>-->
<!--                    <a class='jR3DCarouselGallery_a_1' href=''></a>-->
<!--                    <a class='jR3DCarouselGallery_a' href=''></a>-->
<!--                    <a class='jR3DCarouselGallery_a_1' href=''></a>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="images_wrap">-->
<!--                <div class="jR3DCarouselGallery_3">-->
<!--                    <a class='jR3DCarouselGallery_a' href=''></a>-->
<!--                    <a class='jR3DCarouselGallery_a_1' href=''></a>-->
<!--                    <a class='jR3DCarouselGallery_a' href=''></a>-->
<!--                    <a class='jR3DCarouselGallery_a_1' href=''></a>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="images_wrap">-->
<!--                <div class="jR3DCarouselGallery_4">-->
<!--                    <a class='jR3DCarouselGallery_a' href=''></a>-->
<!--                    <a class='jR3DCarouselGallery_a_1' href=''></a>-->
<!--                    <a class='jR3DCarouselGallery_a' href=''></a>-->
<!--                    <a class='jR3DCarouselGallery_a_1' href=''></a>-->
<!--                </div>-->
<!--            </div>-->

			<div class="images_wrap">
				<div class="jR3DCarouselGallery"></div>
			</div>

            <?php
            $count = Tools::getPharmacyCount();
            $phrm_cnt = $count[0];
            ?>

			<div class="qwerty">
				<span class="Count">335</span>
				<span class="Count">102</span>
			</div>

			<!-- Banner counter -->
			<script>
				$(document).ready(function(){
					setTimeout(function(){
						$('.Count').each(function () {
							$(this).prop('Counter',0).animate({
								Counter: $(this).text()
							}, {
								duration: 6000,
								easing: 'swing',
								step: function (now) {
									$(this).text(Math.ceil(now));
								}
							});
						});
					}, 1000);
				});
			</script>
		</div>
		<?
	}
}
if(true===isset($_SESSION['RELATIVE_BLOG'])){
	unset($_SESSION['RELATIVE_BLOG']);
}
if(true===isset($_SESSION['BRAND_ELEMENT_VALUE_GOODS'])){
	unset($_SESSION['BRAND_ELEMENT_VALUE_GOODS']);
}
