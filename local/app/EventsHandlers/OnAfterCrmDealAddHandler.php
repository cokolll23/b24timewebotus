<?php

namespace EventsHandlers;

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Diag\Debug;
use Bitrix\Iblock\ElementTable;
use \Bitrix\Crm\Service\Container;
use \Models\Autos\AutosTable;


Loader::includeModule('iblock');



class OnAfterCrmDealAddHandler
{
    public static function OnAfterCrmDealAddHandler(&$arFields)
    {

        $result = AutosTable ::add([
            'CONTACT_ID' => $arFields["CONTACT_ID"],
            'MARKA' => $arFields["UF_CRM_DEAL_MARKA"],
            'MARKA' => $arFields["TITLE"],
            'MODEL' => $arFields["UF_CRM_DEAL_MODEL"],
            'YEAR_CREATED' => $arFields["UF_CRM_DEAL_YEAR_CREATED"],
            'COLOR' => $arFields["UF_CRM_DEAL_COLOR"],
            'MILEGE' => $arFields["UF_CRM_DEAL_MILEAGE"],
        ]);

        if ($result->isSuccess()) {
            $id = $result->getId();
            $res = "Книга добавлена с ID: " . $id;
        } else {
            $res = $result->getErrorMessages();
        }



        //Debug::dumpToFile(переменная / массив, 'название в выводе', 'test.log');
        //Debug::writeToFile(переменная / массив, 'название в выводе', 'test.log');
        Debug::dumpToFile($res, '$arFields ' . date('d-m-Y; H:i:s'));

        $log = date('Y-m-d H:i:s') . ' ' . print_r($_REQUEST, true);
        file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);

        $element = new \CIBlockElement;
        $PROP = array();
        $PROP[68] = $arFields["CONTACT_ID"];
        $arLoadProductArray = Array(
           // "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
            "IBLOCK_ID"      => 17,
            "PROPERTY_VALUES"=> $PROP,
            "NAME"           => $arFields["UF_CRM_DEAL_MARKA"],
            "ACTIVE"         => "Y",            // активен

        );

        if($PRODUCT_ID = $element->Add($arLoadProductArray))
            echo "New ID: ".$PRODUCT_ID;
        else
            echo "Error: ".$element->LAST_ERROR;

        //  ["CONTACT_ID"] ["TITLE"]
        Debug::dumpToFile($arFields, '$arFields OnBeforeCrmDealUpdateHandler ' . date('d-m-Y; H:i:s'));


    }

}