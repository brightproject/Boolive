<?php
/**
 * Boolive!
 * Главный исполняемый файл. Запуск движка и проекта
 *
 * @version 2
 * @author Vladimir Shestakov <boolive@yandex.ru>
 * @link http://boolive.ru
 */
use Boolive\Boolive,
    Boolive\data\Data,
    Boolive\commands\Commands,
    Boolive\input\Input;
// Подключение конфигурации путей
require 'config.php';
// Подключение движка Boolive
require DIR_SERVER_ENGINE.'Boolive.php';
// Активация Boolive
Boolive::activate();
// Исполнение корневого объекта. Вывод результата клиенту
echo Data::read()->start(new Commands(), Input::getSource());