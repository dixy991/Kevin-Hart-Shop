<?php
    ob_start();
    session_start();
    if(isset($_POST["update"])){
        require_once("../../../config/connection.php");
        require_once("../../functions.php");
        $idP=$_POST["idProiz"];
        $idS=$_POST["idSlike"];
        $name=$_POST["naziv"];
        $price=$_POST["cena"];
        $desc=$_POST["opis"];
        $cat=$_POST["kategorije"];
        $vel=$_POST["velicine"];
        $stanje=$_POST["stanje"];
        $putanja=$_POST['putanja'];
        $opislike=$_POST['slikopis'];

        try{
            updatujProizvod($idP,$name,$price,$desc,$cat);
            $slika=$conn->prepare("update slika set putanja=:putanja,opis=:opis where idSlike=:id");
            $slika->bindParam(":putanja",$putanja);
            $slika->bindParam(":opis",$opislike);
            $slika->bindParam(":id",$idS);
            $slika->execute();

            if($vel){
                foreach($vel as $v){
                    $ima=$conn->prepare("update stanjeproizvoda set naStanju=1 where velicinaID=:idV and proizvodID=:idP");
                    $ima->bindParam(":idV",$v);
                    $ima->bindParam(":idP",$idP);
                    $ima->execute();
                    $osimovih[]=$v;
                }
                $sve=$conn->query("select idVelicine from velicina")->fetchAll();
                    // 1,2,3,4,5 s
                    // 2,3,4 ov
                    
                    foreach($sve as $s){
                         $velicina=$s->idVelicine;
                        if(!in_array($velicina,$osimovih)){
                             $nemaovih[]=$velicina;//1,5?
                        }
                        
                    }
                    foreach($nemaovih as $no){
                        
                        $fali=$conn->prepare("update stanjeproizvoda set naStanju=0 where velicinaID=:idV and proizvodID=:idP");
                        $fali->bindParam(":idP",$idP);
                        $fali->bindParam(":idV",$no);
                        $fali->execute();
                    }
            }
            if($stanje=="Yes"){
                $ima=$conn->prepare("update stanjeproizvoda set naStanju=1 where  proizvodID=:idP");
                $ima->bindParam(":idP",$idP);
                $ima->execute();
            }
            else{
                $ima=$conn->prepare("update stanjeproizvoda set naStanju=0 where  proizvodID=:idP");
                $ima->bindParam(":idP",$idP);
                $ima->execute();
            }
            header("Location:../../../index.php?page=admin-products");
        }
        catch(PDOException $e){
            $_SESSION["greske"]=$e->getMessage();
            header("Location:../../../index.php?page=products-update");
        }
    }
    else{
        header("Location:../../../index.php?page=admin-products");
    }
?>