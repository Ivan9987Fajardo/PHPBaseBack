<?php

    function verifyPassword($password,$storedPassword){
        if(password_verify($password,$storedPassword)){
            return true;
        } else {
            return false;
        }
    }

    function verifyUserGetPassword($userId, $password){
        $db = db_connect();
        $query = "SELECT Name,Password FROM Users WHERE UserId = ?";
        $resultSet = $db->query($query,$userId);
        $result = $resultSet->getResult();
        if(!count($result)) return false;
        return $result[0];
    }




?>