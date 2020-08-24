<?php

    function getInventory(){
        $db = db_connect();
        $query = "SELECT * FROM Inventory";
        $resultSet = $db->query($query);
        $result = $resultSet->getResult();
        if(!count($result)) return false;
        return $result;
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