<?php

/**
 * Project:  Coral
 * File:     MSSQL.php
 * Created:  2019-02-01
 *
 * PHP version 7.2
 *
 * @category DB
 * @author   Ukrop
 *
 * @version  SVN: $Id: MSSQL.php 263 2018-11-14 04:25:56Z Ukrop $
 * @link     $URL: https://ukrop-note:8443/svn/Coral/trunk/Database/drivers/MSSQL.php $
 */

namespace Coral\Database\drivers;

use Coral\Database\DBException;

/**
 * Microsoft SQL Server Драйвер БД
 * @author Ukrop
 * @version 0.0.1
 */
final class MSSQL implements IDriver {

    private $DSN = [
        'Server'   => null,
        'Database' => '',
        'User'     => '',
        'Password' => ''
    ];

    /**
     * @var resource
     */
    private $Connection;

    /**
     * @var boolean Использовать постоянное подключение
     */
    private $Persistent = false;

    /**
     * @var resource
     */
    private $Statement;

    /**
     * @var string Символ экранирования имен полей
     */
    protected $QuoteNamesCharacter = '\'';

    /**
     * Конструктор
     * @param array $_Settings Параметры подключения
     */
    public function __construct(array $_Settings) {
        if (!extension_loaded('sqlsrv')) {
            throw new DBException('extension_loaded', E_ERROR, 'MSSQL extension is not loaded');
        }
    }

    /**
     * Деструктор
     */
    public function __destruct() {
        if ($this->Statement) {
            sqlsrv_free_stmt($this->Statement);
            $this->Statement = null;
        }

        if ($this->Connection) {
            sqlsrv_close($this->Connection);
            $this->Connection = null;
        }
    }

    /**
     * Подключение к БД
     * @return boolean Статус
     */
    public function DriverConnect(array $_Settings) {
        sqlsrv_configure('WarningsReturnAsErrors', true);

        try {
            $this->Connection = sqlsrv_connect($_Settings['Host'], [
                "Database"             => $_Settings['Database'],
                "Uid"                  => $_Settings['User'],
                "PWD"                  => $_Settings['Password'],
                'CharacterSet'         => $_Settings['Encoding'],
                'ReturnDatesAsStrings' => !empty($_Settings['Options']['ReturnDatesAsStrings'])
            ]);
        } catch (\Exception $e) {
            $this->Connection = null;
            throw new DBException('sqlsrv_connect()', print_r(sqlsrv_errors(), true), -1);
        }
    }

    /**
     * Отключение от БД
     * @return boolean Статус
     */
    public function DriverDisconnect() {
        if ($this->Connection) {
            sqlsrv_close($this->Connection);
            $this->Connection = null;
        }
    }

    /**
     * Запрос к БД
     * @param int $_QueryType Тип запроса
     * @param string $_QueryString Строка запроса
     * @return mixed Специфический результат
     */
    public function DriverQuery(int $_QueryType, string $_QueryString) {
            $_Result = sqlsrv_query($this->Connection, $_QueryString);
//        try {
//        } catch (\Exception $e) {
//            $this->Connection = null;
//            throw new DBException($_QueryString, $e->getCode(), $e->getMessage(), $e->getTraceAsString());
//        }

        if (is_resource($_Result)) {
            return $_Result;
        }

        print '<pre>';
        print_r([$_QueryString, $_Result]);
        exit;

        throw new DBException($_QueryString, $this->Connection->errno, $this->Connection->error);
    }

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
    public function DriverQueryPrepare($_QueryString) {
        $this->Statement = sqlsrv_prepare($this->Connection, $_QueryString);
    }

    /**
     * Выполнение подготовленного запроса
     * @return Boolean|NULL Статус выполнения
     */
    public function DriverQueryExecute() {
        return sqlsrv_execute($this->Statement);
    }

    /**
     * Обработать результат
     * @return array Выборка
     */
    public function DriverFetch($_QueryResult) {
        $_Row = sqlsrv_fetch_array($_QueryResult, SQLSRV_FETCH_ASSOC);

        if (sqlsrv_errors()) {
            throw new DBException('SQL Exception', 0, 'fetch_assoc()');
        }

        if ($_Row === false) {
            return null;
        }

        return $_Row;
    }

    /**
     * Экранирование спецсимволов
     * @param string $_String Строка
     * @return string Экранированная строка
     */
    public function DriverEscapeString($_String) {
        return "'" . $_String . "'";
    }

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
    public function DriverQuoteNames($_String) {
        return '`' . str_replace('`', '``', $_String) . '`';
    }

    /**
     * Начать транзакцию
     *
     * @param int $_Mode Режим
     *
     * @return boolean Статус
     */
    public function DriverTransaction($_Mode = null) {
        if ($_Mode == 'r') {
            $flags = MYSQLI_TRANS_START_READ_ONLY;
        } elseif ($_Mode == 'w') {
            $flags = MYSQLI_TRANS_START_READ_WRITE;
        } elseif ($_Mode == 's') {
            $flags = MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT;
        } else {
            $flags = MYSQLI_TRANS_START_READ_WRITE;
        }

        return sqlsrv_begin_transaction($this->Connection);
    }

    /**
     * Внести изменения
     *
     * @param string $_Name Имя
     *
     * @return boolean Статус
     */
    public function DriverCommit() {
        sqlsrv_commit($this->Connection);

        return true;
    }

    /**
     * Откатить изменения
     *
     * @param string $_Name Имя
     *
     * @return boolean Статус
     */
    public function DriverRollback() {
        sqlsrv_rollback($this->Connection);

        return true;
    }

    /**
     * Генерация ошибки
     *
     * @param string $_Query
     * @param int $_Code
     * @param string $_Message
     *
     * @return \Coral\Database\DBException
     */
    public function Error($_Query, $_Code = -1, $_Message = 'Error') {
        if ($this->Connection) {
            $_Code = $this->Connection->errno;
            $_Message = $this->Connection->error;
        }

        return new DBException($_Query, $_Code, $_Message);
    }

}
