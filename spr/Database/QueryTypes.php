<?php

/**
 * Project:  Coral
 * File:     QueryTypes.php
 * Created:  2017-12-30
 *
 * PHP version 7.2
 *
 * @category DB
 * @author   Ukrop
 *
 * @version  SVN: $Id: QueryTypes.php 229 2018-02-04 16:38:00Z Ukrop $
 * @link     $URL: https://ukrop-note:8443/svn/coral/trunk/Database/QueryTypes.php $
 */

namespace Coral\Database;

/**
 * Типы запросов
 * @author Ukrop
 * @version 0.2.1
 */
abstract class QueryTypes {

    const SELECT = 0;
    const INSERT = 1;
    const UPDATE = 2;
    const DELETE = 3;
    const QUERY = 5;
    const OTHER = 100;

}
