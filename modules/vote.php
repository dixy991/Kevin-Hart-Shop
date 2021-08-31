<?php
    ob_start();
    if(isset($_POST['send'])){
    require_once("../config/connection.php");
    require_once("functions.php");
    header("Content-type:application/json");
    $korisnikId=$_POST['korisnikId'];
    $glas=$_POST['glas'];
    $data=null;
    $code=404;
    try{
        $proveraglasa=$conn->prepare("select * from anketa a inner join odgovor o on a.idAnkete=o.anketaID inner join glasanje g on o.idOdgovora=g.odgovorID where g.korisnikID=:id and a.aktivna=1");
        $proveraglasa->bindParam(":id",$korisnikId);
        $proveraglasa->execute();
        if($proveraglasa->rowCount()>=1){
            $code=409;
        }
        else{
            unesiGlas($glas,$korisnikId);
            $code=201;
        }
    }
    catch(PDOException $e){
        $code = 500; 
        echo $x=$e->getMessage();
        array_push($data,$x);
    }
    http_response_code($code);
    echo json_encode($data);
    }
    else{
        header("Location:../index.php?page=survey");
    }
?>