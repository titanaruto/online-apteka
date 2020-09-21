<?php
CJSCore::Init( 'jquery' );
?>
<script>
    $(document).ready(function () {
        function changeStatus(elem, status, editText = '') {
            switch (+status) {
                case 1:
                    elem.find('a').addClass('moder_red');
                    elem.addClass('moder_red');
                    elem.find('.moder-view').removeClass('moder-view').addClass('moder-hidden');
                    break;
                case 2:
                    elem.find('a').removeClass('moder_red').removeClass('moder_white');
                    elem.removeClass('moder_red');
                    elem.find('.moder-hidden').removeClass('moder-hidden').addClass('moder-view');
                    break;
                case 3:
                    elem.find('td').eq(2).find('a').text(editText);
                    break;
                case 4:
                    elem.remove();
                    break;
            }
        }

        $(".moder-icons div").on('click', function () {
            let thisEl = $(this);
            let thisClass = $(this).attr('class');
            let elem = $(".product_comments_content tr[id=" + thisEl.parent().data('id') + "]");
            let editText = '';
            let sure = true;

            if (thisClass === 'moder-edit')
                editText = prompt('Редактирование комментария', elem.find('td').eq(2).find('a').text());
            else if (thisClass === 'moder-trash')
                sure = confirm('Вы уверены?');

            if (!sure)
                return false;

            $.ajax({
                type: "POST",
                url: "/bitrix/templates/eshop_adapt_MC/components/medservice/MCsale.order.ajax/orderMC/delivery/check.php",
                data: {
                    action: thisClass,
                    text: editText,
                    id: thisEl.parent().data('id')
                },
                success: function(resp) {
                    changeStatus(elem, resp, editText);
                }
            });
        })
    })
</script>