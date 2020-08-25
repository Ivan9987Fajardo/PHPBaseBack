<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Inventory extends ResourceController
{
    public function __construct() {
        helper('encryption');
    }


    public function getInventory(){
        helper('inventory');
        $data = receiveData($this);
        if(!is_object($data)) return $this -> fail($data);
        $data = getInventory();
        return sendData($this,200,$data,'NICE');

    }

    public function insertInventory(){
        helper('inventory');
        $data = receiveData($this);
        if(!is_object($data)) return $this -> fail($data);
       if(!isset($data->name) || !isset($data->quantity)){
        return $this -> fail('No Name or Quantity!');
       }
       if(addInventory($data->name,$data->quantity)){
            $data = getInventory();
       } else {
        return $this -> fail('Database Error');
       }

        return sendData($this,200,$data,'Success');

    }

    public function removeInventory(){
        helper('inventory');
        $data = receiveData($this);
        if(!is_object($data)) return $this -> fail($data);
       if(!isset($data->id)){
        return $this -> fail('No Id!');
       }
       if(deleteInventory($data->id)){
            $data = getInventory();
       } else {
        return $this -> fail('Database Error');
       }

        return sendData($this,200,$data,'Success');

    }

    public function updateInventory(){
        helper('inventory');
        $data = receiveData($this);
        if(!is_object($data)) return $this -> fail($data);
       if(!isset($data->urlId) || !isset($data->name) || !isset($data->quantity)){
        return $this -> fail('No Name, Quantity, or Id!');
       }
       if(putInventory($data->urlId,$data->name,$data->quantity)){
            $data = getInventory();
       } else {
        return $this -> fail('Database Error');
       }

        return sendData($this,200,$data,'Success');

    }






}
