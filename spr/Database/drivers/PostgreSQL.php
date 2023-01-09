<?php

/**
 * Project:  Coral
 * File:     PostgreSQL.php
 * Created:  2017-09-21
 *
 * PHP version 7.2
 *
 * @category DB
 * @author   Ukrop
 *
 * @version  SVN: $Id: PostgreSQL.php 229 2018-02-04 16:38:00Z Ukrop $
 * @link     $URL: https://ukrop-note:8443/svn/coral/trunk/Database/drivers/PostgreSQL.php $
 */

namespace Coral\Database\drivers;

use Coral\Database\DBException;

/**
 * PostgreSQL Драйвер БД
 * @author Ukrop
 * @version 0.1.1
 */
final class PostgreSQL implements IDriver {

    private $DSN = [
        'Persistent' => true,
        'Host'       => '127.0.0.1',
        'User'       => '',
        'Password'   => '',
        'Database'   => '',
        'Port'       => 3306,
        'Socket'     => null
    ];

    /**
     * @var resource Description
     */
    private $Connection;

    /**
     * @var boolean Использовать постоянное подключение
     */
    private $Persistent = false;

    /**
     * @var \pg_stmt Description
     */
    private $Statement;

    /**
     * @var string Символ экранирования имен полей
     */
    protected $QuoteNamesCharacter = '"';

    /**
     * Конструктор
     * @param array $_Settings Параметры подключения
     */
    public function __construct(array $_Settings) {
        if (!extension_loaded('pgsql')) {
            throw new DBException('extension_loaded', E_ERROR, 'PostgreSQL extension is not loaded');
        }
    }

    /**
     * Деструктор
     */
    public function __destruct() {
        if ($this->Connection && !$this->Persistent) {
            pg_close($this->Connection);
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
        $this->DSN = array_merge($this->DSN, $_Settings);
        $this->Persistent = ($_Settings['Persistent'] === true);
        $ConnectionString = "host={$this->DSN['Host']} port={$this->DSN['Port']} dbname={$this->DSN['Database']} user={$this->DSN['User']} password={$this->DSN['Password']}";

        try {
            $this->Connection = $this->Persistent ? pg_pconnect($ConnectionString) : pg_connect($ConnectionString);
        } catch (\Exception $e) {
            $this->Connection = null;
            throw new DBException('pg_connect()', 0, pg_last_error($this->Connection));
        }

        if (isset($_Settings['Locale'])) {
            if (!pg_set_client_encoding($this->Connection, $this->DSN['Locale'])) {
                throw new DBException('pg_set_client_encoding()', 0, pg_last_error($this->Connection));
            }
        }
    }

    /**
     * Отключение от БД
     * @return boolean Статус
     */
    public function DriverDisconnect() {
        if ($this->Connection) {
            pg_close($this->Connection);
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
        $_Result = pg_query($this->Connection, $_QueryString);
        if ($_Result === false) {
            throw new DBException($_QueryString, 0, pg_last_error($this->Connection));
        }

        if (is_resource($_Result)) {
            return $_Result;
        }

        return [
            'AffectedRows' => pg_affected_rows($this->Connection),
            'InsertID'     => $_Result
        ];
    }

    /**
     * Подготовка запроса
     * @param string $_QueryString Строка запроса для подготовки
     * @param mixed $ Параметры для связывания
     * @return Boolean|NULL Статус подготовки
     *  (pg_result для MySQL при SELECT)
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
        $this->Statement = pg_prepare($this->Connection, $_QueryString);
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
        $_Row = pg_fetch_assoc($_QueryResult);

        if (!pg_result_status($_QueryResult) || !empty(\pg_last_error($this->Connection))) {
            throw new DBException('SQL Exception', 0, pg_last_error($this->Connection));
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
        return "'" . pg_escape_string($this->Connection, $_String) . "'";
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
        return '"' . str_replace('"', '""', $_String) . '"';
    }

    /**
     * Начать транзакцию
     * @param string $_Name Имя
     * @return boolean Статус
     */
    public function DriverTransaction($_Name) {
        return pg_begin_transaction($this->Connection, NULL, $_Name);
    }

    /**
     * Внести изменения
     * @param string $_Name Имя
     * @return boolean Статус
     */
    public function DriverCommit($_Name) {
        return pg_commit($this->Connection, NULL, $_Name);
    }

    /**
     * Откатить изменения
     * @param string $_Name Имя
     * @return boolean Статус
     */
    public function DriverRollback($_Name) {
        return pg_rollback($this->Connection, NULL, $_Name);
    }

}
