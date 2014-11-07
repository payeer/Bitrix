<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$APPLICATION->IncludeComponent('bitrix:sale.order.payment.receive','',
	Array(
		'PAY_SYSTEM_ID' => '',
		'PERSON_TYPE_ID' => array('1','2')
	)
);

?>