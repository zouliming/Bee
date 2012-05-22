<?php

require(dirname(__FILE__).'/BeeBase.php');

class Bee extends BeeBase
{
    public $validator;
    public $db;
    public static function va(){
        if(!$this->validator){
            $this->validator = new BValidator();
        }
        return $this->validator;
    }
    public static function dba(){
        if(!$this->db){
            $this->db = new BMysql();
        }
        return $this->db;
    }
}
?>
