<?php namespace App\Controllers\Admin04;

use CodeIgniter\Controller; 
class Main extends AdminBase
{
	
	public function index()
	{
		$controller = preg_replace('/[^a-z]/', '',$_GET['controller']); 
		$method = preg_replace('/[^a-z]/', '',$_GET['method']);
		// если есть переопределенные методы для работы с таблицей $method;
		$modelName =  '\App\Models\Admin\Common\MM'.$controller;
		if (file_exists(APPPATH."Models/Admin/M$controller.php")) {
		    $modelName =  '\App\Models\Admin\M'.$controller;
		}
		
		$model = new $modelName();
		$result = $model->get();
		$this->jsonOut($result);						
	}


}
