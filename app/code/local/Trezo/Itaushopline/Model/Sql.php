<?php
/**
* Trezo
*
* NOTICE OF LICENSE
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade Magento to newer
* versions in the future. If you wish to customize Magento for your
* needs please refer to http://www.trezo.com.br for more information.
*
* @category Trezo
* @package Trezo_Itaushopline
*
* @copyright Copyright (c) 2017 Trezo. (http://www.trezo.com.br)
*
* @author Trezo Core Team <contato@trezo.com.br>
*/
class Trezo_Itaushopline_Model_Sql
{
    public function getCoreResource()
    {
        return Mage::getSingleton('core/resource');
    }

    public function select($tableName, $fields, $conditions)
    {
        $resource = $this->getCoreResource();
        $read = $resource->getConnection('core_read');
        $table = $resource->getTableName($tableName);

        $select = $read->select()
        ->from($table, $fields)
        ->where($conditions);
        $rows = $read->fetchAll($select);

        return $rows;
    }

    public function update($tableName, $fields, $conditions)
    {
        $resource = $this->getCoreResource();
        $write = $resource->getConnection('core_write');
        $table = $resource->getTableName($tableName);

        $write->beginTransaction();
        try {
            $result = $write->update($table, $fields, $conditions);
            $write->commit();
        } catch (Exception $e) {
            $result = $e->getMessage();
            $write->rollback();
        }

        return $result;
    }

    public function insert($tableName, $fields)
    {
        $resource = $this->getCoreResource();
        $write = $resource->getConnection('core_write');
        $table = $resource->getTableName($tableName);

        $write->beginTransaction();
        try {
            $result = $write->insert($table, $fields);
            $write->commit();
        } catch (Exception $e) {
            $result = $e->getMessage();
            $write->rollback();
        }

        return $result;
    }

    public function delete($tableName, $conditions)
    {
        $resource = $this->getCoreResource();
        $write = $resource->getConnection('core_write');
        $table = $resource->getTableName($tableName);
        $conds = array($write->quoteInto($conditions));

        $write->beginTransaction();
        try {
            $result = $write->delete($table, $conds);
            $write->commit();
        } catch (Exception $e) {
            $result = $e->getMessage();
            $write->rollback();
        }

        return $result;
    }
}
