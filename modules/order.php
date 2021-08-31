<?php
 session_start();
 ob_start();
 if(isset($_SESSION['korisnik'])):
    if(isset($_POST['send'])){

    require_once("../config/connection.php");
    require_once("functions.php");
    header("Content-type:application/json");

    $idK=$_POST['id'];
    $idP=$_POST['idP'];
    $kolicina=$_POST['kol'];
    $velicina=$_POST['vel'];

    $code=404;
    $data=null;

    try{
        $svIdP=explode(",",$idP);
        $svaKolicina=explode(",",$kolicina);
        $svaVelicina=explode(",",$velicina);
        $vreme=date("Y-m-d H:i:s",time());

        $parametri=[];
        $vrednosti=[];
        foreach($svIdP as $sid){
            $parametri[]="(?,?,?,?,?)";
            $vrednosti[]=$idK;
            $vrednosti[]=$sid;
            foreach($svaKolicina as $sk){
                $vrednosti[]=$sk;
                array_shift($svaKolicina);
                foreach($svaVelicina as $sv){
                    array_shift($svaVelicina);
                    if($sv=="null"){
                        $vrednosti[]=null;
                    }
                    else{
                        $vrednosti[]=$sv;
                    }
                break;
                }
                break;
            }
            $vrednosti[]=$vreme;
        }
        $naruci=$conn->prepare("insert into porudzbina(korisnikID,proizvodID,kolicina,velicinaID,datum) values" .implode(",",$parametri));
        $naruci->execute($vrednosti);

        $code=201;
    }
        catch(PDOException $e){
        $conn->rollback();
        $code=500;
        $data=["greska"=>$e->getMessage()];
        header("Location:../index.php?page=cart");
    }
    http_response_code($code);
    echo json_encode($data);
 }
 else{
    header("Location:../index.php?page=cart");
}
else:
    header("Location:../index.php?page=home");
endif;
?>