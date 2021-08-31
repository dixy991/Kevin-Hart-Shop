<?php
    session_start();
    ob_start();
    if(isset($_SESSION['korisnik'])){
        header("Content-type:application/json");
        require_once("../config/connection.php");
        $data=null;
        $code=404;
        try
        {
            $upit=$conn->query("SELECT *,v.naziv as velicinaziv,s.putanja as putanja,s.opis as opis,p.naziv as naziv,p.cena as cena FROM slika s inner join proizvod p on s.idSlike=p.slikeID INNER JOIN kategorija k on p.kategorijeID=k.idKategorije inner join stanjeproizvoda pv on pv.proizvodID=p.idProizvoda left outer join velicina v on pv.velicinaID=v.idVelicine order by p.idProizvoda");
            if($upit->rowCount()>=1){
                $code=200;
                $data=$upit->fetchAll();
            }
        }
        catch(PDOException $e){
            $code=500;
            $data=["greska"=>$e->getMessage()];
        }
        http_response_code($code);
        echo json_encode($data);
    }
    else{
        header("Location:../index.php?page=login");
    }
?>