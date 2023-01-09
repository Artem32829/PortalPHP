<?php

/**
 * Project:  Coral
 * File:     Container.php
 * Created:  2015-02-01
 *
 * PHP version 5
 *
 * @category DB
 * @author   Ukrop
 *
 * @version  SVN: $Id: Container.php 229 2018-02-04 16:38:00Z Ukrop $
 * @link     $URL: https://ukrop-note:8443/svn/coral/trunk/Database/plugins/Container.php $
 */

namespace Coral\Database\plugins;

/**
 * Container - плагин для преобразования результата выборки из массива в объект
 * @author Ukrop
 * @version 0.2.2
 */
final class Container extends AbstractPlugin {

    private $RowSet;

    public function __construct($_RowSet) {
        $this->RowSet = $_RowSet;
    }

    /**
     * События базы данных, для которых вызывается данный плагин
     * @return array
     */
    public function Attaches() {
        return [];
    }

    /**
     * @param string $_ClassName Имя класса-контейнера для строки
     */
    public function Row($_ClassName) {
        foreach ($this->RowSet as &$Row) {
            $Row = (new $_ClassName())->FromRawArray($Row);
        }

        return $this->RowSet;
    }

    /**
     * @param string $_CollectionClassName Имя класса
     */
    public function Collection($_CollectionClassName) {
        $this->RowSet = (new $_CollectionClassName())->FromArray($this->RowSet);
        return $this->RowSet;
    }

    /**
     * @param string $_ClassName Имя класса
     */
    public function Both($_RowContainer, $_CollectionContainer) {
        foreach ($this->RowSet as &$Row) {
            $Row = (new $_RowContainer())->FromRawArray($Row);
        }
        $this->RowSet = (new $_CollectionContainer())->FromArray($this->RowSet);
        return $this->RowSet;
    }

    /**
     * Псевдоним
     * @return string
     * @since 0.2.2
     */
    public function Alias() {
        return 'CONTAINER';
    }

}
