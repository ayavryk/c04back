<?php namespace App\Models\Admin\Common;

use CodeIgniter\Model;

/************************************************************************
    дефолтные методы для получения перечня записей в таблицах
************************************************************************/

//ff.dev/admin/edit?method=text&id=18

class MMedit extends MModel
{
    public $GET = array();
    public $ID = 0;

    function __construct() {
        parent::__construct();
        if (!isset($this->GET['id'])) {
            echo "В параметрах отсутствует id";
            die();
        }
        $this->ID = intval($this->GET['id']);
        $query =  $this->sql("SELECT * FROM ".$this->table." WHERE id=".$this->ID);  
        $total = $this->sql("SELECT count(*) as N FROM ".$this->table." WHERE id=".$this->ID)[0]['N'];
        if ($total != 1) {
            echo "Не найдена запись с  id=".$this->ID;
            die();         
        }
    }

    

    public function default_default(){     
        $res  = $this->sql("SELECT * FROM ".$this->table." WHERE id=".$this->ID)[0];
        $res = array_merge($res,$this->getRelList($this->ID));
        return $res;
    }


}
