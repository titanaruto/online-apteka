<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if (empty($arResult))
    return "";

$strReturn = '';

//we can't use $APPLICATION->SetAdditionalCSS() here because we are inside the buffered function GetNavChain()
$css = $APPLICATION->GetCSSArray();
if (!is_array($css) || !in_array("/bitrix/css/main/font-awesome.css", $css)) {
    $strReturn .= '<link href="' . CUtil::GetAdditionalFileURL("/bitrix/css/main/font-awesome.css") . '" type="text/css" rel="stylesheet" />' . "\n";
}

$strReturn .= '<div class="bx-breadcrumb">';
$items = '';
$itemSize = count($arResult);
$isHttps = !empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS']);
$host = ($isHttps ? "https://" : "http://") . str_replace(":443", "", $_SERVER['HTTP_HOST']);
for ($index = 0; $index < $itemSize; $index++) {
    $title = htmlspecialcharsex($arResult[$index]["TITLE"]);

    $nextRef = ($index < $itemSize - 2 && $arResult[$index + 1]["LINK"] <> "" ? ' itemref="bx_breadcrumb_' . ($index + 1) . '"' : '');
    $child = ($index > 0 ? ' itemprop=""' : '');
    $arrow = ($index > 0 ? '<span id="icon" class="icon_slash">/</span>' : '');

    if ($arResult[$index]["LINK"] <> "" && $index != $itemSize - 1) {
        $strReturn .= '
			<div class="bx-breadcrumb-item" id="bx_breadcrumb_' . $index . '" >
				' . $arrow . '
				<a style="display: block" href="' . $arResult[$index]["LINK"] . '" title="' . $title . '" >
					<span style="display: block" class="active">' . $title . '</span>
					
				</a>			
			</div>';

        $items .= '{
    "@type": "ListItem",
    "position": ' . ($index + 1) . ',
    "name": "' . $title . '",
    "item": "' .$host. $arResult[$index]["LINK"] . '"}';

        if ($index != $itemSize - 2) {
            $items .= ',';
        }
    } else {
        $strReturn .= '
			<div class="bx-breadcrumb-item">
				' . $arrow . '
				<span>' . $title . '</span>
			</div>';

    }
}

$strReturn .= '   <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [' . $items . ']
}
</script>';

$strReturn .= '<div style="clear:both"></div></div>';

return $strReturn;
