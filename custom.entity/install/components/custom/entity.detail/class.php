<?php
namespace Custom\Entity;

use \Bitrix\Main;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\Web\Uri;


class EntityAdd extends \CBitrixComponent
{


    /**
     * @var array
     */
    protected $errorsFatal = array();
    protected $arFields = array();

    public function executeComponent(){

        try {

            $this->checkRequiredModules();
            $this->getFields();

            $this->arResult['FIELDS'] = $this->getFields();
            $this->arResult['ITEMS'] = $this->getItems();


        } catch (\Exception $e) {
            $this->addFatalError($e->getCode(), $e->getMessage());
        }



        $this->includeComponentTemplate();

        return parent::executeComponent();
    }

    private function getFields(){
        $arFields = array();
        $rsFields = \Custom\Entity\EntityTable::getEntity()->getFields();
        foreach ($rsFields as $arField){
            if ($arField->getName() == 'ID')
                continue;
            $arFields[] = array(
                'NAME' => $arField->getName(),
                'TITLE' => $arField->getTitle()
            );
        }
        return $arFields;
    }
    private function getItems(){
        $arResult = array();
        $id = intval($this->arParams['ELEMENT_ID']);
        if( $id <= 0){
            $arResult['ERRORS'] = Loc::getMessage('ELEMENT_NOT_FOUND');
            return $arResult;
        }
        $rsElement = EntityTable::getList(
            array(
                'order' => array('id' => 'asc'),
                'filter' => array('ID' => $id)
            )
        );
        if($arElement = $rsElement->Fetch()){
            $arResult = $arElement;
        }else{
            $arResult['ERRORS'] = Loc::getMessage('ELEMENT_NOT_FOUND');
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
    private function processForm(){
        return true;
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


    private function addEntity(){

        if(!empty($_POST['ENTITY_DATA'])){
            foreach ($_POST['ENTITY_DATA'] as $key => $arFields){
                $arAddValue[$key] = $arFields;
            }
            $result = EntityTable::add($arAddValue);
            if (empty($result->getErrorMessages())){
                LocalRedirect('index.php');
            }else{
                return $result->getErrorMessages();
            }
        }
    }

}
