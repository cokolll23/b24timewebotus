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