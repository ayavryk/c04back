<?php namespace App\Models;

use CodeIgniter\Model;

// Конструктор моделей админки. Обработка запросов и

class MCommon extends Model
{
   
    function __construct() {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

/*****************************************************************************************************
*          Общие методы для  всех моделей 
*****************************************************************************************************/

    // преобразование выхода SQL в массив
    public function sql($query) {

        $res = $this->db->query($query);
        if (!$res) {
            echo "<h4>bad sql query: ".$query."</h4>";
            die();
        }

        $query = substr(trim(strtoupper($query)),0,8);
        $rd = array('SELECT','SHOW');

        foreach ($rd as $key=>$value) {
            if (strpos($query,$value) === 0) {
                return $res->getResultArray();  
            }
        }           
    }

	public function lastId($table)	{
           return $this->sql("SELECT LAST_INSERT_ID() AS N FROM $table")[0]['N'];
	}

    public function clearName($value) {
        return preg_replace('/[^@a-zA-Z0-9\-\_\:\,\.]/', '',$value); 
    }

}
