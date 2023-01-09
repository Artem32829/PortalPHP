<?php

/**
 * Project:  Coral
 * File:     DBException.php
 * Created:  2015-02-01
 *
 * PHP version 5
 *
 * @category DB
 * @author   Ukrop
 *
 * @version  SVN: $Id: DBException.php 229 2018-02-04 16:38:00Z Ukrop $
 * @link     $URL: https://ukrop-note:8443/svn/coral/trunk/Database/DBException.php $
 */

namespace Coral\Database;

/**
 * DBException
 * @version 0.2.1
 */
final class DBException extends \Exception {

    protected $Query;

    /**
     * Construct the exception
     * @param $_Query
     * @param $_Code
     * @param $_Message
     */
    public final function __construct($_Query, $_Code, $_Message) {
        parent::__construct($_Message, $_Code);

        $this->Query = $_Query;

        file_put_contents('./asset/log/db/error-' . time() . '.log', print_r([$_Query, $_Code, $_Message], 1), FILE_APPEND);
    }

    final public function getQuery() {
        return $this->Query;
    }

    final public function __toString() {
        $this->message .= ' in query ' . ($this->Query);
        return parent::__toString();
    }

}
