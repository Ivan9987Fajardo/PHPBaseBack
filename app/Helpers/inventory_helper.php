<?php

    function getInventory(){
        $db = db_connect();
        $query = "SELECT ItemId as itemId, Name as name, Quantity as quantity FROM Inventory";
        $resultSet = $db->query($query);
        $result = $resultSet->getResult();
        if(!count($result)) return false;
        return $result;
    }

    function addInventory($name, $quantity){
        $db = db_connect();
        $query = "INSERT INTO Inventory (Name, Quantity) VALUES (?,?);";
        $data = array($name, $quantity);
        $result = $db->query($query,$data);
        return $result;
    }

    function deleteInventory($id){
        $db = db_connect();
        $query = "DELETE FROM Inventory WHERE ItemId = ?";
        $result = $db->query($query,$id);
        return $result;
    }

    function putInventory($id,$name,$quantity){
        $db = db_connect();
        $query = "UPDATE Inventory SET Name = ?, Quantity = ? WHERE ItemId = ?";
        $data = array($name, $quantity,$id);
        $result = $db->query($query,$data);
        return $result;
    }

    




?>