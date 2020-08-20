<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Auth extends ResourceController
{
    private $oldHash;
    private $newHash;
    public function __construct() {
        helper('encryption');
        
    }


    public function Login()
	{
        helper('authentication');
        $data = $this->receiveData();
        if(!is_object($this->receiveData())) return $this -> fail();

        $userId = isset($data->userId)?$data->userId:'';
        $password = isset($data->password)?$data->password:'';
        $user = verifyUserGetPassword($userId,$password);
        if(!$user) {
            return $this -> failUnauthorized();
        }
        if(!verifyPassword($password,$user->Password)) {
            return $this -> failUnauthorized();
        }
        
        generateNewUserTicket($userId);
        $data = [
                'name' => $user->Name
        ];
       
        return $this->sendData(200,$data);
		
    }


    private function sendData($status_code, $data, $message=''){
        $data = (object)[
            'newHash' => $this->newHash,
            'data' => $data
        ];
        $send = [
            'data' => encryptData($data, $this->oldHash)
        ];

        return $this->respond($send,$status_code,$message);
    }

    private function receiveData(){
        $data = (array)$this->request->getJson();
        $data = decryptData($data,true);
        if(!isset($data['data'])) {
            return $this->fail($data);
        }
        $this->newHash = $data['newHash'];
        $this->oldHash = $data['oldHash'];
        $data = json_decode($data['data']);
        return $data;
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
