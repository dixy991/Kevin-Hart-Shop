<?php 
    require_once("config/connection.php");

    $limit = 4;
    $offset = 0;
    if(isset($_GET["broj"])) {
        $offset = ($_GET["broj"] - 1) * $limit; 
    }
    $totalCount = $conn->prepare("select count(*) as total from proizvod WHERE kategorijeID=:id");
    $totalCount->bindParam(":id",$ajdi);
    $uspeh=$totalCount->execute();
    
    if($uspeh){
         $totalCount=$totalCount->fetch()->total;//6
    }
    $pagesCount = ceil($totalCount / $limit);//2
 ?>