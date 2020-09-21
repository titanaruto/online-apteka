<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$ID = (int)$_GET['ORDER_ID'];

$arFilter = Array(
    "ID" => $ID,
);

$CSaleOrder = new CSaleOrder();

$dbOrder = $CSaleOrder->GetList(Array("ID" => "ASC"), $arFilter);
$arOrder = $dbOrder->GetNext();

$CSalePaySystemAction = new CSalePaySystemAction();

?>
<div class="pga-payform-container">
    <form action="<?php echo $CSalePaySystemAction->GetParamValue("START_PAYMENT_URL"); ?>" method="GET"
          name="pga_payment_form">
        <input type="hidden" name="o.order_id" value="<?php echo $ID; ?>">
        <input type="hidden" name="o.pay_system_id" value="<?php echo $arOrder["PAY_SYSTEM_ID"]; ?>">
        <input type="hidden" name="o.person_type_id" value="<?php echo $arOrder["PERSON_TYPE_ID"]; ?>">
        <input type="hidden" name="o.plugin" value="pgamerchant_module_bitrix_1.0.3">
        <input type="hidden" name="merch_id" value="<?php echo $CSalePaySystemAction->GetParamValue("SHOP_PCID") ?>">
        <input type="hidden" name="back_url_s" value="<?php echo $CSalePaySystemAction->GetParamValue("SUCCESS_URL") ?>">
        <input type="hidden" name="back_url_f" value="<?php echo $CSalePaySystemAction->GetParamValue("DECLINE_URL") ?>">
        <?php if($CSalePaySystemAction->GetParamValue("LANG_CODE")!=""){ ?><input type="hidden" name="lang" value="<?php echo $CSalePaySystemAction->GetParamValue("LANG_CODE") ?>"><?php }  ?>
        <?php if($CSalePaySystemAction->GetParamValue("PAGE_ID")!=""){ ?><input type="hidden" name="page_id" value="<?php echo $CSalePaySystemAction->GetParamValue("PAGE_ID") ?>"><?php }  ?>
        <div style="padding: 20px 0">
            <input type="submit" value="Перейти к оплате" class="" name="sbmt">
        </div>
    </form>
</div>