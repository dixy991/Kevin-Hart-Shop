<?php 
   ob_start();
if (isset($_POST['send'])) {
 
    require_once("../config/connection.php");
    require_once("functions.php");
    header("Content-type:application/json");

    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $username = $_POST['username'];
    $mejl = $_POST['mejl'];
    $sifra = $_POST['sifra'];

    $greske = [];
    $code = 404;
    $data = null;

    $reimeprezime = "/^[A-Z][a-z]+(\s[A-Z][a-z]+)*$/";
    $repassword = "/^[A-z0-9]{7,50}$/";
    $reuser = "/^[\d\w\_\-\.@]{4,30}$/";

    if($ime==""){
        array_push($greske, "First name is required! <br/>");
    }
    else if (!preg_match($reimeprezime, $ime)) {
        array_push($greske, "First name in bad format!-Start with an uppercase! <br/>");
    }

    if($prezime==""){
        array_push($greske, "Last name is required! <br/>");
    }
    else if (!preg_match($reimeprezime, $prezime)) {
        array_push($greske, "Last name in bad format!-Start with an uppercase! <br/>");
    }

    if($username==""){
        array_push($greske, "Username is required!");
    }
    else if (!preg_match($reuser, $username)) {
        array_push($greske, "Username in bad format!-At least 7 characters! <br/>");
    }

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
        $data = $greske;
        $code = 422;
    } 
    else {
        $sifra = md5($sifra);
        try {
            $provera = $conn->prepare("SELECT * FROM korisnik WHERE email = :email OR username = :username");
            $provera->bindParam(":email",$mejl);
            $provera->bindParam(":username",$username);
            $provera->execute();

            if ($provera->rowCount() >= 1) {
                $code=409;
            }else{
                insertKorisnika($ime, $prezime, $mejl, $username, $sifra);
                $code = 201;
            }
        } catch (PDOException $e) {
            $code = 500; 
            $x=$e->getMessage();
            array_push($data,$x);
        }
    }
    http_response_code($code);
    echo json_encode($data);
}
else{
    header("Location:../index.php?page=register");
}
?>