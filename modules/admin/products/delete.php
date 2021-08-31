<?php
    ob_start();
    session_start();
    if(isset($_POST["delete"])){
        require_once("../../../config/connection.php");
        require_once("../../functions.php");
        $id=$_POST["idProiz"];

        try{
            izbrisiProizvod($id);
            header("Location:../../../index.php?page=admin-products");
        }
        catch (PDOException $e) {
            $_SESSION["greske"]=$e->getMessage();
            header("Location:../../../index.php?page=admin-products");
        }
    }
    else{
        header("Location:../../../index.php?page=admin-products");
    }
?>