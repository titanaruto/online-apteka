<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональный раздел");
?>
<div class="bx_page">
	<p>В личном кабинете Вы можете проверить ход выполнения Ваших заказов, их историю, текущее состояние корзины, а также просмотреть или изменить личную информацию. </p>
	<div>
		<h2>Личная информация</h2>
		<a href="profile/">Изменить регистрационные данные</a>
	</div>
	<div>
		<h2>Мои заказы</h2>
		<a href="order/">Ознакомиться с состоянием заказов</a><br/>
		<a href="cart/">Посмотреть содержимое корзины</a><br/>
		<a href="order/">Посмотреть историю заказов</a><br/>
	</div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
