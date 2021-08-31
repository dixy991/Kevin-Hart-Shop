<?php if (isset($_GET['id'])) :
    $ajdi = $_GET['id'];
    require_once("paginacija.php");
    $rproizvodi = $conn->prepare("SELECT pv.naStanju as ima, k.naziv as katnaz, p.idProizvoda as pId,p.naziv as pnaz,p.cena as pcena,s.putanja as sput,s.opis as altic from  kategorija k inner join proizvod p on k.idKategorije=p.kategorijeID inner join slika s on p.slikeID=s.idSlike inner join stanjeproizvoda pv on p.idProizvoda=pv.proizvodID  WHERE k.idKategorije=:id  group by p.naziv order by p.naziv desc limit $limit offset $offset");
    $rproizvodi->bindParam(":id", $ajdi);
    $rproizvodi->execute();
    if ($rproizvodi->rowCount() > 0) :
        $rproizvodi = $rproizvodi->fetchAll();
        ?>

        <div class="omotac border-top">
            <nav aria-label="breadcrumb ">
                <ol class="breadcrumb py-3">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="index.php?page=categories">Collections</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php foreach ($rproizvodi as $rpr) : ?>
                            <?php echo $rpr->katnaz;
                                        break; ?>
                        <?php endforeach ?>
                    </li>
                </ol>
            </nav>
            <div class="row border-bottom my-5">
                <?php foreach ($rproizvodi as $rpr) : ?>
                    <h2><?php echo $rpr->katnaz;
                                    break; ?></h2>
                <?php endforeach ?>
            </div>
            <div class="row text-center">
                <?php foreach ($rproizvodi as $rpr) : ?>
                    <div class="col-lg-3">
                        <a href="index.php?page=product&id=<?= $rpr->pId ?>">
                            <figure>
                                <img src="assets/img/<?= $rpr->sput ?>" alt="<?= $rpr->altic ?>" class="w-100">
                                <caption><?= $rpr->pnaz ?>
                                    <?php $imal = $conn->prepare("SELECT sp.naStanju as ima FROM proizvod p inner JOIN stanjeproizvoda sp on p.idProizvoda=sp.proizvodID where p.idProizvoda=:id group by p.idProizvoda,sp.naStanju order by sp.naStanju desc");
                                                $id = $rpr->pId;
                                                $imal->bindParam(":id", $id);
                                                $imal->execute();
                                                foreach ($imal as $i) :
                                                    ?>
                                        <?php if ($i->ima == 1) : ?>
                                            <p class="crveno">$<?= $rpr->pcena ?></p>
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
            <div class="row justify-content-center">
                <nav aria-label="Page navigation example">
                    <ul class="pagination pagination-sm">
                        <?php for ($i = 0; $i < $pagesCount; $i++) : ?>
                            <li class="page-item"><a class="crno page-link crveno" href="<?php
                        $putanja = $_SERVER["PHP_SELF"] . "?" . $_SERVER["QUERY_STRING"] . "&broj=" . ($i + 1);
                         echo substr($putanja, 0, 33) . "&broj=" . ($i + 1); ?>"><?= $i + 1 ?></a></li>
                        <?php endfor ?>
                    </ul>
                </nav>
            </div>
        <?php endif ?>
        </div>

    <?php else : ?>
        <?php header("Location:index.php?page=categories"); ?>
    <?php endif ?>