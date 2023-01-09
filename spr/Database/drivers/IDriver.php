<?php

/**
 * Project:  Coral
 * File:     AbstractDriver.php
 * Created:  2015-02-01
 *
 * PHP version 7.2
 *
 * @category DB
 * @author   Ukrop
 *
 * @version  SVN: $Id: IDriver.php 263 2018-11-14 04:25:56Z Ukrop $
 * @link     $URL: https://ukrop-note:8443/svn/Coral/trunk/Database/drivers/IDriver.php $
 */

namespace Coral\Database\drivers;

/**
 * Общая реализация драйвера БД
 * @author Ukrop
 * @version 0.4.1
 */
interface IDriver {

    /**
     * Конструктор
     * @param array $_DSN Параметры подключения
     */
    public function __construct(array $_DSN);

    /**
     * Деструктор
     */
    public function __destruct();

    /**
     * Подключение к БД
     * @return boolean Статус
     */
    public function DriverConnect(array $_Settings);

    /**
     * Отключение от БД
     * @return boolean Статус
     */
    public function DriverDisconnect();

    /**
     * Запрос к БД
     * @param int $_QueryType Тип запроса
     * @param string $_QueryString Строка запроса
     * @return mixed Специфический результат
     */
    public function DriverQuery(int $_QueryType, string $_QueryString);

    /**
     * Подготовка запроса
     * @param string $_QueryString Строка запроса для подготовки
     * @param mixed $ Параметры для связывания
     * @return Boolean|NULL Статус подготовки
     *  (mysqli_result для MySQL при SELECT)
     *
     * @example
     *      $db->QueryPrepare('SELECT col1, col2 FROM table WHERE id=?d AND name=?s', $_Id, $_Name);
     *      $_Id = 2;
     *      $_Name = 'Name';
     *
     *      while($result = $db->QueryExecute()){
     *          print $result['col1'].' '.$result['col2'];
     *      }
     *
     */
    public function DriverQueryPrepare($_QueryString);

    /**
     * Выполнение подготовленного запроса
     * @return Boolean|NULL Статус выполнения
     */
    public function DriverQueryExecute();

    /**
     * Обработать результат
     * @return array Выборка
     */
    public function DriverFetch($_QueryResult);

    /**
     * Экранирование спецсимволов
     * @param string $_String Строка
     * @return string Экранированная строка
     */
    public function DriverEscapeString($_String);

    /**
     * Экранирование названия столбцов/таблиц
     * @param string $_String Название столбца/таблицы
     * @return string Название в кавычках
     *
     * @example
     *      SELECT data, id, guid FROM table ->
     *      SELECT `data`, `id`, `guid` FROM `table` -- MySQL
     *      SELECT [data], [id], [guid] FROM [table] -- MsSQL
     */
    public function DriverQuoteNames($_String);

    /**
     * Начать транзакцию
     * @param string $_Mode Режим
     * @return boolean Статус
     */
    public function DriverTransaction($_Mode);

    /**
     * Внести изменения
     * @return boolean Статус
     */
    public function DriverCommit();

    /**
     * Откатить изменения
     * @param string $_Name Имя
     * @return boolean Статус
     */
    public function DriverRollback();
}
