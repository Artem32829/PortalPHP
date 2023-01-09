<?php

/**
 * Project:  Coral
 * File:     AbstractPlugin.php
 * Created:  2015-02-01
 *
 * PHP version 5
 *
 * @category DB
 * @author   Ukrop
 *
 * @version  SVN: $Id: AbstractPlugin.php 229 2018-02-04 16:38:00Z Ukrop $
 * @link     $URL: https://ukrop-note:8443/svn/coral/trunk/Database/plugins/AbstractPlugin.php $
 */

namespace Coral\Database\plugins;

/**
 * Базовый плагин
 * @author Ukrop
 * @version 0.2.2
 */
abstract class AbstractPlugin {

    /**
     * События, на которые реагирует плагин
     * @return array Массив callable
     */
    abstract public function Attaches();

    /**
     * Получить псевдоним плагина
     * @return string Псевдоним
     * @since 0.2.2
     */
    abstract public function Alias();
}
