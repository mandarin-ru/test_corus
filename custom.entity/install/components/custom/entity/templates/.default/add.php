<?php

$APPLICATION->IncludeComponent(
    "custom:entity.add",
    "",
    Array(
        'SEF_URL_TEMPLATES' => $arParams['SEF_URL_TEMPLATES'],
        'SEF_FOLDER' => $arParams['SEF_FOLDER']
    )
);