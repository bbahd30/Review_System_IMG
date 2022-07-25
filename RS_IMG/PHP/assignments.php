<?php
require_once '../PHP/tableManager.php';

if(!isset($_SESSION))
{
    session_start();
}


class assignment extends tableManager 
{
    protected $colPair = "aID = :aID, aName = :name, aDescLink = :descLink, aDeadline = :deadline";

    public function __construct()
    {
        parent::__construct();
        $this->setIndex("aID");
        $this->setTable("ASSIGNMENTS");
        $this->setColumns("aID, aName, aDescLink, aDeadline");
        $this->setValues(":aID, :name, :descLink, :deadline");
        $this->setUpdateCondition("aID = :aID");
    }

    
    public function setArrValuesMatch($aID)
    {
        $this->arrValues = array
        (
            ":aID" => $aID
        );
    }
    public function setArrValues($aID, $aName, $aDescLink, $aDeadline)
    {
        $this->arrValues = array
        (
            ":aID" => $aID,
            ":name" => $aName,
            ":descLink" => $aDescLink,
            ":deadline" => $aDeadline
        );
    }
}