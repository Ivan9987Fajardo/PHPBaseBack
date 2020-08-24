<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Auth extends ResourceController
{

    public function __construct() {
        helper('encryption');
        
    }


    public function Login()
	{
        helper('authentication');
        $data = receiveData($this,true);
        if(!is_object($data)) return $this -> fail($data);
        $userId = isset($data->userId)?$data->userId:'';
        $password = isset($data->password)?$data->password:'';
        $user = verifyUserGetPassword($userId,$password);
        if(!$user) {
            return $this -> failUnauthorized();
        }
        if(!verifyPassword($password,$user->Password)) {
            return $this -> failUnauthorized();
        }
        
        $data = [
                'name' => $user->Name,
                'userId'=> $user->UserId
        ];
       
        return sendData($this,200,$data);
		
    }



    private function checkRequest(){
        return $this->request->getMethod(true);
    }

    private function verifyRequest(string $requestType ){
        $method = $this -> checkRequest();
        if($method == strtoupper(trim($requestType))){
            return true;
        }
        return false;
    }



}
