<?php namespace App\Models\Admin\Common;

use CodeIgniter\Model;

/************************************************************************
    дефолтные методы для получения перечня записей в таблицах
************************************************************************/

class MMbd extends MModel
{


    function __construct() {
        parent::__construct();
    }

    public function default_default(){
        return $this->meta;
    }
}
