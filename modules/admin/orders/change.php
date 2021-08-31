<?php
    ob_start();
    session_start();
    //brisi porudzbinu
    if(isset($_POST["delete"])){
        require_once("../../../config/connection.php");
        require_once("../../functions.php");
        $id=$_POST["idKoris"];

        try{
            izbrisiPorudzbinu($id);
            header("Location:../../../index.php?page=admin-orders");
            $_SESSION["uspeh"]="Successfully deleted!";
        }
        catch (PDOException $e) {
            $_SESSION["greske"]=$e->getMessage();
            header("Location:../../../index.php?page=admin-orders");
        }
    }
    else{
        header("Location:../../../index.php?page=admin-orders");
    }
    header("Content-type:application/json");
    //dohvati detalje porudzbine
    if(isset($_POST["send"])){
        require_once("../../../config/connection.php");
        require_once("../../functions.php");
        $id=$_POST["idKorisnika"];

        $code=404;
        $data=null;

        try{
            $dohvati=$conn->prepare("select *,v.skracenica as vnaz,p.naziv as pnaz from porudzbina pr left outer JOIN velicina v on pr.velicinaID=v.idVelicine inner join proizvod p on pr.proizvodID=p.idProizvoda inner join slika s on p.slikeID=s.idSlike where pr.korisnikID=:id");
            $dohvati->bindParam(":id",$id);
            $dohvati->execute();

            if($dohvati){
                $rezultati=$dohvati->fetchAll();
                $data=$rezultati;
                $code=200;
            }
            
        }
        catch (PDOException $e) {
            $code=500;
            $data=["greska"=>$e->getMessage()];
            header("Location:../../../index.php?page=admin-orders");
        }
        
    http_response_code($code);
    echo json_encode($data);
    }
    else{
        header("Location:../../../index.php?page=admin-orders");
    }
    ?>