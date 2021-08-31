<?php
    ob_start();
    header("Content-type:application/json");
    if(isset($_POST['send'])){
    require_once("../config/connection.php");
    require_once("functions.php");
    $id=$_POST['idAnkete'];
    $code=404;
    $data=null;
    try{
        $uhvati=$conn->prepare("select count(g.odgovorID) as ukupno, o.odgovor from odgovor o left outer join glasanje g on o.idOdgovora=g.odgovorID where o.anketaID=:id group by o.odgovor");
        $uhvati->bindParam(":id",$id);
        $uhvati->execute();

        if($uhvati){
            $rezultati=$uhvati->fetchAll();
            $data=$rezultati;
            $code=200;
        }
    }
    catch(PDOException $e){
        $code=500;
        $data=["greska"=>$e->getMessage()];
    }
    echo json_encode($data);
    http_response_code($code);
    }
    else{
        header("Location:../index.php?page=survey");
    }
 ?>