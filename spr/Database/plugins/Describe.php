<?php

/**
 * Project:  Coral
 * File:     Describe.php
 * Created:  2015-02-01
 *
 * PHP version 5
 *
 * @category DB
 * @author   Ukrop
 *
 * @version  SVN: $Id: Describe.php 229 2018-02-04 16:38:00Z Ukrop $
 * @link     $URL: https://ukrop-note:8443/svn/coral/trunk/Database/plugins/Describe.php $
 */

namespace Coral\Database\plugins;

/**
 * Describe - полностью описать поля и названия таблиц из запроса.
 * @author Ukrop
 * @version 0.2.2
 */
class Describe extends AbstractPlugin {

    public function __construct() {

    }

    public function Attaches() {
        return [
            'AfterQuery' => 'Field'
        ];
    }

    /**
     * @link http://se2.php.net/manual/ru/function.mysql-fetch-field.php#108848
     */
    public function Field() {

    }

    /**
     * Псевдоним
     * @return string
     * @since 0.2.2
     */
    public function Alias() {
        return 'DESCRIBE';
    }

}
