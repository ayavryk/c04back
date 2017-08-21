<?php namespace App\Models\Admin;
 
use CodeIgniter\Model;

// Конструктор моделей админки. Обработка запросов и

class Msave extends Common\MMsave
{  
    function __construct() {
        parent::__construct();
    }

    function default_text(){
        $authors = [];
        foreach ($this->POST["text_author_rel"] as $key=>$value) {
            if ($value['name']) {
              $authors[] = $value['name'];
            }
        }
        $authors = implode (', ' , $authors);
        $this->POST['name'] = mb_substr($authors.' // '.$this->POST['text'],0,80);
        return $this->default_default();
    }
}

