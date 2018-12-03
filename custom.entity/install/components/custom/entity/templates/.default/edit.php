<?php

$APPLICATION->IncludeComponent(
    "custom:entity.edit",
    "",
    Array(
        'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
        'SEF_URL_TEMPLATES' => $arParams['SEF_URL_TEMPLATES'],
        'SEF_FOLDER' => $arParams['SEF_FOLDER']
    )
);
