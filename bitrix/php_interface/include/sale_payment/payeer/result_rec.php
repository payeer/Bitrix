<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

include(GetLangFileName(dirname(__FILE__) . "/", "/lang.php"));

if (isset($_POST["m_operation_id"]) && isset($_POST["m_sign"]))
{
	$err = false;
	$message = '';
	
	// загрузка заказа

	$arOrder = CSaleOrder::GetByID(intval($_POST['m_orderid']));
	
	if (!$arOrder)
	{
		$message .= GetMessage("EMAIL_BODY7");
		$err = true;
	}
	else
	{
		CSalePaySystemAction::InitParamArrays($arOrder, $arOrder["ID"]);

		// запись логов

		$log_text = 
		"--------------------------------------------------------\n" .
		"operation id		" . $_POST['m_operation_id'] . "\n" .
		"operation ps		" . $_POST['m_operation_ps'] . "\n" .
		"operation date		" . $_POST['m_operation_date'] . "\n" .
		"operation pay date	" . $_POST['m_operation_pay_date'] . "\n" .
		"shop				" . $_POST['m_shop'] . "\n" .
		"order id			" . $_POST['m_orderid'] . "\n" .
		"amount			" . $_POST['m_amount'] . "\n" .
		"currency			" . $_POST['m_curr'] . "\n" .
		"description		" . base64_decode($_POST['m_desc']) . "\n" .
		"status			" . $_POST['m_status'] . "\n" .
		"sign				" . $_POST['m_sign'] . "\n\n";
		
		$log_file = CSalePaySystemAction::GetParamValue("PAYEER_LOG");
		
		if (!empty($log_file))
		{
			file_put_contents($_SERVER['DOCUMENT_ROOT'] . $log_file, $log_text, FILE_APPEND);
		}
		
		// проверка цифровой подписи и ip

		$sign_hash = strtoupper(hash('sha256', implode(":", array(
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
			CSalePaySystemAction::GetParamValue("SECRET_KEY")
		))));
		
		$valid_ip = true;
		$sIP = str_replace(' ', '', CSalePaySystemAction::GetParamValue("IPFILTER"));
		
		if (!empty($sIP))
		{
			$arrIP = explode('.', $_SERVER['REMOTE_ADDR']);
			if (!preg_match('/(^|,)(' . $arrIP[0] . '|\*{1})(\.)' .
			'(' . $arrIP[1] . '|\*{1})(\.)' .
			'(' . $arrIP[2] . '|\*{1})(\.)' .
			'(' . $arrIP[3] . '|\*{1})($|,)/', $sIP))
			{
				$valid_ip = false;
			}
		}
		
		if (!$valid_ip)
		{
			$message .= GetMessage("EMAIL_BODY2") .
			GetMessage("EMAIL_BODY3") . $sIP . "\n" .
			GetMessage("EMAIL_BODY4") . $_SERVER['REMOTE_ADDR'] . "\n";
			$err = true;
		}

		if ($_POST['m_sign'] != $sign_hash)
		{
			$message .= GetMessage("EMAIL_BODY5");
			$err = true;
		}
		
		if (!$err)
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
				$order_curr = $arOrder["CURRENCY"] == 'RUR' ? 'RUB' : $arOrder["CURRENCY"];
				$order_amount = number_format($arOrder["PRICE"], 2, '.', '');
				
				// проверка суммы, валюты
			
				if ($_POST['m_amount'] != $order_amount)
				{
					$message .= GetMessage("EMAIL_BODY8");
					$err = true;
				}

				if ($_POST['m_curr'] != $order_curr)
				{
					$message .= GetMessage("EMAIL_BODY9");
					$err = true;
				}
				
				// проверка статуса
				
				if (!$err)
				{
					switch ($_POST['m_status'])
					{
						case 'success':
						
							if ($arOrder["PAYED"] == "N")
							{
								CSaleOrder::PayOrder($arOrder["ID"], "Y", false);
								CSaleOrder::StatusOrder($arOrder["ID"], "F");
							}
							
							break;
							
						default:
							$message .= GetMessage("EMAIL_BODY6");
							$err = true;
							break;
					}
				}
			}
		}
	}
	
	if ($err)
	{
		$to = CSalePaySystemAction::GetParamValue("EMAILERR");

		if (!empty($to))
		{
			$message = GetMessage("EMAIL_BODY1") . $message . "\n" . $log_text;
			$headers = "From: no-reply@" . $_SERVER['HTTP_HOST'] . "\r\n" . 
			"Content-type: text/plain; charset=utf-8 \r\n";
			mail($to, GetMessage("EMAIL_SUBJECT"), $message, $headers);
		}
		
		exit($_POST["m_orderid"] . "|error");
	}
	else
	{
		exit($_POST["m_orderid"] . "|success");
	}
}
?>