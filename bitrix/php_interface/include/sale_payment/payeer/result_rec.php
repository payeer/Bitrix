<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

include(GetLangFileName(dirname(__FILE__)."/", "/lang.php"));

if (isset($_POST["m_operation_id"]) && isset($_POST["m_sign"]))
{
	if (!($arOrder = CSaleOrder::GetByID(intval($_POST["m_orderid"]))))
	{
		echo $arOrder["ID"]."|error";
		exit;
	}

	CSalePaySystemAction::InitParamArrays($arOrder, $arOrder["ID"]);

	$m_id = CSalePaySystemAction::GetParamValue("MERCHANT_ID");
	$m_key = CSalePaySystemAction::GetParamValue("SECRET_KEY");

	$arHash = array( 
		$_POST['m_operation_id'],
		$_POST['m_operation_ps'],
		$_POST['m_operation_date'],
		$_POST['m_operation_pay_date'],
		$_POST['m_shop'],
		$_POST['m_orderid'],
		$_POST['m_amount'],
		$_POST['m_curr'],
		$_POST['m_desc'],
		$_POST['m_status'],
		$m_key
	);

	$sign_hash = strtoupper(hash('sha256', implode(":", $arHash)));

	$log_text = 
		"--------------------------------------------------------\n".
		"operation id		".$_POST["m_operation_id"]."\n".
		"operation ps		".$_POST["m_operation_ps"]."\n".
		"operation date		".$_POST["m_operation_date"]."\n".
		"operation pay date	".$_POST["m_operation_pay_date"]."\n".
		"shop				".$_POST["m_shop"]."\n".
		"order id			".$_POST["m_orderid"]."\n".
		"amount				".$_POST["m_amount"]."\n".
		"currency			".$_POST["m_curr"]."\n".
		"description		".base64_decode($_POST["m_desc"])."\n".
		"status				".$_POST["m_status"]."\n".
		"sign				".$_POST["m_sign"]."\n\n";
		
	if (CSalePaySystemAction::GetParamValue("PAYEER_LOG") != '')
	{	
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . CSalePaySystemAction::GetParamValue("PAYEER_LOG"), $log_text, FILE_APPEND);
	}

	// проверка принадлежности ip списку доверенных ip
	$list_ip_str = str_replace(' ', '', CSalePaySystemAction::GetParamValue("IPFILTER"));

	if (!empty($list_ip_str)) 
	{
		$list_ip = explode(',', $list_ip_str);
		$this_ip = $_SERVER['REMOTE_ADDR'];
		$this_ip_field = explode('.', $this_ip);
		$list_ip_field = array();
		$i = 0;
		$valid_ip = FALSE;
		foreach ($list_ip as $ip)
		{
			$ip_field[$i] = explode('.', $ip);
			if ((($this_ip_field[0] ==  $ip_field[$i][0]) or ($ip_field[$i][0] == '*')) and
				(($this_ip_field[1] ==  $ip_field[$i][1]) or ($ip_field[$i][1] == '*')) and
				(($this_ip_field[2] ==  $ip_field[$i][2]) or ($ip_field[$i][2] == '*')) and
				(($this_ip_field[3] ==  $ip_field[$i][3]) or ($ip_field[$i][3] == '*')))
				{
					$valid_ip = TRUE;
					break;
				}
			$i++;
		}
	}
	else
	{
		$valid_ip = TRUE;
	}

	if ($_POST["m_sign"] == $sign_hash && $_POST['m_status'] == "success" && $valid_ip)
	{
		if ($arOrder["PAYED"]=="N")
		{
			$arFields = array(
				"PS_STATUS" => "Y",
				"PS_STATUS_CODE" => "-",
				"PS_STATUS_DESCRIPTION" => $_POST['m_operation_id'],
				"PS_STATUS_MESSAGE" => $_POST['m_operation_ps'],
				"PS_SUM" => $_POST["m_amount"],
				"PS_CURRENCY" => $_POST["m_curr"],
				"PS_RESPONSE_DATE" => date(CDatabase::DateFormatToPHP(CLang::GetDateFormat("FULL", LANG))),
				"USER_ID" => $arOrder["USER_ID"],
			);
						
			if (CSaleOrder::Update($arOrder["ID"], $arFields))
			{
				if ($arOrder["PRICE"] == $_POST["m_amount"] && $arOrder["CURRENCY"] == $_POST["m_curr"])
				{
					CSaleOrder::PayOrder($arOrder["ID"], "Y", false);
					CSaleOrder::StatusOrder($arOrder["ID"], "F");
					exit($arOrder["ID"]."|success");
				}
			}
		}
		else
		{
			exit($arOrder["ID"] . "|error");
		}
	}

	$to = CSalePaySystemAction::GetParamValue("EMAILERR");
	$subject = GetMessage("EMAIL_SUBJECT");
	$message = GetMessage("EMAIL_BODY1");

	if ($_POST["m_sign"] != $sign_hash)
	{
		$message .= GetMessage("EMAIL_BODY5");
	}

	if ($_POST['m_status'] != "success")
	{
		$message .= GetMessage("EMAIL_BODY6");
	}

	if (!$valid_ip)
	{
		$message .= GetMessage("EMAIL_BODY2");
		$message .= GetMessage("EMAIL_BODY3") . CSalePaySystemAction::GetParamValue("IPFILTER") . "\n";
		$message .= GetMessage("EMAIL_BODY4") . $_SERVER['REMOTE_ADDR'] . "\n";
	}

	$message .= "\n" . $log_text;
	$headers = "From: no-reply@" . $_SERVER['HTTP_SERVER'] . "\r\nContent-type: text/plain; charset=utf-8 \r\n";
	mail($to, $subject, $message, $headers);

	echo $arOrder["ID"] . "|error";
}
?>