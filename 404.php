<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Ошибка 404");?>
<style>
	.error-page{

	}
	.error-page p{
		color: #02578e;
		font-size: 24px;
		margin: 20px 0;
		font-family: 'Roboto', sans-serif;
	}
	.error-page .text{
		color: #2a2a2a;
		font-size: 16px;
	}
	.error-page .bigtext{
		color: #2a2a2a;
		font-size: 20px;
		display: block;
		margin-bottom: 10px;
	}
	
	.error-page .text{
		font-family: 'Roboto', sans-serif;
	}
	.error-page .wrap-list ul{
		list-style: none;
		display: block;
		float: left;
		margin: 20px 80px  0 0;
	}
	.error-page ul:last-of-type{
		margin: 20px 0  0 0;
	}
	
	.error-page .wrap-list ul li a{
		display: block;
		text-decoration: underline;
		color: #02578e;
		line-height: 28px;
		font-size: 18px;
	}
	.error-page .linknews{
		display: block;
		text-decoration: underline;
		color: #02578e;
		line-height: 28px;
		font-size: 18px;
		font-weight: bold;
	}
	.error-page .wrap-list ul li a:hover{
		text-decoration: none;
	}
</style>
<div class="error-page">
	<p>Извините. Мы не можем найти страницу, которую Вы ищете</p>
	<span class="text">Возможно эта страница была удалена или в данный момент она недоступна.</span>
	<br />
	<br />
	<span class="bigtext">Если Вы ищете какой-либо товар, можете воспользоваться поиском ниже:</span>
	
	<br />
	<?$APPLICATION->IncludeComponent("bitrix:search.title", "404", array(
		"NUM_CATEGORIES" => "1",
		"TOP_COUNT" => "5",
		"CHECK_DATES" => "N",
		"SHOW_OTHERS" => "N",
		"PAGE" => SITE_DIR."catalog/",
		"CATEGORY_0_TITLE" => "Товары" ,
		"CATEGORY_0" => array(
		0 => "iblock_catalog",
		),
		"CATEGORY_0_iblock_catalog" => array(
		0 => "all",
		),
		"CATEGORY_OTHERS_TITLE" => "Прочее",
		"SHOW_INPUT" => "Y",
		"INPUT_ID" => "title-search-input",
		"CONTAINER_ID" => "search",
		"PRICE_CODE" => array(
		0 => "BASE",
		),
		"SHOW_PREVIEW" => "Y",
		"PREVIEW_WIDTH" => "75",
		"PREVIEW_HEIGHT" => "75",
		"CONVERT_CURRENCY" => "Y"
		),
		false
	);?>
	
	<br />
	<span class="bigtext">Приглашаем посетить другие разделы нашего сайта:</span>
	<div class="wrap-list clearfix">
		<ul>
			<li><a href="/">Главная</a></li>
			<li><a href="/about/">О нас</a></li>
			<li><a href="/nashy_apteki/">Наши аптеки</a></li>
		</ul>
		<ul>
			<li><a href="/about/delivery/">Оплата и доставка</a></li>
			<li><a href="/help/">Вопросы и ответы</a></li>
			<li><a href="/contact/">Контакты</a></li>
		</ul>
	</div>
	<br />
	
	<span class="bigtext">Что бы первыми узнавать о наших акциях и скидках, подпишитесь на нашу рассылку</span>
	<a class="linknews" href="/subscribe/">Подписаться на новости</a>
	<br />
	<br />
	
	<p>Спасибо. Всегда рады Вам помочь.</p>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
