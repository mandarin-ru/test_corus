<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 23.11.18
 * Time: 1:12
 */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use \Bitrix\Main\Loader;
use Bitrix\Main\EventManager;
use \Bitrix\Main\Entity\Base;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;


Loc::loadMessages(__FILE__);
Class custom_entity extends CModule
{
    public function __construct(){

        if(file_exists(__DIR__."/version.php")){
            $arModuleVersion = array();
            include_once(__DIR__."/version.php");
            $this->MODULE_ID 		   = str_replace("_", ".", get_class($this));
            $this->MODULE_VERSION 	   = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
            $this->MODULE_NAME 		   = Loc::getMessage("ENTIY_NAME");
            $this->MODULE_DESCRIPTION  = Loc::getMessage("ENTITY_DESCRIPTION");
        }

    }


    public function DoInstall(){

        global $APPLICATION;
        if(CheckVersion(ModuleManager::getVersion('main'), '14.00.00')){
            ModuleManager::registerModule($this->MODULE_ID);
            $this->InstallFiles();
            $this->InstallDB();
            $this->InstallEvents();

        }else{
            $APPLICATION->ThrowException(Loc::getMessage("ENTITY_INSTALL_ERROR_D7"));
        }


    }




    public function InstallDB(){

        Loader::includeModule($this->MODULE_ID);

        /*if(!Application::getConnection('\Custom\Entity\EntityTable::getConnectionName()')->isTableExists(Base::getInstance('\Custom\Entity\EntityTable')->getDBTableName())
        ){
            Base::getInstance('\Custom\Entity\EntityTable')->createDbTable();
        }*/
        Base::getInstance('\Custom\Entity\EntityTable')->createDbTable();


    }

    public function InstallEvents(){


    }

    public function DoUninstall(){

        $this->UnInstallFiles();
        $this->UnInstallDB();
        $this->UnInstallEvents();
        ModuleManager::unRegisterModule($this->MODULE_ID);
        return true;
    }

    public function InstallFiles($arParams = array()){
        $path= __DIR__."/components";
        if(\Bitrix\Main\IO\Directory::isDirectoryExists($path))
            \CopyDirFiles($path, $_SERVER["DOCUMENT_ROOT"]."/local/components", true, true);
        else
            throw new \Bitrix\Main\IO\InvalidPathException($path);



        return true;
    }

    public function UnInstallFiles()
    {
        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . '/local/components/custom/');
        return true;
    }


    public function UnInstallDB(){

        Loader::includeModule($this->MODULE_ID);

        var_dump($this->MODULE_ID);
        Application::getConnection(\Custom\Entity\EntityTable::getConnectionName())->
        queryExecute('drop table if exists '.Base::getInstance('\Custom\Entity\EntityTable')->getDBTableName());
        return true;
    }

    public function UnInstallEvents(){

        return false;
    }


}
?>

