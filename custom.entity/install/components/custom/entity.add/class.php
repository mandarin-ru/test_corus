<?php
namespace Custom\Entity;

use \Bitrix\Main;
use \Bitrix\Main\UserTable;
use \Bitrix\Main\Loader;

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
            $this->arResult['USERS'] =  $this->getUserInfo();
            $this->arResult['ERROR'] = $this->addEntity();

        } catch (\Exception $e) {
            $this->addFatalError($e->getCode(), $e->getMessage());
        }

        $this->arResult['FIELDS'] = $this->arFields;
        $this->includeComponentTemplate();

        return parent::executeComponent();
    }

    private function getFields(){
        $rsFields = EntityTable::getEntity()->getFields();
        foreach ($rsFields as $arFields){
            if ($arFields->getName() == 'ID')
                continue;
            $this->arFields[] = array(
                'NAME' => $arFields->getName(),
                'TITLE' => $arFields->getTitle()
            );
        }
        return true;
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

      return $arUsers;
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
