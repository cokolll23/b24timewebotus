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
            $dealId = $arFields["PROPERTY_VALUES"][70]["31:70"]["VALUE"]; // ID сделки, которую нужно изменить
            $dealOtvetstvenniy = $arFields["PROPERTY_VALUES"][72]["31:72"]["VALUE"]; // Ответственный ID сделки, которую нужно изменить
            $dealSumma = $arFields["PROPERTY_VALUES"][71]["31:71"]["VALUE"]; // Сумма ID сделки, которую нужно изменить


            // поля коды сделки
            /* $fieldAssignedById = "ASSIGNED_BY_ID"; // Ответственный
             $fieldSummaById = "OPPORTUNITY"; // Сумма*/

            OnAfterIBlockElementUpdateHandler::updateDeal($dealId, $dealOtvetstvenniy, $dealSumma);

        }
        self::$handlerDisallow = false;
    }

    public static function updateDeal($dealId, $AssignedById, $OPPORTUNITY)
    {

        $factory = Container::getInstance()->getFactory(\CCrmOwnerType::Deal);
        $item = $factory->getItem((int)$dealId);
        $item->set("ASSIGNED_BY_ID", $AssignedById);
        $item->set("OPPORTUNITY", $OPPORTUNITY);
        $item->save();
    }

}