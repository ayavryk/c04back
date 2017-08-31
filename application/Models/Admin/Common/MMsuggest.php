<?php namespace App\Models\Admin\Common;

use CodeIgniter\Model;

class MMsuggest extends MMcommon
{

    // admin/suggest?method=author&query=',
    
    function __construct() {
        parent::__construct();       
        $this->default();   
    }

    public function default_default()
    {
        $field = 'name'; 
        if (isset($_GET['field']))  {
            $field = $this->$GET['field'];
        }
        $where = $this->db->escapeString("%".$this->GET['query']."%");
        $query = "SELECT $field FROM ".$this->table." WHERE $field LIKE '$where' LIMIT 25";
        $sqlRes = $this->sql($query);
        $res = array();
        foreach($sqlRes as $key=>$value) {
            $res[] = $value[$field];
        }
        return array('res'=>$res);
    }
}
