<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("1С-Битрикс: Управление сайтом");
?>

<?$APPLICATION->IncludeComponent(
    "poyarkov:dateComponent",
    ".default",
	Array(
        'AJAX_MODE' => 'Y'
    )
);?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>