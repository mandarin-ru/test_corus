<?php
namespace Custom\Entity;

use Bitrix\Main;

use Bitrix\Main\UserTable;


class Entity extends \CBitrixComponent
{

    protected $arDefaultUrlTemplates = array();

    public function executeComponent(){

        $this->defaultTemplateUrl();
        $arUrlTemplates = \CComponentEngine::makeComponentUrlTemplates(
            $this->arDefaultUrlTemplates,
            $this->arParams['SEF_URL_TEMPLATES']
            );
        $arVariables = array();
        $page =
            \CComponentEngine::ParseComponentPath($this->arParams['SEF_FOLDER'],
                $arUrlTemplates, $arVariables);
        $this->arResult['VARIABLES'] = $arVariables;

        $this->includeComponentTemplate($page);

        //return parent::executeComponent();
    }

    private function defaultTemplateUrl(){
        $this->arDefaultUrlTemplates = array(
            "list" => "index.php",
            "detail" => "#ELEMENT_ID#/",
            "edit" => "edit/#ELEMENT_ID#/",
        );
    }


}
