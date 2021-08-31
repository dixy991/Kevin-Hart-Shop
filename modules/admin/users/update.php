<?php
    ob_start();
    session_start();
    if(isset($_POST["update"])){
        require_once("../../../config/connection.php");
        require_once("../../functions.php");
        $id=$_POST["id"];
        $ime=$_POST["ime"];
        $prezime=$_POST["prezime"];
        $username=$_POST["username"];
        $email=$_POST["email"];
        $uloga=$_POST["uloga"];
        $active=$_POST["active"];

        try{
            updatujKorisnika($id,$ime,$prezime,$username,$email,$uloga,$active);
            header("Location:../../../index.php?page=admin-users");

        }
        catch(PDOException $e){
            $_SESSION["greske"]=$e->getMessage();
            header("Location:../../../index.php?page=users-update");
        }
    }
    else{
        header("Location:../../../index.php?page=admin-users");
    }
?>
?>