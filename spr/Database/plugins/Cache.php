<?php

/**
 * Project:  Coral
 * File:     Cache.php
 * Created:  2015-02-01
 *
 * PHP version 5
 *
 * @category DB
 * @author   Ukrop
 *
 * @version  SVN: $Id: Cache.php 229 2018-02-04 16:38:00Z Ukrop $
 * @link     $URL: https://ukrop-note:8443/svn/coral/trunk/Database/plugins/Cache.php $
 */

namespace Coral\Database\plugins;

/**
 * Cache - плагин кеширования результата выборки
 * @author Ukrop
 * @version 0.2.2
 */
final class Cache extends AbstractPlugin {

    /**
     * @var \Coral\Cache\ICache Класс кеширования
     */
    private $Cacher;

    /**
     * @var string Хеш-ключ для результат
     */
    private $CacheID;

    /**
     * @var int Время жизни кеша в секундах
     */
    private $CacheTTL;

    /**
     * @var array Данные кеша
     */
    private $CacheData;

    /**
     * Конструктор
     * @param array $_Settings Параметры
     */
    public function __construct(array $_Settings) {
        $this->Cacher = new $_Settings['Cache']['Class']();
        $this->Cacher->Setup($_Settings['Cache']);
    }

    /**
     * События базы данных, для которых вызывается данный плагин
     * @return array
     */
    public function Attaches() {
        return [
            /**
             * Перед запросом проверить, существует ли кешированная версия результата запроса
             * @param string $_Query Запрос
             */
            'BeforeQuery' => function(string $_Query, array $Args) {
                $this->CacheID = md5($_Query);
                $this->CacheTTL = $Args[0];

                $this->CacheData = $this->Cacher->Fetch($this->CacheID);
                return !empty($this->CacheData);
            },
            /**
             * После запроса кешированнать версия результата запроса
             * @param string $_Result Результат запроса
             */
            'AfterQuery' => function (&$_Result, string $_Query, array $Args) {
                if (!empty($this->CacheData)) {
                    $_Result = $this->CacheData;
                } else {
                    $this->Cacher->Store($this->CacheID, $_Result, $this->CacheTTL);
                }

                $this->CacheID = '';
                $this->CacheTTL = 0;
                $this->CacheData = [];
            }
        ];
    }

    /**
     * Псевдоним
     * @return string
     * @since 0.2.2
     */
    public function Alias() {
        return 'CACHE';
    }

}
