<?php

namespace Models\Autos;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

class AutosLists extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'auto_lists';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('LISTS_ENTITY_ID_FIELD'),
                ]
            ),
            new StringField(
                'MARKA',
                [
                    'validation' => function()
                    {
                        return[
                            new LengthValidator(null, 50),
                        ];
                    },
                    'title' => Loc::getMessage('LISTS_ENTITY_MARKA_FIELD'),
                ]
            ),
            new StringField(
                'MODEL',
                [
                    'validation' => function()
                    {
                        return[
                            new LengthValidator(null, 50),
                        ];
                    },
                    'title' => Loc::getMessage('LISTS_ENTITY_MODEL_FIELD'),
                ]
            ),
            new StringField(
                'YEAR_CREATED',
                [
                    'validation' => function()
                    {
                        return[
                            new LengthValidator(null, 50),
                        ];
                    },
                    'title' => Loc::getMessage('LISTS_ENTITY_YEAR_CREATED_FIELD'),
                ]
            ),
            new StringField(
                'COLOR',
                [
                    'validation' => function()
                    {
                        return[
                            new LengthValidator(null, 50),
                        ];
                    },
                    'title' => Loc::getMessage('LISTS_ENTITY_COLOR_FIELD'),
                ]
            ),
            new StringField(
                'MILEGE',
                [
                    'validation' => function()
                    {
                        return[
                            new LengthValidator(null, 50),
                        ];
                    },
                    'title' => Loc::getMessage('LISTS_ENTITY_MILEGE_FIELD'),
                ]
            ),
        ];
    }

}