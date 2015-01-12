<?php
global $MESS;
$MESS["MERCHANT_URL"] = "URL Мерчанта";
$MESS["MERCHANT_URL_DESCR"] = 'URL для оплаты заказа';
$MESS["MERCHANT_ID"] = "Идентификатор магазина";
$MESS["MERCHANT_ID_DESCR"] = 'Идентификатор магазина, зарегистрированного в системе "PAYEER".<br/>Узнать его можно в <a href="http://www.payeer.com/account/">аккаунте Payeer</a>: "Аккаунт -> Мой магазин -> Изменить".';
$MESS["SECRET_KEY"] = "Секретный ключ";
$MESS["SECRET_KEY_DESCR"] = 'Секретный ключ оповещения о выполнении платежа,<br/>который используется для проверки целостности полученной информации<br/>и однозначной идентификации отправителя.<br/>Должен совпадать с секретным ключем, указанным в <a href="http://www.payeer.com/account/">аккаунте Payeer</a>: "Аккаунт -> Мой магазин -> Изменить".';
$MESS["CURRENCY"] = "Валюта магазина";
$MESS["ORDER_DESCRIPTION"] = "Описание заказа";
$MESS["ORDER_DESCRIPTION_DESCR"] = "Дополнительный комментарий, отображаемый при оплате";
$MESS["PAYEER_LOG"] = "Путь до файла для журнала оплат через Payeer (например, /payeer_orders.log)";
$MESS["PAYEER_LOG_DESCR"] = 'Если путь не указан, то журнал не записывается';
$MESS["IPFILTER"] = "IP фильтр";
$MESS["IPFILTER_DESCR"] = 'Список доверенных ip адресов, можно указать маску';
$MESS["EMAILERR"] = "Email для ошибок";
$MESS["EMAILERR_DESCR"] = 'Email для отправки ошибок оплаты';
$MESS["ORDER_ID"] = "Номер заказа";
$MESS["SHOULD_PAY"] = "Сумма к оплате";

$MESS["PAYEER_MAIN_TITLE"] = "Payeer";
$MESS["PAYEER_MAIN_DESCR"] = "Payeer® Merchant позволяет принимать платежи всеми возможными способами по всему миру!";
$MESS["PAYEER_PAYMENT_MESSAGE"] = "Оплата через платежную систему <b>Payeer</b>";
$MESS["PAYEER_ORDER_MESSAGE"] = "Cчет № ";
$MESS["PAYEER_AMOUNT"] = "Сумма заказа";
$MESS["PAYEER_AMOUNT_DESCR"] = "Сумма к оплате";
$MESS["DATE_INSERT"] = "Дата создания заказа";
$MESS["DATE_INSERT_DESCR"] = "";
$MESS["PAYEER_TO_PAY"] = "Сумма к оплате по счету:";
$MESS["PAYEER_SUBMIT"] = "Оплатить";
$MESS["PAYMENT_ERROR_IPFILTER"] = "<p>Заказ не может быть оплачен через платежную систему Payeer.<br/>Письмо отправлено в службу поддержки.</p>";
$MESS["EMAIL_SUBJECT"] = "Ошибка оплаты";
$MESS["EMAIL_BODY1"] = "Не удалось провести платёж через систему Payeer по следующим причинам:\n\n";
$MESS["EMAIL_BODY2"] = " - ip-адрес сервера не является доверенным\n";
$MESS["EMAIL_BODY3"] = "   доверенные ip: ";
$MESS["EMAIL_BODY4"] = "   ip текущего сервера: ";
$MESS["EMAIL_BODY5"] = " - Не совпадают цифровые подписи\n";
$MESS["EMAIL_BODY6"] = " - Cтатус платежа не является success\n";
?>