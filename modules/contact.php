<?php 
   ob_start();
if (isset($_POST['send'])) {
 
    require_once("../config/connection.php");
    require_once("functions.php");
    header("Content-type:application/json");
    $ime = $_POST['ime'];
    $email = $_POST['email'];
    $poruka = $_POST['poruka'];;

    $greske = [];
    $code = 404;
    $data = null;

    $reimeprezime = "/^[A-Z][a-z]+(\s[A-Z][a-z]+)*$/";

    if($ime==""){
        array_push($greske, "First name is required! <br/>");
    }
    else if (!preg_match($reimeprezime, $ime)) {
        array_push($greske, "First name in bad format!-Start with an uppercase! <br/>");
    }

    if($email==""){
        array_push($greske, "Email is required!");
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($greske, "Email in bad format!Gotta have @ in it!Like this: try.again@gmail.com <br/>");
    }
    
    if (count($greske)>0) {
        $data = $greske;
        $code = 422;
    } 
    else {
        try {
            insertPoruke($ime, $email, $poruka);
            $code = 201;
        } catch (PDOException $e) {
            $code = 500; 
            $data=["greska"=>$e->getMessage()];
        }
    }
    http_response_code($code);
    echo json_encode($data);
}
else{
    header("Location:../index.php?page=contact");
}

?>