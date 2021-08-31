<?php 
   ob_start();
if (isset($_POST['send'])) {
 
    require_once("../../../config/connection.php");
    require_once("../../functions.php");
    header("Content-type:application/json");
    $pitanje = $_POST['pitanje'];
    $odgovori = $_POST['odgovori'];

    $greske = [];
    $code = 404;
    $data = null;

    $rePitanje = "/^[\w\d\/\.\_\-]+(\s[\w\d]+)*\?$/";
    $reOdgovori = "/^[\w\d]+(\/[\w\d]+){1,}$/";

    if($pitanje==""){
        array_push($greske, "Question is required!");
    }
    else if (!preg_match($rePitanje, $pitanje)) {
        array_push($greske, "End with question mark");
    }

    if($odgovori==""){
        array_push($greske, "Answers are required!");
    }
    if (!preg_match($reOdgovori, $odgovori)) {
        array_push($greske, "At least two of them(and separate them with slashes '/' !!! geez...)");
    }

    if (count($greske)>0) {
        $data = $greske;
        $code = 422;
    } else { 
        try {
            $odgovori=explode("/",$odgovori);
            if(count($odgovori)<2){
                $data[]="Less then two made answers!";
            }
            else{
                $conn->beginTransaction();
                $anketa=$conn->prepare("insert into anketa(pitanje,aktivna) values(:pitanje,0)");
                $anketa->bindParam(":pitanje",$pitanje);
                $anketa->execute();
                $id=$conn->lastInsertId();
                $parametri=[];
                $vrednosti=[];
                foreach($odgovori as $odgovor){
                $parametri[]="(?,?)";
                $vrednosti[]=$id;
                $vrednosti[]=$odgovor;
                }
                $upit=$conn->prepare("insert into odgovor(anketaID,odgovor) values" .implode(",",$parametri));
                $upit->execute($vrednosti);
                $conn->commit();
                $code=201;

            }
        } catch (PDOException $e) {
            $code = 500; 
            $data=["greska"=>$e->getMessage()];
        }
    }
    http_response_code($code);
    echo json_encode($data);
}
else{
    header("Location:../../../index.php?page=admin-surveys");
}

?>