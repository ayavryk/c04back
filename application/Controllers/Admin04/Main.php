<?php namespace App\Controllers\Admin04;

use CodeIgniter\Controller;
 
class Main extends Controller
{
	protected $result = array();
	protected $startSegment = 3;
	protected $data; // 0 - method 1-table 2-id

	public function index()
	{

		//var_dump($_POST);
        	//var_dump($_GET);
		//phpinfo();
		$controller = preg_replace('/[^a-z]/', '',$_GET['controller']); 
		$method = preg_replace('/[^a-z]/', '',$_GET['method']);
		// если есть переопределенные методы для работы с таблицей $method;
		$modelName =  '\App\Models\Admin\Common\MM'.$controller;
		if (file_exists(APPPATH."Models/Admin/M$controller.php")) {
		    $modelName =  '\App\Models\Admin\M'.$controller;
		}

		$model = new $modelName();
		$result = $model->get();
		$this->out($result);	

					
	}


	// dsdjl json
	public function out($result)
	{	
		header("HTTP/1.1 200 OK");
		header('Content-Type: application/json');
		// если сервер настроен на Allow, строку нужно закомментировать!!
		header('Access-Control-Allow-Origin: *'); 
		echo json_encode($result,  JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);	
		die;
	}
}
