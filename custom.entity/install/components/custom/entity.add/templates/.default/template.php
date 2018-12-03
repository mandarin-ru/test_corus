<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
    die();
}
?>

    <a href="<?=$arParams['SEF_FOLDER']?>">Список записей</a><br/>
    <?if (!empty($arResult['ERROR'])){?>
        <div>
            <b style="color:red">Ошибка добавления</b><br/>
            <?foreach ($arResult['ERROR'] as $arError){?>
                <span style="color:red"><?=$arError?></span><br/>
            <?}?>
        </div>
    <?}?>
    <form action="" method="post">
        <?foreach ($arResult['FIELDS'] as $arFields){?>

            <?if ($arFields['NAME'] == 'USER_ID'){?>
                <select name="ENTITY_DATA[<?=$arFields['NAME']?>]" id="">
                    <?foreach ($arResult['USERS'] as $arUser){?>
                        <option value="<?=$arUser['ID']?>">

                            <?=$arUser['LAST_NAME']?> <?=$arUser['NAME']?> <?=$arUser['SECOND_NAME']?>
                        </option>
                    <?}?>
                </select>
            <?}else{?>

                <input type="text" name="ENTITY_DATA[<?=$arFields['NAME']?>]"
                       placeholder="<?=$arFields['TITLE']?>"
                       value="<?=$_REQUEST['ENTITY_DATA'][$arFields['NAME']]?>"
                >
            <?}?>
            <br/>
        <?}?>
        <input type="submit" value="Добавить">
    </form>
<?
