<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 23.11.18
 * Time: 17:22
 */


namespace Custom\Entity;
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;

class EntityTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'entity_test';
    }

    public static function getUfId()
    {
        return 'ENTITY_TEST';
    }

    public static function getConnectionName()
    {
        return 'default';
    }

    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            new Entity\StringField('NAME',
                array(
                    'required' => true,
                    'title' => Loc::getMessage('NAME_FIELD')
                )
            ),
            new Entity\TextField('DESCRIPTION',
                array(
                    'required' => true,
                    'title' => Loc::getMessage('DESCRIPTION_FIELD')
                )
            ),
            new Entity\IntegerField('USER_ID',
                 array(
                     'required' => true,
                     'title' => Loc::getMessage('USER_FIELD')
                     )
            ),
        );
    }

}