<?php
namespace Custom\Entity;

use \Bitrix\Main;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
class EntityList extends \CBitrixComponent
{


    /**
     * @var array
     */
    protected $errorsFatal = array();

    public function executeComponent(){

        try {

            $this->checkRequiredModules();
            $this->arResult['NAV'] = $this->pagenParam();
            $this->processParams();
            $this->arResult['FIELDS'] = $this->getFields();
            $this->arResult['ITEMS'] = $this->getItems();
            $this->arResult['USERS'] = $this->arUserData();
            $this->arResult['ITEMS'] =  $this->processData();
        } catch (\Exception $e) {
            $this->addFatalError($e->getCode(), $e->getMessage());
        }

        $this->includeComponentTemplate();

        return parent::executeComponent();
    }

    private function processParams(){

        $this->arParams['SORT'] = isset($this->arParams['SORT']) ? $this->arParams['SORT'] : 'ID';
        $this->arParams['SORT_ORDER'] = isset($this->arParams['SORT_ORDER']) ? $this->arParams['SORT_ORDER'] : 'ASC';
        $this->arParams['PAGE_COUNT'] = intval($this->arParams['PAGE_COUNT']) > 0 ? intval($this->arParams['PAGE_COUNT']) : 10;

    }

    private function pagenParam(){

        $grid_options = new \Bitrix\Main\Grid\Options('entity_test');
        $nav_params = $grid_options->GetNavParams();
        $cnt = EntityTable::getCount();
        $nav = new \Bitrix\Main\UI\PageNavigation('entity_test');
        $nav->allowAllRecords(true)
            ->setPageSize($nav_params['nPageSize'])
            ->initFromUri();

        $nav->setRecordCount($cnt);

        return $nav;
    }

    private function getFields(){
        $arFields = array();
        $rsFields = EntityTable::getEntity()->getFields();
        foreach ($rsFields as $arField){
            $arFields[] = array(
                'id' => $arField->getName(),
                'name' => $arField->getTitle(),
                'sort' => $arField->getName(), 'default' => true
            );
        }
        return $arFields;
    }

    private function getItems(){
        $arResult = array();
        $rsElement = EntityTable::getList(
            array(
                'order' => array($this->arParams['SORT'] => $this->arParams['SORT_ORDER']),
                'limit' => $this->arResult['NAV']->getPageSize(),
                'offset'=> $this->arResult['NAV']->getCurrentPage() * $this->arResult['NAV']->getPageSize() - $this->arResult['NAV']->getPageSize()

            )
        );
        while($arElement = $rsElement->Fetch()){
            $arResult[]  = $arElement;
        }

        return $arResult;
    }
    private function checkRequiredModules(){
        if (!Loader::includeModule('custom.entity')) {
            echo 'ошибка модуля';
            /*
            throw new Main\SystemException(
                Loc::getMessage('MODULE_NOT_INSTALLED'),
                self::ERROR_IBLOCK_MODULE_NOT_INSTALLED
            );*/
        }
    }

    private function arUserData(){
        foreach ($this->arResult['ITEMS']  as $arItem){
            if (intval($arItem['USER_ID']) > 0)
                $arUserId[] = intval($arItem['USER_ID']);
        }
        if (!empty($arUserId)){
            $rsUser = \Bitrix\Main\UserTable::getList(array(
                    'filter' => array('ID' => $arUserId),
                    'select' => array('ID', 'NAME', 'SECOND_NAME', 'LAST_NAME')
                )
            );
            while($arUser = $rsUser->Fetch()) {
                $arUsers[$arUser['ID']] = $arUser;
            }
        }
        return $arUsers;
    }

    private function processData(){
        $arResult = array();
        foreach ($this->arResult['ITEMS'] as $arItem){
            $arItem['USER_ID'] =
                $this->arResult['USERS'][$arItem['USER_ID']]['NAME'].' '.
                $this->arResult['USERS'][$arItem['USER_ID']]['LAST_NAME'].' '.
                $this->arResult['USERS'][$arItem['USER_ID']]['SECOND_NAME'].' ';

                $arResult[] = array(
                'data' => $arItem,
                'actions' => [
                    [
                        'text'    => Loc::getMessage('EDIT_LINK_TITLE'),
                        'onclick' => 'document.location.href="'.str_replace('#ELEMENT_ID#', $arItem['ID'] , $this->arParams['SEF_URL_TEMPLATES']['edit'].'"')
                    ],

                ],
            );

        }
        return $arResult;

    }

    /**
     * Добавление критичной ошибки
     *
     * @param int $code Код ошибки
     * @param string $message Сообщение
     */
    protected function addFatalError($code, $message)
    {
        $this->errorsFatal[] = array(
            'CODE' => $code,
            'MESSAGE' => $message,
        );
    }

}
