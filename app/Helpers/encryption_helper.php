<?php 

    function getInitialPass(){
        $initialPass ='bpCkhycb7fQB_@@L**kC6W@YT%WjQJUr$_vLKyEb3=H&k?KtT8AEBhZt7&#TxD%GCuANf6JH_M#Je_z%9HFHV-43@s?CTwX5BaB5q-Anf4Fb?WQ6qr2k6w9p9Lq&8jLU';
        return $initialPass;
    }
    function getEncryptionSaltIv(){
        return (object)[
            'salt' => '54d6a051b83ac21f366091ba3cf8f015567da6144f8edbf6b8797b1e65758abec73a3c5b31d393a1695d032868c91417b54695e59b7f6462c68d14d5bbcf5b49a24f08e83e86c35c885aa1cc0ccc31f700f1ace15e9ad53a277157fb937ba46935bf1596f19e01fe2f642dfc873e8c154d43ee47fb2e270684641ca75b4566a8fb839170b0a21655d37b93eafc195e0fa57425fe19847cb5a1a4540a9b7704b4974604f36ef219e4a7f647d3b595301fe1de7b2e60c39939fba7f58721bf003f5bc189ac01fae764246b56447fcbfeec4232fe3eebf75c83bf8957e29e8d1b02efd4261659b8b80f260df44bbc99dab120a1a1a6f5a3a0393bd3f569b1f1e4db',
            'iv' => '5e7123e19a07a334a99524ef5785f315'
        ];
    }

    function getDecryptionSaltIv(){
        return (object)[
            'salt' => '3a702bcbf53aa2deaf3449eb44f2ca2afff74d9bef84d50f371ee22f7e9f3f9178988da1e94052500b3c2ccb54f11351fef578b2d4907f934c5b26a0f5f9273e4c5a1b7f08000c341d177de5e70570d318833607d44369732f6467c911e8fdc93d00c990d5cb67d3102ab1929afd7d13ce85dd5be634100a966c3b92d4056dff42570a9f75f360c15699a489a0a8464218da3ca15025742201d47565869870b46b5aea8375a01aa5216266e417554f343cd5fedb2270b3e367aeb7c225178ef2c38ba1bba3844170b635ef29252cda61622049bcc9dffab984e4cbd2506c9f07254f48cc8e915c315886111956ad003c6b99648b3f856f599f759d5372314567',
            'iv' => 'd2cb77fc6bd1491294a1d226835c06ef'
        ];
    }

    function sendData($instance, $status_code, $data, $message=''){
        $data = (object)[
            'newHash' => $instance->newHash,
            'data' => $data
        ];
        $send = [
            'data' => encryptData($data, $instance->oldHash)
        ];
        return $instance->respond($send,$status_code,$message);
    }
    
    function accessProtected($obj, $prop) {
        $reflection = new ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
      }

    function receiveData($instance,$isInitial=false,$generateKey=true){
        $data = (array)accessProtected($instance,'request')->getJson();
        if(!$data)$data = (array)accessProtected($instance,'request')->getVar();
        $data = decryptData($data,$isInitial,$generateKey);
        
        if(!isset($data['data'])) {
            return $data;
        }
        $instance->newHash = $data['newHash'];
        $instance->oldHash = $data['oldHash'];
        $data = json_decode($data['data']);
        return $data;
    }

    function generateHashKey(){
        $key = password_hash(bin2hex(random_bytes(128)),PASSWORD_DEFAULT);
        return $key;
    }

    function generateNewUserTicket($user){
        helper('databaseEncrypt');
        // $initialPass ='bpCkhycb7fQB_@@L**kC6W@YT%WjQJUr$_vLKyEb3=H&k?KtT8AEBhZt7&#TxD%GCuANf6JH_M#Je_z%9HFHV-43@s?CTwX5BaB5q-Anf4Fb?WQ6qr2k6w9p9Lq&8jLU';
        $newHash = generateHashKey();
        // $user = CryptoJSAesDecrypt($initialPass,$user);
        deleteUser($user);
        insertUser($user,$newHash);

        
        return $newHash;
    }

    function deleteUserTicket($user){
        helper('databaseEncrypt');
        $initialPass = getInitialPass();
        $user = CryptoJSAesDecrypt($initialPass,$user);
        deleteUser($user);
    }


    function decryptData($data,$isInitial = false,$generateKey=true){
        helper('databaseEncrypt');
        $initialPass = getInitialPass();
        $user = CryptoJSAesDecrypt($initialPass,key($data));
        if(!$user) return 'Invalid Key';
        if(!$isInitial){
            $result = getUserHash($user);
            if(!$result) return 'No User in DB';
        } else {
            $result = (object)['Hash' =>$initialPass];
        }
        $eData = CryptoJSAesDecrypt($result->Hash,$data[key($data)]);
        if(!$eData) return 'Invalid Hash';
        $newHashKey = generateHashKey();
        if(!$isInitial && $generateKey){
            updateUser($user,$newHashKey);
        }else if (!$isInitial && !$generateKey){
            $newHashKey = $result->Hash;
        } else {
            $newHashKey = generateNewUserTicket($user);
        }
        $returnData = [
            'data' => $eData,
            'newHash' => $newHashKey,
            'oldHash' => $result->Hash
        ];
        return $returnData;

    }

    function encryptData($data, $oldHash){
        return CryptoJSAesEncrypt($oldHash,json_encode($data));
    }

    function CryptoJSAesEncrypt($passphrase, $plain_text){

        $salt = getEncryptionSaltIv() -> salt;
        $iv = getEncryptionSaltIv() -> iv;
        // $salt = openssl_random_pseudo_bytes(256);
        // $iv = openssl_random_pseudo_bytes(16);


        //on PHP7 can use random_bytes() istead openssl_random_pseudo_bytes()
        //or PHP5x see : https://github.com/paragonie/random_compat
    
        $iterations = 949;  
        $key = hash_pbkdf2("sha512", $passphrase, hex2bin($salt), $iterations, 64);
    
        $encrypted_data = openssl_encrypt($plain_text, 'aes-256-cbc', hex2bin($key), OPENSSL_RAW_DATA, hex2bin($iv));
    
        $data = base64_encode($encrypted_data);
        return $data;
    }

    function CryptoJSAesDecrypt($passphrase, $encrypted){
        $encrypted = hex2bin($encrypted);
        $encrypted = base64_decode($encrypted);
        // $salt = '54d6a051b83ac21f366091ba3cf8f015567da6144f8edbf6b8797b1e65758abec73a3c5b31d393a1695d032868c91417b54695e59b7f6462c68d14d5bbcf5b49a24f08e83e86c35c885aa1cc0ccc31f700f1ace15e9ad53a277157fb937ba46935bf1596f19e01fe2f642dfc873e8c154d43ee47fb2e270684641ca75b4566a8fb839170b0a21655d37b93eafc195e0fa57425fe19847cb5a1a4540a9b7704b4974604f36ef219e4a7f647d3b595301fe1de7b2e60c39939fba7f58721bf003f5bc189ac01fae764246b56447fcbfeec4232fe3eebf75c83bf8957e29e8d1b02efd4261659b8b80f260df44bbc99dab120a1a1a6f5a3a0393bd3f569b1f1e4db';
        // $iv = '5e7123e19a07a334a99524ef5785f315';
        $salt = getDecryptionSaltIv() -> salt;
        $iv = getDecryptionSaltIv() -> iv;

        //on PHP7 can use random_bytes() istead openssl_random_pseudo_bytes()
        //or PHP5x see : https://github.com/paragonie/random_compat
    
        $iterations = 949;  
        $key = hash_pbkdf2("sha512", $passphrase, hex2bin($salt), $iterations, 64);
    
        $encrypted_data = openssl_decrypt($encrypted, 'aes-256-cbc', hex2bin($key), OPENSSL_RAW_DATA, hex2bin($iv));
    
        $data = $encrypted_data;
        return $data;
    }

    
?>


    




    