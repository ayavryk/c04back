<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Update extends Controller
{

    function __construct(...$params) {

        parent::__construct(...$params);
        //$this->db = \Config\Database::connect();
		$modelName =  '\App\Models\MCommon';
		$this->model = new $modelName();

    }

	public function index()
	{
		return view('welcome_message');
	}

	//--------------------------------------------------------------------
    public function step1(){
        // перед операцией добавить поле
        //ALTER TABLE `author` ADD `url` VARCHAR(255) NOT NULL AFTER `public`;
        $res = $this->model->sql("SELECT * FROM author_link_rel");
        foreach ($res as $k=>$v) {
            $id = $v['id_text'];
            $url = $v['url'];
            $query = "UPDATE author SET url='http://$url' WHERE id=$id";
            $this->model->sql($query);
            echo "$k ";
        } 
        echo "<h1>OK</h1>";
    }
}

/* todo 
1. проверить переходы с тех полей где есть ссылка на hpsy
*/