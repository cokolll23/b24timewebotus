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

        Debug::dumpToFile($arFields, 'BeforeCrmDealUpdateHandler', 'arFields.log');

        $dealId = (string)$arFields ["ID"];
       // $dealASSIGNEDId = $arFields["ASSIGNED_BY_ID"]; // Ответственный в сделке и CRM
        $dealSumm = (string)$arFields["OPPORTUNITY"]; // Сумма в сделке и CRM

        $arrCurrDealVal = static::getCurrDeal($dealId);
        if ($arFields["ASSIGNED_BY_ID"]!=''){
            $dealASSIGNEDId = $arFields["ASSIGNED_BY_ID"];
        }else{
            $dealASSIGNEDId =  $arrCurrDealVal['AssignedById'];
        }
        if ($arFields["OPPORTUNITY"]!=''){
            $dealSumm = (string)$arFields["OPPORTUNITY"];
        }else{
            $dealSumm =  $arrCurrDealVal['OPPORTUNITY'];
        }

        $elId = OnAfterCrmDealUpdateHandler::getIblockElId(16, $dealId);

        $sqlQuery = " UPDATE b_iblock_element_prop_s16 SET PROPERTY_71 =  '" . $dealSumm . "', PROPERTY_72 = '" . $dealASSIGNEDId . "' WHERE IBLOCK_ELEMENT_ID = '" . $elId . "'";

        $connection = \Bitrix\Main\Application::getConnection();
        $connection->queryExecute($sqlQuery);
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
    public static function getCurrDeal($dealId)
    {
        $getCurrDealRes = [];

        $factory = Container::getInstance()->getFactory(\CCrmOwnerType::Deal);
        $getCurrDealRes['dealId'] = $item = $factory->getItem((int)$dealId);
        $getCurrDealRes['AssignedById'] = $item->getAssignedById();
        $getCurrDealRes['OPPORTUNITY'] = $item->get("OPPORTUNITY");

        return $getCurrDealRes;

    }
}