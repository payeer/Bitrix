<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

include(GetLangFileName(dirname(__FILE__)."/", "/lang.php"));

$m_url = CSalePaySystemAction::GetParamValue("MERCHANT_URL");
$m_shop = CSalePaySystemAction::GetParamValue("MERCHANT_ID");
$m_key = CSalePaySystemAction::GetParamValue("SECRET_KEY");

$m_orderid = CSalePaySystemAction::GetParamValue("ORDER_ID");
$m_amount = number_format(CSalePaySystemAction::GetParamValue("SHOULD_PAY"), 2, '.', '');
$m_curr = CSalePaySystemAction::GetParamValue("CURRENCY");
$m_desc =  base64_encode(CSalePaySystemAction::GetParamValue("ORDER_DESCRIPTION"));

$arHash = array(
	$m_shop,
	$m_orderid,
	$m_amount,
	$m_curr,
	$m_desc,
	$m_key
);
$sign = strtoupper(hash('sha256', implode(":", $arHash)));
?>

<form action="<?php echo $m_url; ?>" method="get">
	<font class="tablebodytext">
		<?php echo GetMessage("PAYEER_PAYMENT_MESSAGE"); ?><br>
		<?php echo GetMessage("PAYEER_ORDER_MESSAGE"); ?> <?php echo $m_orderid; ?><br>
		<?php echo GetMessage("PAYEER_TO_PAY"); ?> <b><?php echo $m_amount; ?></b>
		<p>
			<input type="hidden" name="m_shop" value="<?php echo $m_shop; ?>">
			<input type="hidden" name="m_orderid" value="<?php echo $m_orderid; ?>">
			<input type="hidden" name="m_amount" value="<?php echo $m_amount; ?>">
			<input type="hidden" name="m_curr" value="<?php echo $m_curr; ?>">
			<input type="hidden" name="m_desc" value="<?php echo $m_desc; ?>">
			<input type="hidden" name="m_sign" value="<?php echo $sign; ?>">
			<input type="submit" name="m_process" value="<?php echo GetMessage("PAYEER_SUBMIT");?>">
		</p>
	</font>
</form>