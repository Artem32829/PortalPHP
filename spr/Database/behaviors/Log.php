<?php

/**
 * Project:  Coral
 * File:     Log.php
 * Created:  2015-02-01
 *
 * PHP version 5
 *
 * @category DB
 * @author   Ukrop
 *
 * @version  SVN: $Id: Log.php 229 2018-02-04 16:38:00Z Ukrop $
 * @link     $URL: https://ukrop-note:8443/svn/coral/trunk/Database/behaviors/Log.php $
 */

namespace Coral\Database\behaviors;

/**
 * Логирование запросов
 * @author Ukrop
 * @version 0.2.1
 */
class Log {

    protected $FileName;
    protected $File;

    public function __construct($_File) {
        $this->FileName = $_File['File'];
        $this->File = fopen($this->FileName, 'a');
    }

    public function __destruct() {
        fclose($this->File);
    }

    public function Behavior($_Param) {
        $_Content = strftime("%Y-%m-%d %H:%M:%S\n");
        $_Content .= microtime(true) . "\n";
        $_Content .= print_r($_Param, true);
        fwrite($this->File, $_Content);
    }

}
