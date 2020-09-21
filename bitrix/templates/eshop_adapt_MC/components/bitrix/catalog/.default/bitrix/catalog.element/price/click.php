<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
if ($_POST['sessId'] != session_id())
    die;
if (!CModule::IncludeModule("iblock"))
    die;
$CIBlockSection = new CIBlockSection;
$CIBlockElement = new CIBlockElement;

$price = $_POST["prd-price"];
$src = $_POST["prd-src"];
$product_name = $_POST["prd-name"];
$product_id = $_POST["prd-id"];

$user_name = "";
$user_id = $USER->GetID();
if (!is_null($user_id)) {
    $user_name = $USER->GetFirstName();
}
$arFilter = array(
    'IBLOCK_ID' => intval(12),
    'ACTIVE' => 'Y',
);
$arSelectApt = array(
    'IBLOCK_ID',
    'ID',
    'IBLOCK_SECTION_ID',
    'NAME',
    'EXTERNAL_ID',
    'CODE',
    'PROPERTY_CITY',
    'PROPERTY_ADRES',
);

$rsElementApt = $CIBlockElement->GetList(array('NAME' => 'DESC'), $arFilter, false, false, $arSelectApt);
$elementAptArg = array();
echo '<div class=" " id="ord_oblast_name_list" data-vis="none">';
echo '<ul  class="row-list" id="ord_oblast_name" style="display:none">';
while ($arElementApt = $rsElementApt->Fetch()) {
    $name_shut = $arElementApt['PROPERTY_ADRES_VALUE'];

//    $elemUp_hood = array("name"=> (string) $arElementApt['NAME'], "id" => $arElementApt['EXTERNAL_ID'], "class" => "up-hood", "city_value" =>$arElementApt['PROPERTY_CITY_VALUE']);
//    $elem_hood = array("name"=>$arElementApt['NAME'], "id" => $arElementApt['EXTERNAL_ID'], "class" => "hood", "city_value" =>$arElementApt['PROPERTY_ADRES_VALUE']);
//    array_push($elementAptArg,$elemUp_hood,$elem_hood);

    echo '<li name="' . $arElementApt['NAME'] . '" id="' . $arElementApt['EXTERNAL_ID'] . '" style="display:none;" class="up-hood">' . $arElementApt['PROPERTY_CITY_VALUE'] . '</li>';
    echo '<li name="' . $arElementApt['NAME'] . '" id="' . $arElementApt['EXTERNAL_ID'] . '" class="hood">' . $arElementApt['PROPERTY_ADRES_VALUE'] . '</li>';
}
echo '</ul>';
echo '</div>';
//$JSON_OBJ_APT =  json_encode($elementAptArg,JSON_UNESCAPED_UNICODE);
//print_r($JSON_OBJ_APT);
?>


<form class="one-click" method="post" autocomplete="off">
    <div class="one-click__inputs">
        <input type="text" name="inputs__name" class="inputs__name inputs--all" autocomplete="new-name" value="<?= $user_name; ?>"
               placeholder="Имя">
        <input type="tel" name="inputs__phone" class="inputs__phone inputs--all" autocomplete="new-tel"  placeholder="Телефон*" required>
        <input type="text" hidden name="inputs__product-id" id="inputs__product-id" value="<?= $product_id; ?>">
        <input type="text" hidden name="inputs__apt_id" id="inputs__apt_id" value="">
        <div class="inputs__block">
            <input type="text" name="inputs__city" class="inputs__city inputs--all" autocomplete="new-city" required
                   placeholder="Город*">
            <ul id="inputs__city-list" class="inputs__city-list inputs__list inputs--all" style="display: none">

            </ul>
        </div>
        <div class="inputs__block">
            <input type="text" name="inputs__adress" class="inputs__adress inputs--all"  autocomplete="new-adress" required
                   placeholder="Адресс аптеки*">
            <ul id="inputs__adress-list" class="inputs__adress-list inputs__list inputs--all" style="display: none">
            </ul>
        </div>
        <div class="one-click__price-work">
            <div class="one-click__amount">
                <button onclick="return false;" class="price-work__minus price-work--btn">-</button>
                <input type="text" name="price-work__ammount" readonly value="1" class="price-work__ammount">
                <button onclick="return false;" class="price-work__plus price-work--btn">+</button>
            </div>
            <div class="one-click__price">
                <label class="price-work__price" price-val="<?= $price ?>"><?= $price ?></label> грн
            </div>


        </div>

    </div>
    <div class="one-click__desktop">

        <div class="one-click__view">
            <img src="<?= $src; ?>"
                 alt="<?= $product_name; ?>" class="view__picture">
            <p class="view__name-product"><?= $product_name; ?></p>
        </div>
        <div class="one-click__total">

            <p class="total__summ">ИТОГО:
                <label class="summ__price"><?= $price ?> грн</label>
            </p>
            <input type="button" onclick="return false;" class="total__buy" value="Купить"/>
        </div>
    </div>


</form>

<script>
    //количество
    let amount = $(".price-work__ammount");
    //кнопка плюс
    let plus = $(".price-work__plus");
    //кнопка минус
    let minus = $(".price-work__minus");
    // цена
    let price = $(".price-work__price");
    //Имя пользователя
    let name = $(".inputs__name");
    //телефон
    let phone = $(".inputs__phone");
    // id товара
    let product_id = $("#inputs__product-id");
    //итоговая цена
    let total = $(".summ__price");
    //город
    let city = $(".inputs__city");
    //адресс аптеки
    let adress_apt = $(".inputs__adress");
    // список городов
    let inputs__city_list = $(".inputs__city-list");
    // список адресов аптек
    let inputs__adress_list = $(".inputs__adress-list");
    //главный список от куда беру данные про города
    let ord_oblast_name = $("#ord_oblast_name");
    //let ord_oblast_name = Array.from( <?//= $JSON_OBJ_APT;?>//);
    // console.log(ord_oblast_name);

    //айди аптеки 1с
    let id_local = $("#inputs__apt_id");
    //кнопка купить
    let buy_btn = $(".total__buy");
    // айди городов
    let cityId = "inputs__city-list";
    // айди адресоов аптек
    let addressId = "inputs__adress-list";
    //цена товара
    const priceNow = Number.parseFloat(price.attr("price-val")).toFixed(2);

    $(document).ready(function () {
        // кнопки купить обработчик
        buy_btn.click(function () {
            if (phone.val() === "") {
                phone.css("border", "rgb(255, 4, 4) solid 1px");
            } else {
                phone.css("border", "rgb(4, 126, 4) solid 1px");
            }
            if (city.val() === "") {
                city.css("border", "rgb(255, 4, 4) solid 1px");
            } else {
                city.css("border", "rgb(4, 126, 4) solid 1px");
            }
            if (adress_apt.val() === "") {
                adress_apt.css("border", "rgb(255, 4, 4) solid 1px");
            } else {
                adress_apt.css("border", "rgb(4, 126, 4) solid 1px");
            }
            if (phone.val() !== "" && city.val() !== "" && adress_apt.val() !== "" && id_local.val() !== "") {
                // buy_btn.css("background-color", "rgb(4, 126, 4)");
                let count = Number.parseInt(amount.val());
                let adress = city.val() + ", " + adress_apt.val();
                // console.log(`name: ${name.val()}  phone: ${phone.val()} adress: ${adress} id_local: ${id_local.val()}  product_id: ${product_id.val()}  QUANTITY: ${count}`);
                $("#ord_oblast_name_list").remove();
                $(".one-click").remove();
                $("#ajax-one-click").append('<div class="leftovers_cssload-container" >' +
                    '                    <div class="leftovers_cssload-speeding-wheel"></div>' +
                    '                    </div>');
                $("#ajax-one-click .leftovers_cssload-container").css("display", "block");
                $("#ajax-one-click .leftovers_cssload-container").css("margin-bottom", "40px");
                // debugger;
                Order1click(name.val(), phone.val(), adress, id_local.val(), product_id.val(), count);
            }
        });

        //обработчик ввода данных в поле Города
        city.keyup(function (e) {
            searchCity(ord_oblast_name, "up-hood", city, inputs__city_list, cityId);
        });
        //обработчик ввода данных в поле Адреса аптек
        adress_apt.keyup(function () {
            searchApt(inputs__adress_list, adress_apt, addressId, id_local);
        });
        //обработчик активности поля Адреса аптек
        adress_apt.hover(function () {
            if (adress_apt.val() === "" && city.val() !== "") {
                searchApt(inputs__adress_list, adress_apt, addressId, id_local);
            }
        });


        $(".inputs__phone").mask("+38 (999) 99 999 99");
//обработчик нажатия кнопки плюс
        plus.click(function () {
            let count = Number.parseInt(amount.val());
            count++;
            amount.val(count);
            let summa = (priceNow * count).toFixed(2);
            price.html(summa);
            total.html(summa + " грн");

        });
        //обработчик нажатия кнопки минус
        minus.click(function () {
            let count = Number.parseInt(amount.val());
            if (count > 1) {
                count--;
                amount.val(count);
                let summa = (priceNow * count).toFixed(2);
                price.html(summa);
                total.html(summa + " грн");
            }
        });
    });

    //функиця вывода успешного заказа
    function showSuccessResult() {
        $("#ord_oblast_name_list").remove();
        $(".leftovers_cssload-container").remove();
        $(".one-click").remove();
        $("#ajax-one-click").append('<h3 class="one-click-form-success" >Заказ оформлен успешно!</h3>');
    }

    //функция поиска аптек по определенному городу при вводе данных в поле Адрес аптек
    const searchApt = function (obj, key, sortId, resultAptId) {
        obj.css("display", "block");
        obj.each(function (i, ul) {
            $(ul).find("li").each(function (j, li) {
                if (li.textContent.toLowerCase().includes(key.val().toLowerCase())
                    && li.textContent.charAt(0).toLowerCase() === key.val().charAt(0).toLowerCase() || key.val() === "") {
                    $(li).css('display', "block");
                    boolres = true;

                } else {
                    $(li).css('display', "none");
                }
            });
        });
        let clickli = "." + sortId + " li";
        $(clickli).click(function (e) {
            key.val(e.currentTarget.textContent);
            obj.css("display", "none");
            resultAptId.val(e.currentTarget.id);
        });
    };
    //функция поиска аптек по определенному городу
    const searchAdress = function (obj, className, changeObj, sortId, city) {
        let boolres = false;
        changeObj.css("display", "block");
        changeObj.html("");
        obj.each(function (i, ul) {
            $(ul).find("li").each(function (j, li) {
                if ($(li).hasClass(className) && li.textContent.toLowerCase().includes(city.toLowerCase())) {
                    changeObj.append('<li class="list__item" id="' + li.nextSibling.id + '">' + li.nextSibling.textContent + '</li>');
                    boolres = true;
                    $('.list__item').on('click', e => {
                        if ($(".inputs__adress").val() === "" && $(".inputs__city").val() !== "") {
                            $(".inputs__adress").val(e.currentTarget.textContent);
                            $(".inputs__adress-list").css("display", "none");
                            $("#inputs__apt_id").val(e.currentTarget.id);
                        }
                    })
                }
            });
        });
        // sortUnorderedList(sortId);
    };

    //функция поиска при вводе данных в поле Города
    const searchCity = function (obj, className, key, changeObj, sortId) {
        if (key.val() !== "") {
            let boolres = false;
            changeObj.css("display", "block");
            changeObj.html("");
            obj.each(function (i, ul) {
                $(ul).find("li").each(function (j, li) {
                    if ($(li).hasClass(className) && li.textContent.toLowerCase().includes(key.val().toLowerCase())
                        && li.textContent.charAt(0).toLowerCase() === key.val().charAt(0).toLowerCase()) {
                        let res = searchUnicueLi(changeObj, li.textContent);
                        if (res !== true) {
                            changeObj.append('<li class="list__item">' + li.textContent + '</li>');
                            boolres = true;
                        }
                    }
                });
            });
            if (boolres === false) {
                changeObj.css("display", "none");
            }
            sortUnorderedList(sortId);
            let clickli = "." + sortId + " li";
            $(clickli).click(function (e) {
                // console.log(e.currentTarget.textContent);
                key.val(e.currentTarget.textContent);
                changeObj.css("display", "none");
                searchAdress(ord_oblast_name, "up-hood", inputs__adress_list, addressId, city.val());
            });
        } else {
            changeObj.css("display", "none");
        }
    };

    //функция сортировки
    function sortUnorderedList(ul, sortDescending) {
        if (typeof ul == "string")
            ul = document.getElementById(ul);

        // Idiot-proof, remove if you want
        if (!ul) {
            alert("The UL object is null!");
            return;
        }

        // Get the list items and setup an array for sorting
        var lis = ul.getElementsByTagName("LI");
        var vals = [];

        // Populate the array
        for (var i = 0, l = lis.length; i < l; i++)
            vals.push(lis[i].textContent);

        // Sort it
        vals.sort();

        // Sometimes you gotta DESC
        if (sortDescending)
            vals.reverse();

        // Change the list on the page
        for (var i = 0, l = lis.length; i < l; i++)
            lis[i].textContent = vals[i];
    }

    //функция проверки на уникальность Города
    const searchUnicueLi = function (obj, text) {
        let boolingResult = false;
        obj.each(function (i, ul) {
            $(ul).find("li").each(function (j, li) {
                // debugger;
                if (li.textContent === text)
                    boolingResult = true;
            });
        });
        return boolingResult;

    };

    //функция оформления заказа
    function Order1click(name, phone, adres, id_local, product_id, QUANTITY = 1) {
        $.ajax({
            type: "POST",
            url: "/bitrix/templates/eshop_adapt_MC/components/bitrix/catalog/.default/ajax/addorderclickform.php",
            data: {
                name: name,
                phone: phone,
                adres: adres,
                id_local: id_local,
                product_id: product_id,
                QUANTITY: QUANTITY,
            },
            success: function (msg) {
                showSuccessResult();
            },
        });
    }


</script>
