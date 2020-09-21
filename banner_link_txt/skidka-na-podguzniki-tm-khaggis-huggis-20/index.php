<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Скидка на подгузники ТМ Хаггис (Huggis) – 20%");
?><div class="row">
	<div class="col-xs-12">
		<div class="article-pict-wrap">
 <img width="819" alt="Скидка на подгузники ТМ Хаггис (Huggis) – 20%" src="/bitrix/templates/eshop_adapt_MC/images/slider-header/june_2019_2.jpg" height="162" title="Скидка на подгузники ТМ Хаггис (Huggis) – 20%"><br>
		</div>
 <br>
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