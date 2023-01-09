<?php

/**
 * Project:  Coral
 * File:     Transform.php
 * Created:  2015-02-01
 *
 * PHP version 5
 *
 * @category DB
 * @author   Ukrop
 *
 * @version  SVN: $Id: Transform.php 229 2018-02-04 16:38:00Z Ukrop $
 * @link     $URL: https://ukrop-note:8443/svn/coral/trunk/Database/plugins/Transform.php $
 */

namespace Coral\Database\plugins;

/**
 * Cache - плагин преобразования результата выборки из плоского массива в дерево.
 * @author Ukrop
 * @version 0.2.2
 */
class Transform extends AbstractPlugin {

    public function __construct() {

    }

    public function Attaches() {
        return [
            'AfterQuery' => function (&$_DataSet, $Query, $Keys) {
                $_DataSet = self::Map($_DataSet, $Keys);
            }
        ];
    }

    /**
     * Трансформация массива в HASH-Map
     * @param array $_Array Плоский массив
     * @param array $_Keys Путь
     * @return array Hash-map
     *
     * @example
     * $_Path = ['sh_Provider' => 'sm_ID']
     * $_Path = ['sh_Provider' => ['sm_Date' => 'sm_ID']]
     */
    public static function Map(array $_Array, array $_Keys) {
        $_Result = [];
        foreach ($_Array as $_Row) {
            $current = & $_Result;
            foreach ($_Keys as $ArrayKey) {
                $key = $_Row[$ArrayKey];
//                if (false) // remove ARRAY_KEY* field from result row
//                    unset($_Row[$ArrayKey]);
                if ($key !== null) {
                    $current = & $current[$key];
                } else {
                    // IF ARRAY_KEY field === null, use array auto-indices.
                    $tmp = [];
                    $current[] = & $tmp;
                    $current = & $tmp;
                    unset($tmp);
                }
            }
            $current = $_Row; // save the row in last dimension
        }
        return $_Result;
    }

    /**
     * RollUp - свертка
     * @example
     *
     *  -- TRANSFORM-RollUp: sm_Group => user_group.name, user_group.access
     *  SELECT user.id, user.name, user_group.name, user_group.access
     *   FROM user LEFT JOIN user_groups ON user.group = group.id WHERE user.id=1
     *
     * result = [
     *      id   --user.id
     *      name -- user.name
     *      sm_Group = [
     *          name    -- user_group.name
     *          access  -- user_group.access
     *      ]
     *  ];
     */
    public static function RollUp(array $_Array, array $_Keys) {
        $_RKey = array_shift($_Keys);

        foreach ($_Array as &$_Row) {
            $_Row[$_RKey] = [];
            foreach ($_Keys as $_KName) {
                $_Row[$_RKey][$_KName] = $_Row[$_KName];
                unset($_Row[$_KName]);
            }
        }
    }

    /**
     * Построение дерева из массива
     * @param array $_Array Плоский массив
     * @param array [primary-key, parent-key] Массив [родитель => потомок]
     * @return array Tree
     *
     * @example
     * $_Path = ['sh_Provider' => 'sm_ID']
     *
     *
     * Converts rowset to the forest.
     *
     * @param array $_Array Two-dimensional array of resulting rows.
     * @param string $_Keys = ['id', 'parent-key'] Name of ID field.
     * @param string $_ParentKey Name of PARENT_ID field.
     * @return array Transformed array (tree).
     */
    public static function Tree(array $_Array, array $_Keys, $_ChildKey = 'childNodes') {
        $_Key = $_Keys[0];
        $_ParentKey = $_Keys[1];
        $children = []; // children of each ID
        $ids = [];
        // Collect who are children of whom.
        foreach ($_Array as &$row) {
//            $row = & $rows[$i];
            $id = $row[$_Key];
            if ($id === null) {
                // Rows without an ID are totally invalid and makes the result tree to
                // be empty (because PARENT_ID = null means "a root of the tree"). So
                // skip them totally.
                continue;
            }
            $pid = $row[$_ParentKey];
            if ($id == $pid)
                $pid = null;
            $children[$pid][$id] = & $row;
            if (!isset($children[$id]))
                $children[$id] = [];
            $row[$_ChildKey] = & $children[$id];
            $ids[$id] = true;
        }
        // Root elements are elements with non-found PIDs.
        $forest = [];
        foreach ($_Array as &$row) {
//            $row = & $rows[$i];
            $id = $row[$_Key];
            $pid = $row[$_ParentKey];
            if ($pid == $id)
                $pid = null;
            if (!isset($ids[$pid])) {
                $forest[$row[$_Key]] = & $row;
            }
//            unset($row[$_Key]);
//            unset($row[$_ParentKey]);
        }
        return $forest;
    }

    /**
     * Псевдоним
     * @return string
     * @since 0.2.2
     */
    public function Alias() {
        return 'TRANSFORM-MAP';
    }

}
