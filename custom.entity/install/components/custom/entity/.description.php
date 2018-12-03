<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = array(
    "NAME" => Loc::getMessage("ENTITY_NAME"),
    "DESCRIPTION" => Loc::getMessage("ENTITY_NAME_DESCRIPTION"),
    "PATH" => array(
        "ID" => "entity",
        "CHILD" => array(
            "ID" => "entity_list",
            "NAME" => Loc::getMessage("ENTITY_NAME_SHORT"),
            "SORT" => 10,

        ),
    ),
);
?>