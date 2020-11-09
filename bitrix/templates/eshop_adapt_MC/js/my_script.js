$(document).ready(function() {
    function isOpen (shedule) {
        let res = shedule.split("-")
        let result = new Array
        let from = res[0].trim()
        let to = res[1].trim()
        let closeColor = 'FF7F50'
        let openColor = '90EE90'
        let exc = new Array
        let dayArray = new Array("вс", "пн", "вт", "ср", "чт", "пн", "сб")

        result[2] = '(' + shedule + ')'
        if (from == '00:00') {
            result[0] = 'Открыта'
            result[1] = openColor
        } else {
            let openClose = shedule.split('-')
            let date = new Date()
            let year = date.getFullYear()
            let month = date.getMonth()
            let day = date.getDate()
            let hours = date.getHours()
            let minutes = date.getMinutes()
            let currentTimeStamp = new Date(year, month, day, hours, minutes).getTime();
            let openTimeStamp = new Date(year, month, day, openClose[0].split(":")[0]).getTime();
            let closeTimeStamp = new Date(year, month, day, openClose[1].split(":")[0]).getTime();
            let closeTime = (closeTimeStamp - currentTimeStamp) / 1000
            let openTime = (openTimeStamp - currentTimeStamp) / 1000
            if (shedule.indexOf("(") != -1) {
                exc = shedule.split("(")[1].split(":")[0].split("-")
            }
            if (closeTime < 3600 && closeTime > 0) {
                result[1] = openColor
                result[0] = 'Еще открыта'
            } else if (closeTime < 0) {
                result[1] = closeColor
                result[0] = 'Уже закрыта'
            } else if (openTime > 0 && openTime < 3600) {
                result[1] = closeColor
                result[0] = 'Еще закрыта'
            } else if (openTime > 0 || (exc.indexOf(dayArray[date.getDay()]) != -1)) {
                result[1] = closeColor
                result[0] = 'Закрыта'
            } else if (openTime <= 0 && openTime > -3600) {
                result[1] = openColor
                result[0] = 'Уже открыта'
            } else {
                result[1] = openColor
                result[0] = 'Открыта'
            }
        }
        return result
    }
    // $("#tab_1").on('click', function () {
        $(".leftovers_cssload-container").show()
        $("#leftovers_select_result select, #leftovers_select_result table").remove()
        $.ajax({
            type: "POST",
            url: $("#leftovers_ajax_path").val(),
            cache: false,
            data: {xmlId: $("#xmlId").val(), sessId: $("#sessId").val()},
            success: function (response) {
                $(".leftovers_cssload-container").hide()
                $("#leftovers_select_result").append(response)
                $("#leftovers_cities").change(function () {
                    $("#leftovers_select_result table").remove()
                    $(".leftovers_cssload-container").show()
                    $.ajax({
                        type: "POST",
                        url: $("#leftovers_ajax_path").val(),
                        cache: false,
                        async: false,
                        data: {xmlId: $("#xmlId").val(), city: $(this).find("option:checked").val(), sessId: $("#sessId").val()},
                        success: function (response) {
                            $(".leftovers_cssload-container").hide()
                            let items = JSON.parse(response);
                            let result = ''
                            for (let i = 0; i < items.length; i++) {
                                let isPharmacyOpened = items[i].time.includes('-') ? isOpen(items[i].time) : '-';
                                result += "<tr><td pharmId='" + items[i].id + "'>Аптека №" + items[i].name + ", г. " + items[i].city + ", " + items[i].address + "</td><td>" + items[i].qty + "</td><td style='background-color: #" + isPharmacyOpened[1] + ";'>" + isPharmacyOpened[0] + "<br />" + isPharmacyOpened[2] + "</td><td><button class='leftovers_popup'>Резерв</button></td></tr>"
                            }
                            $("#leftovers_select_result").append('<table cellpadding="0" cellspacing="0" border="1"><thead><td>Аптека (точный адрес)</td><td>Кол-во упаковок в наличии</td><td>Время работы аптеки</td><td>Забронировать</td></thead>' + result + '</table>')
                        }
                    });
                })
            }
        });
    // })

    $("input[name=user_name").keyup(function () {
        $(this).val($(this).val().replace(/[^a-zA-Zа-яА-Я]/,""));
    })

    $("input#ORDER_PROP_3").mask("+38 (999) 999-99-99")

    $(".bx-basket-block.cart-popup-block a, a.cart-popup-a").on("click", function () {
        $(".scroller-wrapper-nav-pills").remove()
        return false
    })

    // $("span.bx_catalog_list_home.col3.bx_blue").on("click", function () {
    //     $('.bx-basket-block a, a.cart-popup-a').click()
    // })

    $('body').on('click', '.popup-window-buttons span.bx_catalog_list_home.col3.bx_blue:first-child, .popup-window-buttons span.bx_medium.bx_bt_button:first-child', function() {
        if ($('.popup-window-buttons span.bx_medium.bx_bt_button').index(this) == 0 || $('.popup-window-buttons span.bx_catalog_list_home.col3.bx_blue').index(this) == 0) {
            // $('.bx-basket-block a, a.cart-popup-a').click()
            // $('.bx-basket-block a').click()
        }
    });

    if (document.querySelector('#np_second_name') !== null && document.querySelector('#np_first_name') !== null && document.querySelector('#np_last_name') !== null) {
        if ($("#np_second_name").val().length === 0 || $("#np_first_name").val().length === 0 || $("#np_last_name").val().length === 0) {
            $(".slide").click();
        }
    }
})

/* Google map */

// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 48.379433, lng: 31.1655799},
        disableDefaultUI: 0,
        zoom: 7
    });
    var input = /** @type {!HTMLInputElement} */(
        document.getElementById('courier_address'));

    var types = document.getElementById('type-selector');
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });

    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("No details available for input: '" + place.name + "'");
            return;
        }

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
        }
        marker.setIcon(/** @type {google.maps.Icon} */({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
        }));
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }

        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        infowindow.open(map, marker);
    });

    // Sets a listener on a radio button to change the filter type on Places
    // Autocomplete.
    function setupClickListener(id, types) {
        var radioButton = document.getElementById(id);
        radioButton.addEventListener('click', function() {
            autocomplete.setTypes(types);
        });
    }
}

/* end Google map */

function deleteProductFromCartById(ID) {
    $.ajax({
        type: "POST",
        url: "/bitrix/templates/eshop_adapt_MC/components/bitrix/catalog/.default/ajax/add2basket_ajax.php",
        data: {
            PRODUCT_ID: ID,
            action: "deleteProdFromCart",
        },
        success: function(msg) {
            $('body').removeAttr('doublefunction')
            let resp = JSON.parse(msg)
            if (resp.code == 1) {
                BX.onCustomEvent('OnBasketChange');
                $("table#basket_items tr[id=" + ID + "]").fadeOut('fast')
                $("table.bx_ordercart_order_sum td#allSum_FORMATED").html(resp.summFormatted)
                if (resp.summ == 0) {
                    $("#cart-popup-form").remove()
                }
            }
        }
    });
    gtag('event', 'remove_from_cart', {
        "items": [
            {
                "id": ID,
                "name": "remove from cart",
                "list_name": "Search Results",
                "brand": "Google",
                "category": "Google",
                "list_position": 1,
                "quantity": 1,
                "price": '0.0'
            }
        ]
    });
}

function removeAttrDoublefnc() {
    $('body').removeAttr('doublefunction')
    $("div#cart-popup-form").hide()
    return false
}

function deleteProductsFromCart() {
    $("body").attr("doublefunction", true)
    let deleteBtn = $("#cart-popup-form .total-cart-delete-items")
    let ids = new Array()
    $("#cart-popup-form input[id^=chkb_]").on("click", function () {
        let thisEl = $(this).prop('checked')
        let thisParent = $(this).data('set-parent')

        $(".set-products-delete-btn").on('click', function () {
            $(this).css({
                opacity: 0
            })
        })

        let attr = $(this).attr('data-set-parent');

        if (typeof attr !== typeof undefined && attr !== false) {
            $(".set-products-delete-btn").animate({
                opacity: !thisEl ? 0 : 1,
                marginTop: !thisEl ? '0px' : '30px'
            }, 300)

            $("#cart-popup-form input[id^=chkb_]").filter((idx, el) => {
                if ($(el).data('set-chld') == thisParent) {

                    $(el).prop('checked', thisEl)
                }
            })
        }

        ids = []
        let checked = $("#cart-popup-form input[id^=chkb_]:checked")
        checked.each(function () {
            ids.push($(this).attr('data-id'))
        })
        if (checked.length) {
            deleteBtn.fadeIn()
        } else {
            deleteBtn.fadeOut('fast')
        }
    })

    deleteBtn.on("click", function () {
        if ($(this).is(":visible")) {
            $.ajax({
                type: "POST",
                url: "/bitrix/templates/eshop_adapt_MC/components/bitrix/catalog/.default/ajax/add2basket_ajax.php",
                data: {
                    PRODUCT_ID: ids,
                    action: "deleteProdFromCart",
                },
                success: function(msg) {
                    $(".set-products-delete-btn").hide()
                    $('body').removeAttr('doublefunction')
                    let resp = JSON.parse(msg)
                    if (resp.code == 1) {
                        deleteBtn.hide()
                        BX.onCustomEvent('OnBasketChange');
                        for (let i = 0; i < ids.length; i ++)
                            $("table#basket_items tr[id=" + ids[i] + "]").remove()
                        $("table.bx_ordercart_order_sum td#allSum_FORMATED").html(resp.summFormatted)
                        if (resp.summ == 0) {
                            $("div#cart-popup-form").remove()
                        }
                    }
                }
            });
        }
        return false
    })
}
var list = $('.root-list');
if(list.length > 4) {
    function animateList(direction) {
        if (direction === 'down') {
            $('.root-list.active')
                .find('li:first')
                .before($('li:last', '.root-list.active'))
                .end()
                .scrollTop(40)
                .stop()
                .animate({scrollTop: 0}, 100, 'swing');
        } else {
            $('.root-list.active')
                .animate({scrollTop: 40}, 100, 'swing', function () {
                    $(this)
                        .find('li:last')
                        .after($('li:first', '.root-list.active'));
                });
        }
    }
    $('.next-prev').on('click', function () {
        $('.root-list').removeClass('active');
        $(this).parents().prev('ul').addClass('active');
        var direction = $(this).attr('id');
        animateList(direction);
    });
}
var elemHeight = $('.root-item2').height();
if(elemHeight < 400) {
    $('.root-item2').css('overflow', 'auto');
}
