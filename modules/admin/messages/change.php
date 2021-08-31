<?php
    ob_start();
    session_start();
    //brisi poruku
    if(isset($_POST["delete"])){
        require_once("../../../config/connection.php");
        require_once("../../functions.php");
        $id=$_POST["idPoruke"];

        try{
            izbrisiPoruku($id);
            header("Location:../../../index.php?page=admin-contact");
            $_SESSION["uspeh"]="Successfully deleted!";
        }
        catch (PDOException $e) {
            $_SESSION["greske"]=$e->getMessage();
            header("Location:../../../index.php?page=admin-contact");
        }
    }
    else{
        header("Location:../../../index.php?page=admin-contact");
    }
    //oznaci kao procitano
    if(isset($_POST["seenuj"])){
        require_once("../../../config/connection.php");
        require_once("../../functions.php");
        $id=$_POST["idPoruke"];

        try{
            seenujPoruku($id);
            header("Location:../../../index.php?page=admin-contact");
            $_SESSION["uspeh"]="Successfully seen!";
        }
        catch (PDOException $e) {
            $_SESSION["greske"]=$e->getMessage();
            header("Location:../../../index.php?page=admin-contact");
        }
    }
    else{
        header("Location:../../../index.php?page=admin-contact");
    }
    ?>