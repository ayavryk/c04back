<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Auth extends Controller
{
    function __construct(...$params) {
        parent::__construct(...$params);
		$modelName =  '\App\Models\MCommon';
		$this->model = new $modelName();
    }

	public function index()
	{
		session_start();
		if (isset($_SESSION['logged'])) {
			return view('auth/authorized');
		}	else {
			return view('auth/login');
		} 
	}

	public function login()
	{
		
		if (!isset($_POST['l'])) {
			$this->index();
			return;
		} 
		$L = $this->model->clearName($_POST['l']);
		$P = $this->model->clearName(md5($_POST['p']));
		$query = "SELECT username,regmail FROM users WHERE regmail='$L'";
	    $res = $this->model->sql($query);
	    if (count($res)) {			
			session_start();
			$_SESSION['logged'] = true;
			$_SESSION['user']=  $res[0]['username']; 
			$url = '/auth';
			if (isset($_SESSION['return'])) {
				$url = $_SESSION['return'];
				unset($_SESSION['return']);
			}	
			header("Location: $url"); 
			die();
		}
	
	}

	public function logout(){
		session_start();
		if (isset($_SESSION['logged'])) {
			unset($_SESSION['logged']);
		}
		header("Location: /"); 
		die();
	}
}