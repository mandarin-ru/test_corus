<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */
/** @global CUserTypeManager $USER_FIELD_MANAGER */
use Bitrix\Main\Localization\Loc;


$arComponentParameters = array(
    'PARAMETERS' => array(
        "SEF_MODE" => Array(
            'list' => array(
                "NAME" => Loc::getMessage('PAGE_SECTION'),
                "DEFAULT" => "index.php",

            ),
            'detail' => array(
                "NAME" => Loc::getMessage('PAGE_ELEMENT'),
                "DEFAULT" => "#ELEMENT_ID#/",
            ),
            'edit' => array(
                "NAME" => Loc::getMessage('PAGE_EDIT'),
                "DEFAULT" => "edit/#ELEMENT_ID#/",
            ),
            'add' => array(
                "NAME" => Loc::getMessage('PAGE_ADD'),
                "DEFAULT" => "add/index.php",
            ),
        ),

    ),
);
