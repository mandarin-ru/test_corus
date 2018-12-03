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
        <?if ($_SESSION['SUCCESS_UPDATE']){
            unset($_SESSION['SUCCESS_UPDATE']);
            ?><b>Элемент успешно изменен</b><br/><?
        }?>
        <form action="" method="post">
            <?foreach ($arResult['FIELDS'] as $arFields){?>

                <span><?=$arFields['TITLE']?>:</span>
                <?if ($arFields['NAME'] == 'USER_ID'){?>
                    <select name="ENTITY_DATA[<?=$arFields['NAME']?>]" id="">
                        <?foreach ($arResult['USERS'] as $arUser){?>
                            <option
                                <?if ($arResult['ITEMS'][$arFields['NAME']] == $arUser['ID'])
                                    echo 'selected';?>
                                value="<?=$arUser['ID']?>">
                                <?=$arUser['LAST_NAME']?> <?=$arUser['NAME']?> <?=$arUser['SECOND_NAME']?>
                            </option>
                        <?}?>
                    </select>
                <?}else{?>
                    <input type="text" name="ENTITY_DATA[<?=$arFields['NAME']?>]"
                                        placeholder="<?=$arFields['TITLE']?>"
                           value="<?=$arResult['ITEMS'][$arFields['NAME']]?>"
                    >
                <?}?>
                <br/>
            <?}?>
            <input type="submit" value="Изменить" name="updateEntity">
            <input type="submit" value="Удалить" name="deleteEntity">

        </form>
    <?}?>
<?
