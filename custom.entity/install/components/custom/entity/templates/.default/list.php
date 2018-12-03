<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();




$APPLICATION->IncludeComponent(
    "custom:entity.list",
    "",
    Array(
        'SEF_URL_TEMPLATES' => $arParams['SEF_URL_TEMPLATES'],
        'PAGE_COUNT' => $nav_params['nPageSize'],
        'SORT' => $_REQUEST['by'],
        'SORT_ORDER' => $_REQUEST['order'],
    )
);

