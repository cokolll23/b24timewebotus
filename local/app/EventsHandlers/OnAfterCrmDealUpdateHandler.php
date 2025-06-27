<?php

namespace EventsHandlers;

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Diag\Debug;
use Bitrix\Iblock\ElementTable;

class OnAfterCrmDealUpdateHandler
{
    public static function OnAfterCrmDealUpdateHandler($arFields)
    {
        Loader::includeModule('iblock');

        Debug::dumpToFile($arFields, '$arFields', 'arFields.log');

        $dealId = (string)$arFields ["ID"];
        $dealASSIGNEDId = $arFields["ASSIGNED_BY_ID"]; // Ответственный в сделке и CRM
        $dealSumm = (string)$arFields["OPPORTUNITY"]; // Сумма в сделке и CRM

        OnAfterCrmDealUpdateHandler::updateIblockElById($dealId, $dealASSIGNEDId, $dealSumm);

    }

    public static function updateIblockElById($dealId, $dealASSIGNEDId, $dealSumm)
    {
        $elId = OnAfterCrmDealUpdateHandler::getIblockElId(16, $dealId);

        if ($elId) {
            $el = new \CIBlockElement;
            $PROP = array();
            $PROP[70] = $dealId; // Сделка в иб
            $PROP[71] = $dealSumm;// Сумма
            $PROP[72] = $dealASSIGNEDId;// Ответственный

            $arLoadProductArray = array(
                //"MODIFIED_BY" => $USER->GetID(), // элемент изменен текущим пользователем
                "IBLOCK_SECTION" => false,          // элемент лежит в корне раздела
                "PROPERTY_VALUES" => $PROP,
                "NAME" => "Элемент",
                "ACTIVE" => "Y",            // активен

            );
            $res = $el->Update($elId, $arLoadProductArray);


        } else {
            echo "Элемент не найден.";
        }

    }

    public static function getIblockElId($iblockId, $propertyValue)
    {
        $arFilter = array(
            "IBLOCK_ID" => $iblockId,
            "PROPERTY_CAV_DEALS" => $propertyValue
        );
        $res = \CIBlockElement::GetList(
            array("SORT" => "ASC"),
            $arFilter,
            false, false, ['IBLOCK_ID', 'ID']
        );
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
        }
        return $arFields['ID'];
    }
}






