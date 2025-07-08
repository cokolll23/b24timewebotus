<?php

namespace EventsHandlers;

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Diag\Debug;
use Bitrix\Crm\Entity\Deal;
use \Bitrix\Crm\Service\Container;
use Bitrix\Crm;

class OnAfterIBlockElementUpdateHandler
{


    /**
     * @param   &$arFields
     * @return void
     */
    public static function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        $intElementID = $arFields['ID'];


        if ($intElementID) {
            Loader::includeModule('crm');

            $dealId = (int)$arFields["PROPERTY_VALUES"][70][$intElementID . ":70"]["VALUE"];
            $strDealSumma = $OPPORTUNITY = explode('|', $arFields["PROPERTY_VALUES"][71][$intElementID . ":71"]["VALUE"])[0];// Сумма

            if ($arFields["PROPERTY_VALUES"][72] != null) {
                $ASSIGNED_BY_ID = $arFields["PROPERTY_VALUES"][72];
            }


            $dealFactory = Container::getInstance()->getFactory(\CCrmOwnerType::Deal);
            $newDealItem = $dealFactory->getItem($dealId);

            if (is_array($arFields["PROPERTY_VALUES"][71][$intElementID . ":71"]) && $OPPORTUNITY != null) { // Сумма сделки
                //$newDealItem->set('OPPORTUNITY', $OPPORTUNITY);
                $newDealItem->setOpportunity($OPPORTUNITY);
            }


            if ($ASSIGNED_BY_ID) {
                $newDealItem->set("ASSIGNED_BY_ID", $ASSIGNED_BY_ID);// ответственный
            }
            $newDealItem->save();
        }

    }
}