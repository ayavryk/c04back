<?php namespace App\Models\Admin\Common;

use CodeIgniter\Model;

class MMsave extends MModel
{

    // admin/suggest?method=author&query=',

    public $id;
    
    function __construct() {
        parent::__construct();  
        $this->id = intval($this->GET['id']);
        $this->table = $this->clearName($this->GET['method']);
    }



    public function default_default()
    {   
        $this->mainSave();
        $this->relSave();
        return array('command'=>'save');
    }

    public function mainSave(){
        $set = [];
        foreach ($this->POST as $key=>$value) {
	    $type =  gettype($value); 	
            if (!($type == "object" or $type == "NULL" or $type == "resource" ) and strpos($key,'_rel') === false and $key !== 'id') {
                $value = $this->db->escapeString($value);
		if ($value != null)
                if ($key === 'edited') {
                    $set[] = 'edited = NOW()';
                }   else {
                    $set[] = "$key='$value'";
                }
            }
        }
        $set = $this->table." SET ".implode(',',$set)." ";
	 
        if ($this->id == 0) {
            if ($this->id == 0 and isset($this->meta[$this->table]['fields']['created'])) {
		$set = $set.",created = NOW(),edited=NOW()";
	    }
            $this->sql("INSERT INTO $set");
	    $this->id = $this->lastId($this->table);
        }   else {
            $this->sql("UPDATE $set WHERE id=".$this->id);
        }
    }
               

             
    // это только для привязок типа тегов по имени тега
    public function relSave(){
        $id = $this->id;
        foreach ($this->meta[$this->table]['rel'] as $key => $rel) {
         // удаяляем старые привязки   
            $linkTable = $rel['link'];
            $this->sql("DELETE FROM $linkTable WHERE id_text=$id");
            // выставляем новые привязки 
            if (isset($this->POST[$rel['link']])) {
                $names = $this->POST[$rel['link']];
                for ($i=0;$i<count($names);$i++) {
                    $name =  $this->db->escapeString($names[$i]['name']);
                    $relTable = $rel['table'];
                    // смотрим существует в таблице привязки запись с таким именем
                    $old = $this->sql("SELECT id FROM $relTable WHERE name='$name'");
                    if (count($old) != 0) {
                        $insertId = $old[0]['id'];
                    }   else {
                    	$this->sql("INSERT INTO $relTable SET name='$name'");
			$insertId = $this->lastId($relTable);
                    }
                    $this->sql("INSERT into $linkTable SET id_text=$id,k=$i,id=$insertId");
                } 
            }
        }
    }


}
