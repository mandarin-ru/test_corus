<?php
namespace Custom\Entity;

use \Bitrix\Main;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\Web\Uri;

class EntityList extends \CBitrixComponent
{


    /**
     * @var array
     */
    protected $errorsFatal = array();

    public function executeComponent(){

        try {


            $this->checkRequiredModules();
            $this->processParam();

            if(!empty($_REQUEST['updateEntity'])){
                $arUpdate = $this->processData();
                $this->updateEntity(intval($this->arParams['ELEMENT_ID']), $arUpdate);
            }elseif(!empty($_REQUEST['deleteEntity'])){
                $this->deleteEntity(intval($this->arParams['ELEMENT_ID']));
            }
            $this->arResult['FIELDS'] = $this->getFields();
            $this->arResult['ITEMS'] = $this->getItems();
            $this->arResult['USERS'] =  $this->getUserInfo();

        } catch (\Exception $e) {
            $this->addFatalError($e->getCode(), $e->getMessage());
        }

        $this->includeComponentTemplate();

        return parent::executeComponent();
    }

    private function getFields(){
        $arFields = array();
        $rsFields = EntityTable::getEntity()->getFields();
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
        $rsElement = EntityTable::getList(
            array(
                'order' => array('id' => 'asc'),
                'filter' => array('ID' => $this->arParams['ELEMENT_ID'])
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


    private function processParam(){

    }

    private function updateEntity($id, $arUpdate){
        if ($id > 0){
            $result = EntityTable::update($id, $arUpdate);
            if($result->isSuccess()) {
                $_SESSION['SUCCESS_UPDATE'] = 'Y';
                $request = Application::getInstance()->getContext()->getRequest();
                $uriString = $request->getRequestUri();
                LocalRedirect($uriString);
            }else{

            }
        }

    }

    private function deleteEntity($id){
        if ($id > 0){
            $_SESSION['SUCCESS_DELETE'] = 'Y';
            $result = EntityTable::delete($id);
            if($result->isSuccess())
                LocalRedirect($this->arParams['SEF_FOLDER']);
            else{

            }
        }
    }
    private function processData(){
        $arUpdate = array();
        foreach ($_REQUEST['ENTITY_DATA'] as $key => $arEntity){
            $arUpdate[$key] = htmlspecialchars($arEntity);
        }
        return $arUpdate;
    }


    private function getUserInfo(){

        $rsUsers = \Bitrix\Main\UserTable::getList(
            array(
                'select' => array(
                    'ID',
                    'NAME',
                    'LAST_NAME',
                    'SECOND_NAME'
                ),

                'order' => array('LAST_NAME' => 'asc','NAME' => 'asc')

            )
        );
        while ($arRes = $rsUsers->fetch()) {
         $arUsers[] = $arRes;
        }
        foreach ($this->arResult['ITEMS'] as $key => $arItem){

            if (!empty($arUserId)){
                $arUsers = Bitrix\Main\UserTable::getList(
                    array(
                        'select' => array(
                            'USER_ID',
                            'NAME',
                            'LAST_NAME',
                            'SECOND_NAME'
                        ),

                        'filter' => array(
                            'ID' => $arUserId
                        )

                    )
                )->fetchAll();
            }
            return $arUsers;
        }
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
