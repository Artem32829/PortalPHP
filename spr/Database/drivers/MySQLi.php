<?php

/**
 * Project:  Coral
 * File:     MySQLi.php
 * Created:  2015-02-01
 *
 * PHP version 7.2
 *
 * @category DB
 * @author   Ukrop
 *
 * @version  SVN: $Id: MySQLi.php 263 2018-11-14 04:25:56Z Ukrop $
 * @link     $URL: https://ukrop-note:8443/svn/Coral/trunk/Database/drivers/MySQLi.php $
 */

namespace Coral\Database\drivers;

use Coral\Database\DBException;

/**
 * MySQL Improved Драйвер БД
 * @author Ukrop
 * @version 0.4.1
 */
final class MySQLi implements IDriver {

    private $DSN = [
        'Persistent' => null,
        'Host'       => '127.0.0.1',
        'User'       => '',
        'Password'   => '',
        'Database'   => '',
        'Port'       => 3306,
        'Socket'     => null
    ];

    /**
     * @var \mysqli Description
     */
    private $Connection;

    /**
     * @var boolean Использовать постоянное подключение
     */
    private $Persistent = false;

    /**
     * @var \mysqli_stmt Description
     */
    private $Statement;

    /**
     * @var string Символ экранирования имен полей
     */
    protected $QuoteNamesCharacter = '`';

    /**
     * Конструктор
     * @param array $_Settings Параметры подключения
     */
    public function __construct(array $_Settings) {
//        parent::__construct($_Settings);
//        $this->DriverConnect($this->Default);
        if (!extension_loaded('mysqli')) {
            throw new DBException('extension_loaded', E_ERROR, 'MySQL extension is not loaded');
        }
    }

    /**
     * Деструктор
     */
    public function __destruct() {
        if ($this->Connection && !$this->Persistent) {
            $this->Connection->close();
            $this->Connection = null;
        }

        if ($this->Statement) {
            $this->Statement->free_result();
            $this->Statement->close();
            $this->Statement = null;
        }
    }

    /**
     * Подключение к БД
     * @return boolean Статус
     */
    public function DriverConnect(array $_Settings) {
        mysqli_report($_Settings['Debug'] ? MYSQLI_REPORT_ALL : MYSQLI_REPORT_STRICT);

        $this->DSN = array_merge($this->DSN, $_Settings);
        if ($_Settings['Persistent'] === true) {
            $this->Persistent = true;
            $this->DSN['Host'] = 'p:' . $this->DSN['Host'];
        }
        try {
            $this->Connection = new \mysqli($this->DSN['Host'], $this->DSN['User'], $this->DSN['Password'], $this->DSN['Database'], $this->DSN['Port'], $this->DSN['Socket']
            );
        } catch (\Exception $e) {
            $this->Connection = null;
            throw new DBException('mysqli_connect()', mysqli_connect_errno(), mysqli_connect_error());
        }

        if (isset($_Settings["Encoding"])) {
            if (!mysqli_set_charset($this->Connection, $this->DSN['Encoding'])) {
                throw new DBException('mysqli_set_charset()');
            }
        }

        //if (isset($_Settings["Locale"])) {
        //    $this->DriverQuery('SET @@lc_time_names=' . $this->DSN["Locale"]);
        //}
    }

    /**
     * Отключение от БД
     * @return boolean Статус
     */
    public function DriverDisconnect() {
        if ($this->Connection) {
            mysqli_close($this->Connection);
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
        try {
            $_Result = $this->Connection->query($_QueryString, MYSQLI_STORE_RESULT);
        } catch (\mysqli_sql_exception $e) {
            $this->Connection = null;
            throw new DBException($_QueryString, $e->getCode(), $e->getMessage(), $e->getTraceAsString());
        }

        if ($_Result instanceof \mysqli_result) {
            return $_Result;
        }

        if ($_Result === true) {
            return [
                'AffectedRows' => $this->Connection->affected_rows,
                'InsertID'     => $this->Connection->insert_id
            ];
        }

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
        $this->Statement = mysqli_prepare($this->Connection, $_QueryString);
    }

    /**
     * Выполнение подготовленного запроса
     * @return Boolean|NULL Статус выполнения
     */
    public function DriverQueryExecute() {
        return $this->Statement->execute();
    }

    /**
     * Обработать результат
     * @return array Выборка
     */
    public function DriverFetch($_QueryResult) {
        $_Row = mysqli_fetch_assoc($_QueryResult);

        if (mysqli_errno($this->Connection)) {
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
        return "'" . mysqli_real_escape_string($this->Connection, $_String) . "'";
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
        $this->Connection->autocommit(false);
        if ($_Mode == 'r') {
            $flags = MYSQLI_TRANS_START_READ_ONLY;
        } elseif ($_Mode == 'w') {
            $flags = MYSQLI_TRANS_START_READ_WRITE;
        } elseif ($_Mode == 's') {
            $flags = MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT;
        } else {
            $flags = MYSQLI_TRANS_START_READ_WRITE;
        }

        return $this->Connection->begin_transaction($_Mode);
    }

    /**
     * Внести изменения
     *
     * @param string $_Name Имя
     *
     * @return boolean Статус
     */
    public function DriverCommit() {
        $this->Connection->commit();
        $this->Connection->autocommit(true);

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
        $this->Connection->rollback();
        $this->Connection->autocommit(true);

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
