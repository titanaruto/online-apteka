<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if (!empty($arResult)):?>
    <ul id="vertical-multilevel-menu">
    <?
    $previousLevel = 0;
    foreach($arResult as $arItem):?>
        <?/*pre($arItem["DEPTH_LEVEL"]);*/?>
        <?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
            <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
        <?endif?>
        <?if ($arItem["IS_PARENT"]):?>
            <?if ($arItem["DEPTH_LEVEL"] == 1):?>
                <li class="ss-flex0">
                <a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?><i class="fa fa-angle-down"></i></a>
                <ul class="root-item test-root">
            <?else:?>
                <li class="ss-flex1"><a href="<?=$arItem["LINK"]?>" class=" <?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?><i class="fa fa-angle-down"></i></a>
                <ul class="test-root2">
            <?endif?>
        <?else:?>
            <?if ($arItem["DEPTH_LEVEL"] == 1):?>
                <li class="ss-flex0">
                    <a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?></a>
                </li>

            <?elseif ($arItem["PERMISSION"] > "D"):?>
                <?if ($arItem["DEPTH_LEVEL"] == 2):?>
                    <li class="ss-flex2"><a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?></a></li>
                <?else:?>
                    <li class="ss-flex3"><a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?></a></li>
                <?endif?>
            <?else:?>
                <?if ($arItem["DEPTH_LEVEL"] == 3):?>
                    <li class="ss-flex4"><a href="" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></li>
                <?else:?>
                    <li class="ss-flex5"><a href="" class="denied" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></li>
                <?endif?>
            <?endif?>
        <?endif?>
        <?$previousLevel = $arItem["DEPTH_LEVEL"];?>
    <?endforeach?>
    <?if ($previousLevel > 1)://close last item tags?>
        <?=str_repeat("</ul></li>", ($previousLevel-1) );?>
    <?endif?>
    </ul>
<?endif?>


                    <!--old template-->
<?//if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?//if (!empty($arResult)):?>
<!--<ul id="vertical-multilevel-menu">-->
<?//
//$previousLevel = 0;
//foreach($arResult as $arItem):?>
<!--	--><?///*pre($arItem["DEPTH_LEVEL"]);*/?>
<!--	--><?//if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
<!--		--><?//=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
<!--	--><?//endif?>
<!--	--><?//if ($arItem["IS_PARENT"]):?>
<!--		--><?//if ($arItem["DEPTH_LEVEL"] == 1):?>
<!--			<li>-->
<!--				<a href="--><?//=$arItem["LINK"]?><!--" class="--><?//if ($arItem["SELECTED"]):?><!--root-item-selected--><?//else:?><!--root-item--><?//endif?><!--">--><?//=$arItem["TEXT"]?><!--<i class="fa fa-angle-down"></i></a>-->
<!--				<ul class="root-item">-->
<!--		--><?//else:?>
<!--			<li><a href="--><?//=$arItem["LINK"]?><!--" class=" --><?//if ($arItem["SELECTED"]):?><!--root-item-selected--><?//else:?><!--root-item--><?//endif?><!--">--><?//=$arItem["TEXT"]?><!--<i class="fa fa-angle-down"></i></a>-->
<!--				<ul>-->
<!--		--><?//endif?>
<!--	--><?//else:?>
<!--		--><?//if ($arItem["PERMISSION"] > "D"):?>
<!--			--><?//if ($arItem["DEPTH_LEVEL"] == 2):?>
<!--				<li><a href="--><?//=$arItem["LINK"]?><!--" class="--><?//if ($arItem["SELECTED"]):?><!--root-item-selected--><?//else:?><!--root-item--><?//endif?><!--">--><?//=$arItem["TEXT"]?><!--</a></li>-->
<!--			--><?//else:?>
<!--				<li><a href="--><?//=$arItem["LINK"]?><!--" class="--><?//if ($arItem["SELECTED"]):?><!--root-item-selected--><?//else:?><!--root-item--><?//endif?><!--">--><?//=$arItem["TEXT"]?><!--</a></li>-->
<!--			--><?//endif?>
<!--		--><?//else:?>
<!--			--><?//if ($arItem["DEPTH_LEVEL"] == 3):?>
<!--				<li><a href="" class="--><?//if ($arItem["SELECTED"]):?><!--root-item-selected--><?//else:?><!--root-item--><?//endif?><!--" title="--><?//=GetMessage("MENU_ITEM_ACCESS_DENIED")?><!--">--><?//=$arItem["TEXT"]?><!--</a></li>-->
<!--			--><?//else:?>
<!--				<li><a href="" class="denied" title="--><?//=GetMessage("MENU_ITEM_ACCESS_DENIED")?><!--">--><?//=$arItem["TEXT"]?><!--</a></li>-->
<!--			--><?//endif?>
<!--		--><?//endif?>
<!--	--><?//endif?>
<!--	--><?//$previousLevel = $arItem["DEPTH_LEVEL"];?>
<?//endforeach?>
<?//if ($previousLevel > 1)://close last item tags?>
<!--	--><?//=str_repeat("</ul></li>", ($previousLevel-1) );?>
<?//endif?>
<!--</ul>-->
<?//endif?>