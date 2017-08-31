<?php namespace App\Models\Admin\Common;

use CodeIgniter\Model;

/************************************************************************
    дефолтные методы для получения перечня записей в таблицах
************************************************************************/

class MMCommand extends MMcommon
{
    public $data = array();
    public $ids = array();
    public $set = '';

    function __construct() {
        parent::__construct();
      	
        $this->data = $this->jsonString2Array($_GET['data']);
        if (!isset($this->data['ids']) or count($this->data['ids']) == 0) {
            return array('message'=>'Не заданы элементы для групповой операции');
        }
        $this->ids = $this->data['ids'];
        for ($i=0; $i<count($this->ids); $i++) {
            $this->ids[$i] = intval($this->ids[$i]);
        }
        $this->set = implode(',', $this->data['ids']);
    }

    public function default_default(){
        // переопределяю метод с названия_таблицы на присланный код операции;
        $method = 'cmd_'.$this->data['code'];
        if (!method_exists($this,$method)) {
            return array('message'=>"not found method $method");
        }
        return $this->$method();
    }

    // Удаление записей
    public function cmd_delete(){
        $this->sql("DELETE from ".$this->table." WHERE id in (".$this->set.")");
        return array('command'=>'reload','notify'=>'Удалено');
    }

    // инверсия поля (опбликовать/снять)
    public function cmd_invert(){
	return $this->cmd_set(1);
    }

    // изменение записи по одному полю
    public function cmd_set($invert=0){
        $field = $this->db->escapeString($this->data['field']);
        if (!isset($this->meta[$this->table]['fields'][$field])) {
            return array('message'=>"not found field=$field in table ".$this->table);
        }
        if (gettype($field) !== 'string') {return $field;}
	if ($invert) {
		$set = "$field=1-$field";
	} else {
		$set = "$field='".$this->db->escapeString($this->data['value'])."'";
	}
        $query = "UPDATE ".$this->table." SET $set WHERE id in (".$this->set.")";
        $this->sql($query);
        return array('command'=>'reload','notify'=>'Изменения внесены');
    }

    // Изменение привязок (тегов, авторов и т.п.)
    public function cmd_rel(){
        if (!isset($this->data['data'])) {
            return array('message'=>" Error. Not found data form ".$this->table);
        }    
        $data =  $this->data['data']; 
        $link = false;
        $table = $this->db->escapeString($this->data['field']);
        // проверка наличия таблицы связей
        foreach ($this->meta[$this->table]['rel'] as $key=>$value) {
            if ($value['table'] === $this->data['field']) {
               $link =  $value['link'];
               break;
            }
        }
        if (!$link) {
            return array('message'=>" Error. Not found link table from ".$this->table." to $table");
        }
        
        // удаление всех привязок
        if (isset($data['all'])) {

           $query = "DELETE FROM $link  WHERE id_text in (".$this->set.")";
           $this->sql($query);
        }

        // удаление привязок совпадающих с присланной
        if (isset($data['del'])) {
           $name = $this->db->escapeString($data['del']); 
           $query = "SELECT id FROM $table WHERE name = '$name'";
           $set = $this->sql($query);
           if (count($set) != 0) {
               foreach ($this->ids as $key=>$value) {
                    $this->query("DELETE FROM $link WHERE id_text=$value AND id=".$set[0]['id']); 
               } 
           } 
        }

        // добавление новых привязок
        if (isset($data['add'])) {
           $name = $this->db->escapeString($data['add']); 
           $query = "SELECT id FROM $table WHERE name = '$name'";
           $id = 0;
           $set = $this->sql($query);
           if (count($set) != 0) {
               $id = $set[0]['id'];
           } else {
              $this->query("INSERT INTO $table SET name='$name'");
              $id = $this->lastId($table);
           }
           if ($id === 0) {
               return array('message'=>" Error. ХЗ не могу найти и вставить тег ");
           }
           foreach ($this->ids as $key=>$value) {
              $this->query("INSERT INTO $link SET k=$key,id=$id,id_text=$value"); 
           }
        }

        return array('command'=>'reload','notify'=>'Изменения внесены');
    }



/*
    public function getQuery()
    {
		$this->QUERY = array();
        if (isset($_GET['query'])) {
            $json = json_decode($_GET['query']);
			foreach ($json as $item=>$value) {
			 $this->QUERY[$item] = $value;
			}
        }

		if (!isset($this->QUERY['page'])) {$this->QUERY['page'] = 0;}
		$this->QUERY['page'] = intval($this->QUERY['page']);
		if (!isset($this->QUERY['query'])) {$this->QUERY['query'] = '';}    
    }
*/




  
}
