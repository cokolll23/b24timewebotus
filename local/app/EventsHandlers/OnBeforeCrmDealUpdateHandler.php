<?php

namespace EventsHandlers;

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Diag\Debug;
use Bitrix\Iblock\ElementTable;
use \Bitrix\Crm\Service\Container;

class OnBeforeCrmDealUpdateHandler
{
    public static function OnBeforeCrmDealUpdateHandler(&$arFields)
    {
        // get измененные значения
        $dealId = $arFields['ID'];
        $BLOCK_ID = 16;

        Debug::dumpToFile($arFields, '$arFields OnBeforeCrmDealUpdateHandler ' . date('d-m-Y; H:i:s'));

        if ($arFields["OPPORTUNITY"] && $arFields["OPPORTUNITY"] != '') {
            $strDealSumma = $arFields["OPPORTUNITY"];
        } else {
            $factory = Container::getInstance()->getFactory(\CCrmOwnerType::Deal);
            $getCurrDealRes['dealId'] = $item = $factory->getItem((int)$dealId);

            $strDealSumma = $item->get("OPPORTUNITY");


        }
        if ($arFields["ASSIGNED_BY_ID"] && $arFields["ASSIGNED_BY_ID"] != '') {
            $strDealOtvetctvenniy = $arFields["ASSIGNED_BY_ID"];
        }

        $arFilter = array(
            "IBLOCK_ID" => $BLOCK_ID,
            "PROPERTY_DEAL" => $dealId,
            //"PROPERTY_SD" => $dealId,
        );
// получить id элемента заказа по свойству Сделка 74 -SD , cust70 DEAL

        $res = \CIBlockElement::GetList(
            array("SORT" => "ASC"),
            $arFilter,
            false, false, ['IBLOCK_ID', 'ID']
        );

        while ($ob = $res->GetNextElement()) {
            $arElFields = $ob->GetFields();
        }

        $iElId = (int)$arElFields['ID'];

        $sqlQuery = ' UPDATE b_iblock_element_prop_s' . $BLOCK_ID . ' SET PROPERTY_70 = ' . $dealId . ', PROPERTY_71 =  ' . $strDealSumma . ', PROPERTY_72 = ' . $strDealOtvetctvenniy . ' WHERE PROPERTY_70 = ' . $dealId ;
        $connection = \Bitrix\Main\Application::getConnection();
        $connection->queryExecute($sqlQuery);

    }
}