<?php

/**
 * Project:  Coral
 * File:     Firebird.php
 * Created:  2015-02-01
 *
 * PHP version 5
 *
 * @category DB
 * @author   Ukrop
 *
 * @version  SVN: $Id: Firebird.php 229 2018-02-04 16:38:00Z Ukrop $
 * @link     $URL: https://ukrop-note:8443/svn/coral/trunk/Database/drivers/Firebird.php $
 */

namespace Coral\Database\drivers;

#

use Coral\Database\DBException;

/**
 * Firebird Драйвер БД
 * @author Ukrop
 * @version 0.3.1
 */
final class Firebird implements IDriver {

    private $DSN = [
        'Persistent' => true,
        'Host'       => '127.0.0.1',
        'User'       => 'SYSDBA',
        'Password'   => 'masterkey',
        'Database'   => '',
        'Port'       => 3050,
        'Encoding'   => 'WIN1251',
        'Role'       => null
    ];

    /**
     * @var \Firebird Description
     */
    private $Connection;
    private $Transaction;

    /**
     * @var boolean Использовать постоянное подключение
     */
    private $Persistent = false;

    /**
     * @var \Firebird_stmt Description
     */
    private $Statement;

    /**
     * @var string Символ экранирования имен полей
     */
    protected $QuoteNamesCharacter = '"';

    /**
     * Конструктор
     * @param array $_DSN Параметры подключения
     */
    public function __construct(array $_Settings) {
//        parent::__construct($_Settings);
//        $this->DriverConnect($this->Default);
        if (!extension_loaded('interbase')) {
            throw new DBException('extension_loaded', E_ERROR, 'Firebird extension is not loaded');
        }
    }

    public function __destruct() {
        if ($this->Connection && !$this->Persistent) {
            ibase_close($this->Connection);
            $this->Connection = null;
        }

        if ($this->Statement) {
            ibase_free_result($this->Statement);
            $this->Statement = null;
        }
    }

    /**
     * Подключение к БД
     * @return boolean Статус
     */
    public function DriverConnect(array $_Settings) {
//        if ($_Settings['Debug']) {
//            Firebird_report(Firebird_REPORT_ALL);
//        } else {
//            Firebird_report(Firebird_REPORT_STRICT);
//        }

        $this->DSN = array_merge($this->DSN, $_Settings);
        if ($_Settings['Persistent'] === true) {
            $this->Persistent  = true;
            $this->DSN['Host'] = 'p:' . $this->DSN['Host'];
        }
        try {
            $this->Connection = ibase_connect($this->DSN['Host'] . ':' . $this->DSN['Database'], $this->DSN['User'], $this->DSN['Password'], $this->DSN['Encoding'], null, null, $this->DSN['Role']);
        } catch (\Exception $e) {
            $this->Connection = null;
            throw new DBException('ibase_connect()', ibase_errcode(), ibase_errmsg());
        }

//        if (isset($_Settings["Locale"])) {
//            $this->DriverQuery('SET @@lc_time_names=' . $this->DSN["Locale"]);
//        }
    }

    /**
     * Отключение от БД
     * @return boolean Статус
     */
    public function DriverDisconnect() {
        if ($this->Connection) {
            $l_ConnectionStatus = ibase_close($this->Connection);

            if ($l_ConnectionStatus) {
                $this->Connection = null;
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Запрос к БД
     * @param int $_QueryType Тип запроса
     * @param string $_QueryString Строка запроса
     * @return mixed Специфический результат
     */
    public function DriverQuery(int $_QueryType, string $_QueryString) {
        $_Result = ibase_query($this->Connection, $_QueryString);
        if ($_Result === false) {
//            ibase_close($this->Connection);
//            $this->Connection = null;
            throw new DBException('SQL Exception on query: '.$_QueryString, ibase_errcode(), ibase_errmsg());
        }

        return $_Result;
    }

    /**
     * Подготовка запроса
     * @param string $_QueryString Строка запроса для подготовки
     * @param mixed $ Параметры для связывания
     * @return Boolean|NULL Статус подготовки
     *  (Firebird_result для Firebird при SELECT)
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
        $this->Statement = ibase_prepare($this->Connection, $_QueryString);
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
//        var_dump([
//            func_get_args(),
//            debug_backtrace()
//                ]);exit;
        $_Row = ibase_fetch_assoc($_QueryResult);

        if (ibase_errcode()) {
            throw new DBException('ibase_fetch_assoc()', ibase_errcode(), ibase_errmsg());
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
     *      SELECT `data`, `id`, `guid` FROM `table` -- Firebird
     *      SELECT [data], [id], [guid] FROM [table] -- MsSQL
     */
    public function DriverQuoteNames($_String) {
        return '`' . str_replace('`', '``', $_String) . '`';
    }

    /**
     * Начать транзакцию
     * @param string $_Name Имя
     * @return boolean Статус
     */
    public function DriverTransaction($_Name) {
        $this->Transaction = ibase_trans($this->Connection, IBASE_READ | IBASE_WRITE | IBASE_REC_NO_VERSION);
        return $this->Transaction;
    }

    /**
     * Внести изменения
     * @param string $_Name Имя
     * @return boolean Статус
     */
    public function DriverCommit() {
        return ibase_commit_ret($this->Transaction);
    }

    /**
     * Откатить изменения
     * @param string $_Name Имя
     * @return boolean Статус
     */
    public function DriverRollback() {
        return ibase_rollback_ret($this->Transaction);
    }

}
