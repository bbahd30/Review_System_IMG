<?php
require_once '../PHP/member.php';

class student extends member
{
    private $table = "STUDENTS";
    private $type = 0;
    public function getTable()
    {
        return $this->table;
    }
    public function getType()
    {
        return $this->type;
    }
    public function __construct()
    {
        parent::__construct();
    }
    
}