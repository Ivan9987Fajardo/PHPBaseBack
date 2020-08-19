<?php

    function insertUser($user, $key){
        helper('date');
        $format = "%Y-%m-%d %h:%i %A";
        $db = db_connect();
        $query = "INSERT INTO Authentication (User, Hash, CreatedDate, ExpiryDate) VALUES (?,?,?,?)";
        $data = array($user, $key,time(),time());
        $result = $db->query($query,$data);
        return $result;
    }

    function deleteUser($user){
        $db = db_connect();
        $query = "DELETE FROM Authentication WHERE User = ?";
        $result = $db->query($query,$user);
        return $result;
    }

    function updateUser($user, $newKey){
        $db = db_connect();
        $query = "UPDATE Authentication SET Hash = ? WHERE User = ?";
        $data = array($newKey, $user);
        $result = $db->query($query,$data);
        return $result;
    }

    function getUserHash($user){
        $db = db_connect();
        $query = "SELECT Hash,ExpiryDate FROM Authentication WHERE User = ?";
        $resultSet = $db->query($query,$user);
        $result = $resultSet->getResult();
        return $result[0];
    }




?>