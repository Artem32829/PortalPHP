<?php

/**
 * Project:  Coral
 * File:     DB.php
 * Created:  2015-02-01
 *
 * PHP version 5
 *
 * @category DB
 * @author   Ukrop
 *
 * @version  SVN: $Id: DB.php 263 2018-11-14 04:25:56Z Ukrop $
 * @link     $URL: https://ukrop-note:8443/svn/Coral/trunk/Database/DB.php $
 */

namespace Coral\Database;

use Coral\Database\QueryTypes;

/**
 * Работа с базой данных
 * @author Ukrop
 * @version 0.4.0
 */
final class DB {

    /**
     * @var Coral\Database\drivers\IDriver Драйвер БД
     */
    private $Driver;

    /**
     * @var array Плагины преобразований результатов
     */
    private $Plugins = [];

    /**
     * @var string Регулярка для разбора плагинов
     * @example
     *  -- TRANSFORM-MAP(CategoryID ID) =>
     *      PLUGIN  TRANSFORM-MAP
     *      PARAMS  CategoryID, ID
     */
    private $AttributesPattern = '#\-\-\040([A-Z0-9_\-]+)(?>\()([A-z0-9_\040]+)*(?>\))#';

    /**
     * Плагины, работающие до запроса
     */
    private $BeforeQueryActions = [];

    /**
     * Плагины, работающие после запроса
     */
    private $AfterQueryActions = [];

    /**
     * @var string Регулярка для разбора плэйсхолдеров
     * @example
     *      ?n      - целое число или NULL
     *      ?d      - целое число
     *      ?f      - вещественное
     *      ?s      - строка
     *      ?t      - время @todo time
     *      ?a      - перечисление или выбор (SET или ENUM)
     *      ?b      - boolean
     *      ?[?d,?s,?d,?s] - развертка массива по типам [1, 2, 3, 4]
     *          'INSERT INTO user(id,name,level,hash) VALUES(?[?d,?s,?d,?s])', [1,'2',3,'4']
     *          => INSERT INTO user(id,name,level,hash) VALUES(1, 's', 3, '4')
     *      ?{}     - условный плейсхолдер, раскрывается если внутри параметр NOT NULL, иначе - удаляется
     */
    private $PlaceholdersRegExp = '/\?([adsfnz_#b]|{[^{}]+}|\[[^\[\]]+\])/';

    /**
     * @var string Префикс таблиц
     */
    private $TablePrefix = '';

    /**
     * @var array Параметры для подстановки
     */
    protected $QueryParams;

    /**
     * Инициализация
     * @param array $_Settings Настройки
     */
    public function Setup(array $_Settings) {
        $_Driver = $_Settings['Driver'];
        $this->Driver = new $_Driver($_Settings);
        $this->TablePrefix = $_Settings['Prefix'];

        foreach ($_Settings['plugins'] as $_PluginName => $_PluginSettings) {
            $l_PluginClass = 'Coral\\Database\\plugins\\' . $_PluginName;
            $_Plugin = new $l_PluginClass($_PluginSettings);
            $this->Plugins[$_Plugin->Alias()] = $_Plugin;
            $this->AssignPluginActions($_Plugin->Attaches(), $_Plugin->Alias());
        }
        $this->Connect($_Settings);
    }

    /**
     * Назначить обработчики событий плагина
     * @param array $_PluginActions События, обрабатываемые плагином
     * @param string $_PluginAlias Псевдоним плагина
     * @return void
     */
    private function AssignPluginActions(array $_PluginActions, string $_PluginAlias) {
        foreach ($_PluginActions as $Event => $Action) {
            switch ($Event) {
                case 'BeforeQuery':
                    $this->BeforeQueryActions[$_PluginAlias] = $Action;
                    break;
                case 'AfterQuery':
                    $this->AfterQueryActions[$_PluginAlias] = $Action;
                    break;
            }
        }
    }

    /**
     *
     */
    public function Connect($_Settings) {
//        $this->Event('connection', $_Settings);
//        try {
        $this->Driver->DriverConnect($_Settings);
//        } catch (DBException $e) {
//            echo $e;
//            $this->Event('Error', $e);
//        }
    }

    /**
     *
     */
    public function Disconnect() {
//        $this->Event('connection', $_Settings);
//        try {
        $this->Driver->DriverDisconnect();
//        } catch (DBException $e) {
//            echo $e;
//            $this->Event('Error', $e);
//        }
    }

    /**
     *
     */
    public function GetPlugins($_Query) {
        $_Plugins = [];
        $_Matches = [];
        if (0 < preg_match_all($this->AttributesPattern, $_Query, $_Matches, PREG_SET_ORDER)) {
            foreach ($_Matches as $_Plugin) {
                $_Plugins[$_Plugin[1]] = explode(' ', $_Plugin[2]);
            }
        }

        return $_Plugins;
    }

    /**
     * Развертывание плэйсхолдеров в параметры запроса
     * @param string $_Query Запрос
     * @param mixed ... Параметры подстановки
     */
    public function ExpandPlaceholders(...$_Query) {
        $this->QueryParams = $_Query;
        array_shift($this->QueryParams);

        return $this->ReplacePlaceholder($_Query[0]);
    }

    /**
     *
     */
    public function ReplacePlaceholder($_Query) {
        $_QueryString = preg_replace_callback($this->PlaceholdersRegExp, [$this, 'Replaces'], $_Query);

        return $_QueryString;
    }

    public function Replaces($_Matches) {
        $_Value = null;

        switch ($_Matches[1]{0}) {
            case '_':
                $_Value = $this->TablePrefix;
                break;
            case '[': // в [ ] указывается типы данных для записи
                $_RawValue = array_shift($this->QueryParams);
                $_Keys = array_keys($_RawValue);
                $_SubQuery = preg_replace_callback($this->PlaceholdersRegExp, function ($Match) use($_RawValue, &$_Keys) {
                    $k = array_shift($_Keys);
                    return $this->Driver->DriverQuoteNames($k) . '=' . $Match[0];
                }, trim($_Matches[1], '[]'));

                $_OldQP = $this->QueryParams;
                $this->QueryParams = is_array($_RawValue) ? $_RawValue : [1 => $_RawValue];
                $_Value = call_user_func_array([$this, 'ReplacePlaceholder'], array_merge([$_SubQuery], (array) ($_RawValue)));
                $this->QueryParams = $_OldQP;
                break;
            case 'a':
                $_RawValue = array_shift($this->QueryParams);
                $_Values = [];
                foreach ($_RawValue as $_AName => $_AValue) {
                    $_Values[] = $this->Driver->DriverEscapeString($_AValue);
                }
                $_Value = implode(', ', $_Values);
                break;
            case '#':
                $_RawValue = (array) array_shift($this->QueryParams);
                $_Values = [];
                foreach ($_RawValue as $_AName => $_AValue) {
                    $_Values[] = $this->Driver->DriverQuoteNames($_AValue);
                }
                $_Value = implode(', ', $_Values);
                break;
            case 'd':
                $_RawValue = array_shift($this->QueryParams);
                $_Value = intval($_RawValue);
                break;
            case 'n':
                $_RawValue = array_shift($this->QueryParams);
                $_Value = empty($_RawValue) ? 'NULL' : intval($_RawValue);
                break;
            case 's':
                $_RawValue = array_shift($this->QueryParams);
                $_Value = $this->Driver->DriverEscapeString($_RawValue);
                break;
            case 'f':
                $_RawValue = array_shift($this->QueryParams);
                $_Value = floatval($_RawValue);
                break;
            case 'b':
                $_RawValue = array_shift($this->QueryParams);
                $_Value = empty($_RawValue) ? 0 : 1;
                break;
            case 'z':
                $_RawValue = array_shift($this->QueryParams);
                $_Value = empty($_RawValue) ? 'NULL' : $this->Driver->DriverEscapeString($_RawValue);
                break;
            case '{': // условный блок, будет раскрыт при значении параметра NOT NULL
                $_SubQuery = \mb_substr($_Matches[1], 1, \mb_strlen($_Matches[1]) - 2);

                $_RawValue = array_shift($this->QueryParams);
                $_OldQP = $this->QueryParams;

                if (\is_null($_RawValue)) {
                    $_Value = null;
                } else {
                    $this->QueryParams = [1 => $_RawValue];
                    $_Value = call_user_func_array([$this, 'ReplacePlaceholder'], array_merge([$_SubQuery], (array) ($this->QueryParams)));
                }
                $this->QueryParams = $_OldQP;
                break;
            default:
                $_RawValue = array_shift($this->QueryParams);
                $_Value = "- placeholder($_Matches[0]) -";
                break;
        }
        return $_Value;
    }

    /**
     * Выполнить запрос в БД
     * @param string $_Query Текст и параметры запроса
     * @return array Результат
     */
    public function Query(...$_Query) {
        $l_Plugins = $this->GetPlugins($_Query[0]);
        foreach ($l_Plugins as $l_Plugin => $Args) {
            $this->BeforeQueryActions[$l_Plugin]($_Query, $Args);
        }

        $l_QueryString = call_user_func_array([$this, 'ExpandPlaceholders'], $_Query);
        return $this->Driver->DriverQuery(QueryTypes::QUERY, $l_QueryString);
    }

    /**
     * Выборка набора
     * @param array $Arguments Массив параметров запроса
     * @return array
     */
    public function Select(...$Arguments) {
        $l_SkipQuery = false;
        $l_Plugins = $this->GetPlugins($Arguments[0]);

        $_Result = [];
        $_Query = call_user_func_array([$this, 'ExpandPlaceholders'], $Arguments);

        foreach ($l_Plugins as $l_Plugin => $Args) {
            if (!empty($this->BeforeQueryActions[$l_Plugin])) {
                $l_SkipQuery = $l_SkipQuery || $this->BeforeQueryActions[$l_Plugin]($_Query, $Args);
            }
        }

        if (empty($l_SkipQuery) || !$l_SkipQuery) {
            $_DriverResult = $this->Driver->DriverQuery(QueryTypes::SELECT, $_Query);
            while ($Row = $this->Driver->DriverFetch($_DriverResult)) {
                $_Result[] = $Row;
            }
            /**
             * @todo fix dependency
             */
            if ($_DriverResult instanceof \mysqli_result) {
                $_DriverResult->free();
            }
        }

        foreach ($l_Plugins as $l_Plugin => $Args) {
            if (!empty($this->AfterQueryActions[$l_Plugin])) {
                $this->AfterQueryActions[$l_Plugin]($_Result, $_Query, $Args);
            }
        }

        return $_Result;
    }

    public function SelectRow(...$Arguments) {
        $_Result = [];
        $_Query = call_user_func_array([$this, 'ExpandPlaceholders'], $Arguments);

        $_DriverResult = $this->Driver->DriverQuery(QueryTypes::SELECT, $_Query);
        while ($Row = $this->Driver->DriverFetch($_DriverResult)) {
            $_Result[] = $Row;
        }
        /**
         * @todo fix dependency
         */
        if ($_DriverResult instanceof \mysqli_result) {
            $_DriverResult->free();
        }

        if (count($_Result) > 1) {
            throw new DBException($_Query, -1, 'В результирующем наборе больше 1 записи!' . print_r($_Result, 1));
        }

        return array_pop($_Result);
    }

    /**
     * Выборка ячейки
     * @param array $Arguments Запрос и параметры подстановки
     * @return mixed
     */
    public function SelectCell(...$Arguments) {
        $_Query = call_user_func_array([$this, 'ExpandPlaceholders'], $Arguments);

        $_DriverResult = $this->Driver->DriverQuery(QueryTypes::SELECT, $_Query);
        $_Result = $this->Driver->DriverFetch($_DriverResult);
        /**
         * @todo fix dependency
         */
        if ($_DriverResult instanceof \mysqli_result) {
            $_DriverResult->free();
        }

        if ((\is_array($_Result) || (\is_object($_Result) && ($_Result instanceof \Countable))) && count($_Result) > 1) {
            throw new DBException($_Query, -1, 'В результирующем наборе больше 1 записи!' . print_r($_Result, 1));
        }

        if (is_null($_Result)) {
            return null;
        }

        return array_shift($_Result);
    }

    /**
     * Выборка столбца
     * @param array $Arguments Запрос и параметры подстановки
     * @return mixed
     */
    public function SelectColumn(...$Arguments) {
        $_Result = [];
        $_Query = call_user_func_array([$this, 'ExpandPlaceholders'], $Arguments);

        $_DriverResult = $this->Driver->DriverQuery(QueryTypes::SELECT, $_Query);
        while ($Row = $this->Driver->DriverFetch($_DriverResult)) {
            $t = array_values($Row);
            $_Result[] = $t[0];
        }
        /**
         * @todo fix dependency
         */
        if ($_DriverResult instanceof \mysqli_result) {
            $_DriverResult->free();
        }

        return $_Result;
    }

    /**
     * Старт транзакции
     *
     * @return bool
     *
     * @since 0.4.0
     */
    public function BeginTransaction() {
        $this->Driver->DriverTransaction(null);

        return true;
    }

    /**
     * Подтверждение транзакции
     *
     * @return bool
     *
     * @since 0.4.0
     */
    public function CommitTransaction() {
        $this->Driver->DriverCommit();

        return true;
    }

    /**
     * Откат транзакции
     *
     * @return bool
     *
     * @since 0.4.0
     */
    public function RollbackTransaction() {
        $this->Driver->DriverRollback();

        return true;
    }



}
