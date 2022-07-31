<?php
require_once '../PHP/databaseConnection.php';

abstract class tableManager extends databaseConnection
{
    protected $index;
    protected $table;
    protected $columns;
    protected $values;
    protected $arrValues;
    protected $updateCondition;
    protected $colPair;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function getColPair()
    {
        return $this->colPair;
    }
    public function setColPair($colPair)
    {
        $this->colPair = $colPair;
    }
    public function setIndex($index)
    {
        $this->index = $index;
    }
    public function getIndex()
    {
        return $this->index;
    }
    public function getUpdateCondition()
    {
        return $this->updateCondition;
    }
    public function setUpdateCondition($updateCondition)
    {
        $this->updateCondition = $updateCondition;
    }
    public function getArrValues()
    {
        return $this->arrValues;
    }
    public function getValues()
    {
        return $this->values;
    }
    public function setValues($values)
    {
        $this->values = $values;
    }
    public function getTable()
    {
        return $this->table;
    }
    public function setTable($table)
    {
        $this->table = $table;
    }
    public function getColumns()
    {
        return $this->columns;
    }
    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    public function rowDataFetch($condition, $arrValues, $table)
    {
        $stmt = $this->conn->prepare("SELECT * FROM $table WHERE $condition");
        $stmt->execute($arrValues);
        if($stmt->rowCount() != 0)
        {
            $rowData = $stmt->fetch(PDO::FETCH_ASSOC);
            return $rowData;
        }
        else 
        {
            return 0;
        }
    }

    public function adder ($columns, $values, $arrValues, $table)
    {
        $stmt = $this->conn->prepare("INSERT INTO $table ($columns) VALUES ($values);");
        $stmt->execute($arrValues);
    }
    
    public function updater ($table, $colPair, $updateCondition, $arrValues)
    {
        $stmt = $this->conn->prepare("UPDATE $table SET $colPair WHERE $updateCondition");
        $stmt->execute($arrValues);
    }

    public function findEntriesNum ($table)
    {
        $stmt = $this->conn->prepare("SELECT * FROM $table;");
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function findLastIndex ($table, $index)
    {
        $stmt = $this->conn->prepare("SELECT MAX($index) FROM $table;");
        $stmt->execute();
        if($stmt->rowCount() != 0)
        {
            $rowData = $stmt->fetch(PDO::FETCH_ASSOC);
            return $rowData['MAX('.$index.')'];
        }
        else
        {
            return 0;
        }
    }

    public function delete($table, $condition, $arrValues)
    {
        $stmt = $this->conn->prepare("DELETE FROM $table WHERE $condition");
        $stmt->execute($arrValues);
    }
    
    public function deleteValidation ($index, $object)
    {
        $object->setArrValuesMatch($index);

        $presentLastIndex = $object->findLastIndex($object->getTable(), $object->getIndex());
    
        $object->delete($object->getTable(), $object->getUpdateCondition(), $this->getArrValues());
    
        if ($index != $presentLastIndex)
        {
            $object->updateIndex ($object->getTable(), $object->getIndex(), $index);
        }
    }



    public function updateIndex ($table, $index, $indexValue)
    {
        $stmt = $this->conn->prepare("UPDATE $table SET $index = $index - 1 WHERE  $index > $indexValue");
        $stmt->execute();
    }

    abstract public function setArrValuesMatch($aID);
    // abstract public function setArrValues($aID, $aName = NULL, $aDescLink = NULL, $aDeadline = NULL);
}