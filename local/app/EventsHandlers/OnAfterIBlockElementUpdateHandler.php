<?php

namespace EventsHandlers;

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Diag\Debug;
use Bitrix\Crm\Entity\Deal;
use \Bitrix\Crm\Service\Container;

class OnAfterIBlockElementUpdateHandler
{
    private static bool $handlerDisallow = false;

    /**
     * @param   &$arFields
     * @return void
     */
    public static function OnAfterIBlockElementUpdateHandler(&$arFields): void
    {
        /* проверяем, что обработчик уже запущен */
        if (self::$handlerDisallow) {
            return;
        }

        /* взводим флаг запуска */
        self::$handlerDisallow = true;

        if (Loader::includeModule('iblock')) {

            $result = IblockTable::getList(array(
                'filter' => ['ID' => $arFields['IBLOCK_ID']],
                'select' => ['CODE']
            ));
            if ($iblock = $result->fetch()) {
                $iblockCode = $iblock['CODE'];
            } else {
                echo "Инфоблок с ID " . $arFields['ID'] . " не найден.";
            }
        } else {
            echo "Модуль инфоблоков не подключен.";

        }
        $iblockCodeOpt = 'deals';
        if ($iblockCode == $iblockCodeOpt) {

            Loader::includeModule('crm');
            Debug::dumpToFile($arFields, 'OnAfterIBlockElementUpdateHandler', 'arFields.log');
            // Debug::writeToFile(переменная/массив, 'название в выводе', 'test.log');

            // свойства иб
            if (is_array($arFields["PROPERTY_VALUES"][70]["31:70"])) {
                $dealId = $arFields["PROPERTY_VALUES"][70]["31:70"]["VALUE"]; // ID сделки, которую нужно изменить
            }
            //if (is_array($arFields["PROPERTY_VALUES"][72])) {
                $dealOtvetstvenniy = $arFields["PROPERTY_VALUES"][72];
           // }
            // Ответственный ID сделки, которую нужно изменить
            if (is_array($arFields["PROPERTY_VALUES"][71]["31:71"])) {
                $dealSumma = $arFields["PROPERTY_VALUES"][71]["31:71"]["VALUE"]; // Сумма ID сделки, которую нужно изменить
            }
            $dealId = (int)$dealId;

            $dealSumma = (string)$dealSumma;

            OnAfterIBlockElementUpdateHandler::updateDeal($dealId, $dealOtvetstvenniy, $dealSumma);

        }
        self::$handlerDisallow = false;
    }

    public static function updateDeal($dealId, $AssignedById, $OPPORTUNITY)
    {
        $arrCurrDealVal = static::getCurrDeal($dealId);

        $factory = Container::getInstance()->getFactory(\CCrmOwnerType::Deal);
        $item = $factory->getItem((int)$dealId);

        if ($AssignedById!=''){
            $item->set("ASSIGNED_BY_ID", $AssignedById);
        }else{
            $item->set("ASSIGNED_BY_ID", $arrCurrDealVal['AssignedById']);
        }
        if ($OPPORTUNITY!=''){
            $item->set("OPPORTUNITY", $OPPORTUNITY);
        }else{
            $item->set("OPPORTUNITY", $arrCurrDealVal['OPPORTUNITY']);
        }

        $item->save();
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