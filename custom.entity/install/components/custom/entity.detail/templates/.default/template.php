<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
    die();
}
?>

    <a href="<?=$arParams['SEF_FOLDER']?>">Список записей</a><br>
<?if (!empty($arResult['ITEMS']['ERRORS'])){?>
    <div>
        <b style="color:red"><?=$arResult['ITEMS']['ERRORS']?></b><br/>
    </div>
<?}else{?>

    <div >
        <?foreach ($arResult['FIELDS'] as $arFields){?>
            <span><?=$arFields['TITLE']?>:</span>
            <span ><?=$arResult['ITEMS'][$arFields['NAME']]?>
            </span>
            <br/>
        <?}?>


    </div>
<?}?>
<?
