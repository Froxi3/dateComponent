<?php
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
?>
<form action="<?=POST_FORM_ACTION_URI?>" method="POST" id="myForm">
<?=bitrix_sessid_post()?>
    <div>
        <input type="number" placeholder="Введите число" min="1" name="number">
    </div>
    <button type="submit">Посчитать</button>
    <?if($arResult["finalDate"]):?>
        <div>
            <span><?=$arResult["finalDate"]?></span>
        </div>
    <?endif;?>
</form>