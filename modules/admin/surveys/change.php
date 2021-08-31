<?php
    ob_start();
    session_start();
    if(isset($_POST["izbrisi"])){
        require_once("../../../config/connection.php");
        require_once("../../functions.php");
        $id=$_POST["anketa"];

        try{
            izbrisiAnketu($id);
            header("Location:../../../index.php?page=admin-surveys");
            $_SESSION["uspeh"]="Successfully deleted!";
        }
        catch (PDOException $e) {
            $_SESSION["greske"]=$e->getMessage();
            header("Location:../../../index.php?page=admin-surveys");
        }
    }
    else{
        header("Location:../../../index.php?page=admin-surveys");
    }
    if(isset($_POST["aktiviraj"])){
        require_once("../../../config/connection.php");
        require_once("../../functions.php");
        $id=$_POST["anketa"];

        try{
            $provera=$conn->query("select * from anketa where aktivna=1");
            if($provera->rowCount()==0){ 
                aktivirajAnketu($id);
                header("Location:../../../index.php?page=admin-surveys");
                $_SESSION["uspeh"]="Successfully activated!";
            }
            else{
                $_SESSION["greske"]="Only one can be currently activated!";
                header("Location:../../../index.php?page=admin-surveys");
            }
        }
        catch (PDOException $e) {
            $_SESSION["greske"]=$e->getMessage();
            header("Location:../../../index.php?page=admin-surveys");
        }
    }
    else{
        header("Location:../../../index.php?page=admin-surveys");
    }
    if(isset($_POST["deaktiviraj"])){
        require_once("../../../config/connection.php");
        require_once("../../functions.php");
        $id=$_POST["anketa"];

        try{
            deaktivirajAnketu($id);
            header("Location:../../../index.php?page=admin-surveys");
            $_SESSION["uspeh"]="Successfully deactivated!";
        }
        catch (PDOException $e) {
            $_SESSION["greske"]=$e->getMessage();
            header("Location:../../../index.php?page=admin-surveys");
        }
    }
    else{
        header("Location:../../../index.php?page=admin-surveys");
    }
?>