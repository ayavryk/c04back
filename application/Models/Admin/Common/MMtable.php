<?php namespace App\Models\Admin\Common;

use CodeIgniter\Model;

/************************************************************************
    дефолтные методы для получения перечня записей в таблицах
************************************************************************/

class MMtable extends MModel
{
    public  $GET = array();

    function __construct() {
        parent::__construct();
        $this->getQuery();
    }

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


    public function default_default($where = ''){
        if ($where === '' and strlen($this->QUERY['query']) > 2) {
            $where  = ' AND '.$this->like();
        }
        $fields = $this->setFieldsList();
        $from = $this->QUERY['page']*$this->perPage;
        $query = "SELECT $fields FROM ".$this->table." WHERE TRUE $where ORDER BY id DESC LIMIT  $from,".$this->perPage;
        $res['data'] = $this->sql($query);
        if (count($res['data']) === 0) {
          $res['notify'] = 'Ничего не найдено';  
        }    
        $total = $this->sql("SELECT count(*) as N FROM ".$this->table." WHERE TRUE $where")[0]['N'];
        $total = ceil(intval($total) / $this->perPage);
        $res['page'] = array('total'=>$total,'current'=>$this->QUERY['page']);
        $res['filter'] = $this->QUERY;
        return $res;
    }

    public function like($name = "name"){
        $query = $this->QUERY['query'];
        if (strlen($query) < 2) {
            return ' ';
        }
        return  "  $name LIKE '".$this->db->escapeString("%".$this->QUERY['query']."%")."'";
    }
  
}
