<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Auth extends ResourceController
{
    public function __construct() {
        helper('encryption');
        
    }


    public function Login()
	{
        $data = (array)$this->request->getJson();
        helper('authentication');
        
        $data = decryptData($data,true);
        if(!isset($data['data'])) return $this->fail($data);
        print_r($data['data']);
        $userId = isset($data['data']->userId)?$data['data']->userId:'';
        $password = isset($data['data']->password)?$data['data']->password:'';
        
        $userPassword = verifyUserGetPassword($userId,$password);
        if(!$userPassword) {
            return $this -> failUnauthorized();
        }
        if(!verifyPassword($password,$userPassword)) {
            // print_r('Nice');
            return $this -> failUnauthorized();
        }
        
        $returnData = [
            'newHash' => $data['newHash']
        ];
        // print_r($returnData);exit();
        // return $returnData;
        $this->respond($returnData,200,'NICE');
		
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
