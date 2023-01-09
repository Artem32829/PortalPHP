<?php

/**
 * Project:  Coral
 * File:     Date.php
 * Created:  2015-02-01
 *
 * PHP version 5
 *
 * @category DB
 * @author   Ukrop
 *
 * @version  SVN: $Id: Date.php 229 2018-02-04 16:38:00Z Ukrop $
 * @link     $URL: https://ukrop-note:8443/svn/coral/trunk/Database/plugins/Date.php $
 */

namespace Coral\Database\plugins;

/**
 * Date - плагин для преобразования даты
 * @author Ukrop
 * @version 0.2.2
 */
class Date extends AbstractPlugin {

    public function __construct() {

    }

    public function Attaches() {
        return [
            'AfterQuery' => function (&$_Result, string $_Query, array $Args) {
                $_Field = array_shift($_Args);
                $_Format = array_pop($_Args);
                foreach ($_Result as &$_Row) {
                    $_Row[$_Field] = strftime($_Format, $_Row[$_Field]);
                }
            }
        ];
    }

    /**
     * Псевдоним
     * @return string
     * @since 0.2.2
     */
    public function Alias() {
        return 'DATE';
    }

}
