<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Inventory extends ResourceController
{
    public function __construct() {
        helper('encryption');
    }


    public function getInventory(){
        $data = receiveData($this);
        if(!is_object($data)) return $this -> fail($data);
        $data = (object)[
            'TEST' => 'Success'
        ];
        return sendData($this,200,$data,'NICE');

    }





}
