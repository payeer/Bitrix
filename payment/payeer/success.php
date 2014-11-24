<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
include(GetLangFileName(dirname(__FILE__)."/", "/lang.php"));

echo GetMessage("PAYEER_USER_MESSAGE2");
echo '<p><a href="/personal/order/"> ' . GetMessage("PAYEER_USER_ORDERS") . '</a></p>';

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>

