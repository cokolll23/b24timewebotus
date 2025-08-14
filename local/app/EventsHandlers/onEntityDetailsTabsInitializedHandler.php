<?php

namespace EventsHandlers;

class onEntityDetailsTabsInitializedHandler
{

    public static function onEntityDetailsTabsInitializedHandler($event)
    {
        $tabs = $event->getParameter('tabs');
        // ID текущего элемента СРМ
        $entityID = $event->getParameter('entityID');
        // ID типа сущности: Сделка, Компания, Контакт и т.д.
        $entityTypeID = $event->getParameter('entityTypeID');

        if($entityTypeID == \CCrmOwnerType::Contact) {

            $tabs[] = [
                'id' => 'contactAuto',
                'name' => 'Гараж',
                'loader' => [
                    // Указываем URL адрес обработчика
                    'serviceUrl' => '/local/ajax/garage_ajax_tab.php',
                    'componentData' => [
                        'template' => '',
                        // Передаем массив необходимых параметров
                        'params' => [
                            'ENTITY_ID' => $entityID,
                            'ENTITY_TYPE' => $entityTypeID,
                            'TAB_ID' => 'newTab'
                        ]
                    ]
                ]
            ];
            $log = date('Y-m-d H:i:s') . ' ' . print_r($tabs, true);
            file_put_contents( '/log.txt', $log . PHP_EOL, FILE_APPEND);

        }

        return new \Bitrix\Main\EventResult(\Bitrix\Main\EventResult::SUCCESS, [
            'tabs' => $tabs,
        ]);



       /* $log = date('Y-m-d H:i:s') . ' ' . print_r($tabs, true);
        file_put_contents( '/log.txt', $log . PHP_EOL, FILE_APPEND);*/

    }

}