<div id="slajder"></div>
<div class="omotac">
    <div class="row my-5">
        <h2 class="mx-auto">T-shirts</h2>
    </div>
    <?php

    $upit = $conn->query("select k.naziv as katnaz, p.idProizvoda as pId,p.naziv as pnaz,p.cena as pcena,s.putanja as sput,s.opis as altic from  kategorija k inner join proizvod p on k.idKategorije=p.kategorijeID inner join slika s on p.slikeID=s.idSlike inner join stanjeproizvoda pv on p.idProizvoda=pv.proizvodID where k.naziv='T-shirts' group by p.naziv order by p.idProizvoda limit 4 ")->fetchAll(); ?>

    <div class="row text-center">

        <?php foreach ($upit as $red) : ?>
            <div class="col-lg-3">
                <a href="index.php?page=product&id=<?= $red->pId ?>">
                    <figure>
                        <img src="assets/img/<?= $red->sput ?>" alt="<?= $red->altic ?>" class="w-100">
                        <caption><?= $red->pnaz ?>
                        <?php $imal = $conn->prepare("SELECT sp.naStanju as ima FROM proizvod p inner JOIN stanjeproizvoda sp on p.idProizvoda=sp.proizvodID where p.idProizvoda=:id group by p.idProizvoda,sp.naStanju order by sp.naStanju desc");
                                $id = $red->pId;
                                $imal->bindParam(":id", $id);
                                $imal->execute();
                                foreach ($imal as $i) :
                                    ?>
                                <?php if ($i->ima == 1) : ?>
                                    <p class="crveno">$<?= $red->pcena ?></p>
                                    <?php break; ?>
                                <?php else : ?>
                                    <p>Sold out</p>
                                <?php endif ?>
                            <?php endforeach; ?>
                        </caption>
                    </figure>
                </a>
            </div>
        <?php endforeach ?>

    </div>
    <div class="row">
        <a href="index.php?page=products&id=1" class="btn btn-lg active mx-auto" role="button" aria-pressed="true">View all</a>
    </div>

    <div class="row my-5 ">
        <h2 class="mx-auto">Hoodies</h2>
    </div>
    <?php
    $upit = $conn->query("select k.naziv as katnaz,s.putanja as sput,s.opis as altic,p.idProizvoda as pId,p.naziv as pnaz,p.cena as pcena from  kategorija k inner join proizvod p on k.idKategorije=p.kategorijeID inner join slika s on p.slikeID=s.idSlike inner join stanjeproizvoda pv on p.idProizvoda=pv.proizvodID where k.naziv='Hoodies' group by p.naziv order by p.naziv desc limit 4")->fetchAll(); ?>

    <div class="row text-center">

        <?php foreach ($upit as $red) : ?>
            <div class="col-lg-3">
                <a href="index.php?page=product&id=<?= $red->pId ?>">
                    <figure>
                        <img src="assets/img/<?= $red->sput ?>" alt="<?= $red->altic ?>" class="w-100">
                        <caption><?= $red->pnaz ?>
                            <?php $imal = $conn->prepare("SELECT sp.naStanju as ima FROM proizvod p inner JOIN stanjeproizvoda sp on p.idProizvoda=sp.proizvodID where p.idProizvoda=:id group by p.idProizvoda,sp.naStanju order by sp.naStanju desc");
                                $id = $red->pId;
                                $imal->bindParam(":id", $id);
                                $imal->execute();
                                foreach ($imal as $i) :
                                    ?>
                                <?php if ($i->ima == 1) : ?>
                                    <p class="crveno">$<?= $red->pcena ?></p>
                                    <?php break; ?>
                                <?php else : ?>
                                    <p>Sold out</p>
                                <?php endif ?>
                            <?php endforeach; ?>
                    </figure>
                </a>
            </div>
        <?php endforeach ?>

    </div>

    <?php
    $jedinac = $conn->query("select p.idProizvoda as pId,p.naziv as pnaz,p.cena as pcena,p.opis as popis,s.putanja as sput,s.opis as altic from proizvod p inner join slika s on p.slikeID=s.idSlike inner join kategorija k on p.kategorijeID=k.idKategorije inner join stanjeproizvoda pv on p.idProizvoda=pv.proizvodID where k.naziv='Jackets' group by p.naziv order by p.naziv desc limit 1")->fetch(); ?>

    <div class="row my-5 py-5">
        <div class="col-lg-6">
        <a href="index.php?page=product&id=<?= $jedinac->pId ?>">
            <img src="assets/img/<?= $jedinac->sput ?>" alt="<?= $jedinac->altic ?>" class="w-100 nopa">
        </a>
        </div>
        <div class="col-lg-6 my-auto">
            <p class="pl-3 h1 font-weight-bold"><?= $jedinac->pnaz ?></p>
            <?php $imal = $conn->prepare("SELECT sp.naStanju as ima FROM proizvod p inner JOIN stanjeproizvoda sp on p.idProizvoda=sp.proizvodID where p.idProizvoda=:id group by p.idProizvoda,sp.naStanju order by sp.naStanju desc");
            $id = $jedinac->pId;
            $imal->bindParam(":id", $id);
            $imal->execute();
            foreach ($imal as $i) :
                ?>
                <p class="crveno mb-4 pl-3 h1">
                    <?php if ($i->ima == 1) : ?>
                        $<?= $jedinac->pcena ?>
                        <?php break; ?>
                    <?php else : ?>
                        Sold out
                    <?php endif ?>
                </p>
            <?php endforeach; ?>
            <ul class="crveno">
                <?php
                $opis = $jedinac->popis;
                $pojedinacniOpisi = explode(".", $opis);
                foreach ($pojedinacniOpisi as $pOp) {
                    echo "<li class='my-2'>$pOp</li>";
                }
                ?>
            </ul>
            <p class="mt-4 pl-3 "><i>This jacket is one of a kind, like you. But there are few left in stock. Go get yours now!</i></p>
            <!-- velicine -->
            <?php
            $velicine = $conn->query("select * from velicina")->fetchAll(); ?>
            <select name="" id="velicine" class="my-5 ml-3">
                <?php foreach ($velicine as $v) : ?>
                    <option value="<?= $v->idVelicine ?>"><?= $v->naziv ?>-(<?= $v->skracenica ?>)</option>
                <?php endforeach ?>
            </select><br>
            <?php if (isset($_SESSION['korisnik'])) : ?>
                <button type="button" id="dodajUkorpu" data-idproizvoda="<?= $jedinac->pId ?>" class="btn btn-dark my-5 ml-3">Add to cart</button>
            <?php else : ?>
                <p class="ml-3">To order, gotta <a href="index.php?page=login" class="text-danger">login</a> first!</p>
            <?php endif ?>
        </div>
    </div>
</div>