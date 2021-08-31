<?php if (isset($_GET['id'])) :
    $ajdi = $_GET['id'];
    $rproizvod = $conn->prepare("SELECT pv.naStanju as ima,pv.velicinaID as vid,p.opis as popis,k.idKategorije as kid, k.naziv as katnaz, p.idProizvoda as pId,p.naziv as pnaz,p.cena as pcena,s.putanja as sput,s.opis as altic from  kategorija k inner join proizvod p on k.idKategorije=p.kategorijeID inner join slika s on p.slikeID=s.idSlike inner join stanjeproizvoda pv on p.idProizvoda=pv.proizvodID  WHERE p.idProizvoda=:id group by p.naziv");
    $rproizvod->bindParam(":id", $ajdi);
    $rproizvod->execute();
    if ($rproizvod->rowCount() == 1) :
        $rpr = $rproizvod->fetch(); 
?>

<div class="omotac  border-top  text-center">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb py-3">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="index.php?page=categories">Collections</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                <?= "<a href='index.php?page=products&id=$rpr->kid'>$rpr->katnaz</a>"; ?>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <?= $rpr->pnaz ?>
            </li>
        </ol>
    </nav>
    <div class="omotac">
        <div class="row ">
            <div class="col-lg-6">
                <img src="assets/img/<?= $rpr->sput ?>" alt="<?= $rpr->altic ?>" class="w-100"/>
            </div> 
            <div class="col-lg-6 my-auto text-left">
                <h3 class="h1 font-weight-bold"><?= $rpr->pnaz ?></h3>
                <p class="crveno h3 mb-2">$<?= $rpr->pcena ?></p>
                <?php if ($rpr->vid != null) : ?>
                    <?php $velicine = $conn->query("select * from velicina")->fetchAll(); ?>
                    <select name="" id="velicine" class="mt-5 " data-idproizvoda="<?= $rpr->pId ?>">
                    <option value="0"> Choose...</option>
                        <?php foreach ($velicine as $v) : ?>
                            <option value="<?= $v->idVelicine ?>"><?= $v->naziv ?>-(<?= $v->skracenica ?>)</option>
                        <?php endforeach ?>
                    </select><br>
                <?php else : ?>
                <?php endif ?>
                <input type="number" class="pl-5" name="" id="kolicinski" value="1" min="1" max="50" data-idproizvoda="<?= $rpr->pId ?>">
                <?php if (isset($_SESSION['korisnik'])) : ?>

                    <?php $imal = $conn->prepare("SELECT sp.naStanju as ima,sp.velicinaID as vid FROM proizvod p inner JOIN stanjeproizvoda sp on p.idProizvoda=sp.proizvodID where p.idProizvoda=:id group by p.idProizvoda,sp.naStanju order by sp.naStanju desc");
                                $id = $rpr->pId;
                                $imal->bindParam(":id", $id);
                                $imal->execute();
                                foreach ($imal as $i) :
                                    ?>
                        <?php if ($i->ima == 1) : ?>
                            <br>
                            <?php if ($i->vid != null) : ?>
                                    <button type="submit" id="dodajUkorpu" name="dodajUkorpu" data-idproizvoda="<?= $rpr->pId ?>" class="btn mb-5" disabled>Gotta choose size first</button>
                            <?php else : ?>
                                    <button type="submit" id="dodajUkorpu" name="dodajUkorpu" data-idproizvoda="<?= $rpr->pId ?>" class="btn mb-5">Add to cart</button>
                            <?php endif ?>
                            <?php break; ?>
                        <?php else : ?>
                            <br>
                            <button class="btn mb-5" disabled>Sold out</button>
                        <?php endif ?>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="my-5">To order, gotta <a href="index.php?page=login" class="crveno">login</a> first!</p>
                <?php endif ?>
                <div id="ima-il-nema"></div>
                <ul class="crveno border-top border-bottom py-5">
                    <?php
                            $opis = $rpr->popis;
                            $pojedinacniOpisi = explode(".", $opis);
                            foreach ($pojedinacniOpisi as $pOp) {
                                echo "<li class='my-3'>$pOp</li>";
                            }
                            ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif ?>
</div>

<?php else : ?>
    <?php header("Location:index.php?page=categories"); ?>
<?php endif ?>