<?php namespace App\Models\Admin\Common;

use CodeIgniter\Model;

// Конструктор моделей админки. Обработка запросов и

class MModel extends MMlib
{
    protected $table = '';
    protected $meta = array();
    protected $perPage = 25;
    protected $GET = array();
    public $POST = array();

    function __construct() {
        parent::__construct();
        $this->db = \Config\Database::connect();
        $this->buildBdData();  
        $this->clearGetName();
        $this->getParapms();   
        $this->POST = $this->getPost();           
    }

/*****************************************************************************************************
*          Общие методы для универсальных моделей 
*****************************************************************************************************/
	
    // составление списка всех таблиц с полями и отношениями
    public  function buildBdData() {
       $tableList = $this->sql('SHOW TABLE STATUS');
       foreach($tableList as $key=>$value) {
            $name = $value['Name'];
            $this->meta[$name] = array('rel'=>array(),'fields' => $this->buildTableData($name));
       }
       $this->buildBdRel();
    }

    // составление списка полей таблицы
    public  function buildTableData($name) {   
        $res = array();
        $fields = $this->sql("SHOW COLUMNS FROM $name");
        //var_dump($fields);
        foreach($fields as $key=>$value) {
            $type =  explode('(',$value['Type'])[0];
            $notext=  strpos($type,'text') === false;
            $res[$value['Field']] = array('type'=>$type,'simple'=>$notext);
        }
        return $res;
    }

    // составление списка отношений между таблицами
    public  function buildBdRel() {  
        foreach($this->meta as $key=>$value) {
            $rel = explode('_',$key);
            if (count($rel) === 3 and $rel[2] === 'rel') {
                $this->meta[$rel[0]]['rel'][] = 
                array('link'=>$key,'table'=> $rel[1]);
            }                         
        }
    }

 	// вытаскиваем из параметров таблицу
    public function getParapms()
    {
 
        if (!isset($this->GET['method'])) {
            echo "NOT FOUND table in GET PARAMS";
            die();
        }       
        $this->table = $this->clearName($this->GET['method']);   
        if (!isset($this->meta[$this->table])) {
            echo "NOT FOUND ".$this->table." table in META";
            die();
        }  
    }

    // определение вызываемого метода если он существет или дефолтного
    public function get() {
        $method = 'default_'.$this->table;
        if (!method_exists($this,$method)) {
            if ($this->method === 'users') {
                echo "USER DEFAULT FORBIDDEN!";
                die();           
            }
            $method = 'default_default';
        }
        if (!method_exists($this,$method)) {
           echo "NOT FOUND $method";
           die(); 
        }	
        return $this->$method();        
    } 	

    // чистим имена полей запроса
    public function clearGetName(){
        $this->GET = array();
        foreach ($_GET as $key=>$value) {

            $index =  $this->clearName($key);
            if ($key != 'query') {$value = $this->clearName($value);}
            $this->GET[$index] = $value;
        }
    }

    // составление дефолтного списка полей для дачи таблиц на фронт
    // выодятся все поля кроме тех кто имеет тип ~TEXT
    // фронт выводит те которые указаны в config
    public function setFieldsList($table = '',$rel = false) {

        if ($table === '') {
            $table = $this->table;
        }
        $fields = $this->meta[$table]['fields'];
        $set = [];
        foreach ($fields as $key=>$field) {
            $ins = $key;
            if ($rel === false OR strpos($ins,'id') !== 0) {
                if ($field['simple']) {
                    $set[] = $ins;
                }
            }  

        }
        return implode(',',$set);
    } 

    public function getRelList($id,$table = '') {
      
        if ($table === '') {
            $table = $this->table;
        }        
        $id = intval($id);
        $res = array();

        if (!isset($this->meta[$table]['rel'])) {
            return $res;
        }
        foreach($this->meta[$table]['rel'] as $key=>$value) {
            $relTable = $value['table'];
            $linkTable = $value['link'];
            //TODO!!! не может быть таблицы связей без привязываемой таблицы 
            // при проверке нобходимо выдавать сообщение об ошибке
            if (isset($this->meta[$linkTable]) AND isset($this->meta[$relTable]) ) {
                $fields = $this->setFieldsList($relTable,true);
                $sql = "SELECT $fields,$relTable.id  as id FROM $relTable,$linkTable WHERE id_text = $id AND $relTable.id = $linkTable.id ORDER by k";
                $res[$linkTable] = $this->sql($sql);
            }
        } 

        return $res;
    }

}
