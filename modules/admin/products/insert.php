<?php 
   ob_start();
    require_once("../../../config/connection.php");
    require_once("../../functions.php");
    if(isset($_POST["insert"])){
    $naziv = $_POST['naziv'];
    $cena = $_POST['cena'];
    $opis = $_POST['opis'];
    $kategorija = $_POST['kategorije'];
    $vel = isset($_POST['velicine'])? $_POST['velicine']:null;
    $stanje=$_POST['stanje'];
    $putanja=$_POST['putanja'];
    $opislike=$_POST['slikopis'];

    $greske = [];

    $reNaziv = "/^[A-Z][A-z]{0,99}$/";
    $reCena = "/^[\d]{1,25}\.[\d]{2}$/";
    $rePutanje="/^(http(s?):)*([\/|\.|\w|\s|\-])+\.(?:jpg|gif|png)$/";
        
    if($naziv==""){
        array_push($greske, "Name is required!");
    }
    else if (!preg_match($reNaziv, $naziv)) {
        array_push($greske, "Gotta start with an uppercase(dont-forget:only 100 letters");
    }

    if($cena==""){
        array_push($greske, "Price is required!");
    }
    else if (!preg_match($reCena, $cena)) {
        array_push($greske, "Numbers only(with 25 digit limit) and 2 digits after fullstop...like this: 27.00");
    }

    if($putanja==""){
        array_push($greske, "Src is required!");
    }
    else if (!preg_match($rePutanje, $putanja)) {
        array_push($greske, "It can be link or name with only these extensions: jpg|gif|png");
    }
    
    if (count($greske)>0) {
        $_SESSION["greske"] = $greske;
        header("Location:../../../index.php?page=new-product");
    } else { 
        try {
                $conn->beginTransaction();
                $slika=$conn->prepare("insert into slika(putanja,opis) values(:putanja,:opis)");
                $slika->bindParam(":putanja",$putanja);
                $slika->bindParam(":opis",$opislike);
                $slika->execute();
                $idS=$conn->lastInsertId();

                $insertuj=$conn->prepare("insert into proizvod(naziv,cena,opis,kategorijeID,slikeID) values(:naziv,:cena,:opis,:kategorija,:idSlike)");
                $insertuj->bindParam(":naziv",$naziv);
                $insertuj->bindParam(":cena",$cena);
                $insertuj->bindParam(":opis",$opis);
                $insertuj->bindParam(":kategorija",$kategorija);
                $insertuj->bindParam(":idSlike",$idS);
                $insertuj->execute();
                $idP=$conn->lastInsertId();
                print_r($vel);
                
                if($stanje=="Yes"){
                    $ima=$conn->prepare("insert into stanjeproizvoda (proizvodID,naStanju) values(:idP,1)");
                        $ima->bindParam(":idP",$idP);
                        $ima->execute();
                }
                if($stanje=="No"){
                    $ima=$conn->prepare("insert into stanjeproizvoda (proizvodID,naStanju) values(:idP,0)");
                        $ima->bindParam(":idP",$idP);
                        $ima->execute();
                }

                if(isset($_POST["velicine"])){
                    foreach($vel as $v){
                        $ima=$conn->prepare("insert into stanjeproizvoda (proizvodID,velicinaID,naStanju) values(:idP,:idV,1)");
                        $ima->bindParam(":idP",$idP);
                        $ima->bindParam(":idV",$v);
                        $ima->execute();
                        $osimovih[]=$v;
                    }
                }

                $sve=$conn->query("select idVelicine from velicina")->fetchAll();
                // 1,2,3,4,5 s
                // 2,3,4 ov
                
                if(isset($_POST["velicine"])){
                    foreach($sve as $s){
                        $vel=$s->idVelicine;
                        if(!in_array($vel,$osimovih)){
                             $nemaovih[]=$vel;//1,5?
                        }
                        
                    }
                }
                
                if(isset($_POST["velicine"])){
                    foreach($nemaovih as $no){
                        $fali=$conn->prepare("insert into stanjeproizvoda (proizvodID,velicinaID,naStanju) values(:idP,:idV,0)");
                        $fali->bindParam(":idP",$idP);
                        $fali->bindParam(":idV",$no);
                        $fali->execute();
                    }
                }

                $conn->commit();
                
                header("Location:../../../index.php?page=admin-products");
        } catch (PDOException $e) {
            array_push($greske, $e->getMessage());
            $_SESSION["greske"]= $greske;
            header("Location:../../../index.php?page=new-product");
        }
    }
    }
    else{
        header("Location:../../../index.php?page=new-product");
    }
?>