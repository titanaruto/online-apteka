{"version":3,"file":"script.min.js","sources":["script.js"],"names":["window","BX","CatalogMenu","items","idCnt","currentItem","overItem","outItem","timeoutOver","timeoutOut","getItem","item","type","isDomNode","id","isNotEmptyString","this","CatalogMenuItem","itemOver","menuItem","clearTimeout","setTimeout","itemOut","removeHover","curItem","parentNode","querySelectorAll","i","length","hasClass","removeClass","onTouch","link","document","location","href","element","popup","findChild","className","isLastItem","lastChild","prototype","addClass","popupRightEdge","getBoundingClientRect","left","offsetWidth","body","clientWidth","style","right","namespace","Main","Menu","CatalogHorizontal","menuBlockId","itemImgDesc","resizeMenu","bind","proxy","toggleInMobile","parentObj","findParent","arrow","firstChild","templateWrap","menuMobile","querySelector","menuMobileButton","create","attrs","data-role","children","clone","insertBefore","events","click","overflow","changeSectionPicure","itemId","curLi","imgDescObj","imgObj","tagName","src","linkObj","descObj","innerHTML"],"mappings":"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"}