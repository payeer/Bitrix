<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

include(GetLangFileName(dirname(__FILE__)."/", "/lang.php"));

$psTitle = GetMessage("PAYEER_MAIN_TITLE");
$psDescription = GetMessage("PAYEER_MAIN_DESCR");
	
$arPSCorrespondence = array(
	"MERCHANT_URL" => array(
		"NAME" => GetMessage("MERCHANT_URL"),
		"DESCR" => GetMessage("MERCHANT_URL_DESCR"),
		"VALUE" => "//payeer.com/merchant/",
		"TYPE" => ""
	),
	"MERCHANT_ID" => array(
		"NAME" => GetMessage("MERCHANT_ID"),
		"DESCR" => GetMessage("MERCHANT_ID_DESCR"),
		"VALUE" => "",
		"TYPE" => ""
	),
	"SECRET_KEY" => array(
		"NAME" => GetMessage("SECRET_KEY"),
		"DESCR" => GetMessage("SECRET_KEY_DESCR"),
		"VALUE" => "",
		"TYPE" => ""
	),
	"ORDER_DESCRIPTION" => array(
		"NAME" => GetMessage("ORDER_DESCRIPTION"),
		"DESCR" => GetMessage("ORDER_DESCRIPTION_DESC"),
		"VALUE" => "",
		"TYPE" => ""
	),
	"PAYEER_LOG" => array(
		"NAME" => GetMessage("PAYEER_LOG"),
		"DESCR" => GetMessage("PAYEER_LOG_DESCR"),
		"VALUE" => "",
		"TYPE" => ""
	),
	"IPFILTER" => array(
		"NAME" => GetMessage("IPFILTER"),
		"DESCR" => GetMessage("IPFILTER_DESCR"),
		"VALUE" => "",
		"TYPE" => ""
	),
	"EMAILERR" => array(
		"NAME" => GetMessage("EMAILERR"),
		"DESCR" => GetMessage("EMAILERR_DESCR"),
		"VALUE" => "",
		"TYPE" => ""
	),
	"ORDER_ID" => array(
		"NAME" => GetMessage("ORDER_ID"),
		"DESCR" => "",
		"VALUE" => "ID",
		"TYPE" => "ORDER"
	),
	"SHOULD_PAY" => array(
		"NAME" => GetMessage("SHOULD_PAY"),
		"DESCR" => "",
		"VALUE" => "SHOULD_PAY",
		"TYPE" => "ORDER"
	),
	"CURRENCY" => array(
		"NAME" => GetMessage("CURRENCY"),
		"DESCR" => GetMessage("CURRENCY_DESCR"),
		"VALUE" => "CURRENCY",
		"TYPE" => "ORDER"
	),
);
?>