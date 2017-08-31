<?php namespace App\Models\Admin;

use CodeIgniter\Model;
class MAdminBase extends Model
{
    public $db;  
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
            echo "bad sql query: ".$query;
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
        return preg_replace('/[^a-zA-Z0-9\-\_\:\,]/', '',$value); 
    }
	
    public function jsonString2Array($json) {
        $res = array();
        $json = json_decode($json,true);
			foreach ($json as $item=>$value) {
			$res[$item] = $value;
		}
        return $res; 
    }

    public function getPost(){
        if (!(isset($_POST) && isset($_POST['json']))) {
            return;
        } 
        return  $this->jsonString2Array($_POST['json']);
    }

}
