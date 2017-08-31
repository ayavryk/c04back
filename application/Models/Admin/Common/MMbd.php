<?php namespace App\Models\Admin\Common;

use CodeIgniter\Model;

/************************************************************************
    дефолтные методы для получения перечня записей в таблицах
************************************************************************/

class MMbd extends MMcommon
{


    function __construct() {
        parent::__construct();
    }

    public function default_default(){
        return $this->meta;
    }
}
