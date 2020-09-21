<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("АКЦИЯ VICHY, LA ROCHE-POSAY");
?><div class="row">
	<div class="col-xs-12">
		<div class="article-pict-wrap">
 <img width="819" alt="АКЦИЯ VICHY, LA ROCHE-POSAY" src="/bitrix/templates/eshop_adapt_MC/images/slider-header/june_2019_1.jpg" height="162" title="АКЦИЯ VICHY, LA ROCHE-POSAY"><br>
		</div>
        <br>
        Акция действует с 15.06.19 по 15.07.19
 <br> <br>
        *Скидка предоставляется от цены, действующей в аптеке «Мед-сервис», на дату приобретения товара. Скидка не суммируются с другими скидками и со скидками по программе лояльности «Мед-сервис». Скидка не предоставляются в аптеках «Мед-сервис», которые участвуют в «Специальном проекте». Компания «Мед-сервис» оставляет за собой право обеспечить наличие акционного товара не во всех аптеках. Количество акционного товара ограничено. Самолечение может быть вредным для вашего здоровья. Перед применением препарата проконсультируйтесь с врачом и ознакомьтесь с инструкцией.<br>
 <br>
        Список акционных товаров:
		</p>
		<p>

            <?php
            $f = file("action.txt");
            foreach ($f as $v) {
                echo "<p><a href='/catalog/?q=" . $v . "'>" . $v . "</a></p>";
            }
            ?>
			 &nbsp;
		</p>
    </div>
</div>
 <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php")?>