<?php 
   ob_start();
   session_start();
if (isset($_POST['btnulogujse'])) {
 
    require_once("../config/connection.php");

    $mejl = $_POST['mejl'];
    $sifra = $_POST['sifra'];

    $greske = [];

    $repassword = "/^[A-z0-9]{7,50}$/";

    if($mejl==""){
        array_push($greske, "Email is required!");
    }
    else if (!filter_var($mejl, FILTER_VALIDATE_EMAIL)) {
        array_push($greske, "Email in bad format!Gotta have @ in it!Like this: try.again@gmail.com <br/>");
    }

    if($sifra==""){
        array_push($greske, "Password is required!");
    }
    else if (!preg_match($repassword, $sifra)) {
        array_push($greske, "Password in bad format! At least 7 characters!**Only letters and numbers! <br/>");
    }
    
    if (count($greske)>0) {
        $_SESSION["greske"]=$greske;
        header("Location:../index.php?page=login");
    } else {
        $sifra = md5($sifra);
        try {
            $nadji=$conn->prepare("select k.idKorisnik as kid,k.username,u.naziv as uloga from korisnik k inner join uloga u on k.ulogaID=u.idUloga where k.email=:mejl and k.password=:sifra and k.active=1");
            $nadji->bindParam(":mejl",$mejl);
            $nadji->bindParam(":sifra",$sifra);
            $nadji->execute();
            
            if($nadji->rowCount()==1){
                $rezultat=$nadji->fetch();
                $_SESSION['korisnik']=$rezultat;
                header("Location:../index.php?page=home");
            }
            else{
                array_push($greske, "Gotta create account first!");
                array_push($greske, "<br/> Or try again...");
                $_SESSION["greske"]=$greske;
                header("Location:../index.php?page=login");
            }
        } catch (PDOException $e) {
            array_push($greske, $e->getMessage());
            $_SESSION["greske"]= $greske;
            header("Location:../index.php?page=login");
        }
    }
}
else{
    header("Location:../index.php?page=login");
}

?>