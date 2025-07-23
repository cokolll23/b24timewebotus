<?php

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Page\Asset;

// \Bitrix\Main\UI\Extension::load('cab_log_events.common'); // вывод js событий
\Bitrix\Main\UI\Extension::load('cav_js_extention.lesson20'); // вывод js событий


if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
}
if (file_exists(__DIR__ . '/../app/autoloader.php')) {
    require_once __DIR__ . '/../app/autoloader.php';
}
if (file_exists(__DIR__ . '/../include/functions/pretty_print.php')) {
    require_once __DIR__ . '/../include/functions/pretty_print.php';
}

// include_once __DIR__ . '/classes/BXHelper.php';
include_once __DIR__ . '/classes/LKIblock.php';

include_once __DIR__ . '/classes/Dadata.php';


$eventManager = \Bitrix\Main\EventManager::getInstance();

// после изменения записи в сделке
/*$eventManager->addEventHandlerCompatible("crm", "OnAfterCrmDealUpdate",'OnAfterCrmDealUpdateHandler');
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate",'OnAfterIBlockElementUpdateHandler');
$eventManager->addEventHandlerCompatible("crm", "OnAfterCrmDealAdd",'OnAfterCrmDealAddHandler');
*/

// для создания кастомных свойств
$eventManager->addEventHandler('iblock', 'OnIBlockPropertyBuildList', ['UserTypes\SignUpForProcedure', 'GetUserTypeDescription']);
$eventManager->addEventHandler('iblock', 'OnIBlockPropertyBuildList', ['UserTypes\SelectDeal', 'GetUserTypeDescription']);


$eventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate", ['EventsHandlers\OnAfterIBlockElementUpdateHandler', 'OnAfterIBlockElementUpdateHandler']);
//$eventManager->addEventHandlerCompatible("crm", "OnAfterCrmDealUpdate", ['EventsHandlers\OnAfterCrmDealUpdateHandler', 'OnAfterCrmDealUpdateHandler']);
$eventManager->addEventHandlerCompatible("crm", "OnBeforeCrmDealUpdate", ['EventsHandlers\OnBeforeCrmDealUpdateHandler', 'OnBeforeCrmDealUpdateHandler']);


// Обработчик для изменений в контактах
//$eventManager->AddEventHandler('crm', 'OnAfterCrmContactUpdate', [__CLASS__, 'updateFromContact']);

// Обработчик для изменений в делах
//$eventManager->AddEventHandler('crm', 'OnAfterCrmDealUpdate', [__CLASS__, 'updateFromDeal']);

// Обработчик для изменений в лидах
//$eventManager->AddEventHandler('crm', 'OnAfterCrmLeadUpdate', [__CLASS__, 'updateFromLead']);

// Обработчик для добавления активности
$eventManager->AddEventHandler('crm', 'OnActivityUpdate', ['EventsHandlers\updateFromActivity', 'updateFromActivity']);
$eventManager->AddEventHandler('crm', 'OnActivityAdd', ['EventsHandlers\OnActivityAddHandler', 'OnActivityAddHandler']);
//$eventManager->AddEventHandler('crm', 'OnActivityUpdate', 'updateFromActivity');

// Обработчик для изменений в timeline
//$eventManager->AddEventHandler('crm', 'OnAfterTimelineAdd', [__CLASS__, 'updateFromTimeline']);

function getIblockCodeHandler($arFieldsIblockID)
{
    $result = IblockTable::getList(array(
        'filter' => ['ID' => $arFieldsIblockID],
        'select' => ['CODE']
    ));
    if ($iblock = $result->fetch()) {
        $iblockCode = $iblock['CODE'];
    }
    return $iblockCode;
}