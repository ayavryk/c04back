<?php namespace App\Controllers\Admin04;

use CodeIgniter\Controller;
 
class AdminBase extends Controller
{
 
	// не забудь снять DEBUG!!!
	protected $DEBUG = true;

    function __construct(...$params) {
        parent::__construct(...$params);
		session_start();

		if (isset($_GET['whois'])) {
				$user = false;
				if ($this->DEBUG) {$user = '@DEBUG@';}
				if (isset($_SESSION['user'])) {$user = $_SESSION['user'];}
				if ($user) {$this->jsonOut(array('user'=>$user));}
		}

		if (!$this->DEBUG && !isset($_SESSION['logged'])) {
			$this->jsonOut(array('error'=>'auth'));
		}       
    }

	public function jsonOut($result)
	{	
		header("HTTP/1.1 200 OK");
		header('Content-Type: application/json');
		echo json_encode($result,  JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);	
		die;
	}

}
