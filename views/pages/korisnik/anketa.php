<?php
if ((isset($_SESSION['korisnik']) && $_SESSION['korisnik']->uloga == 'Korisnik')) : ?>
    <div class="omotac border-top py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center mt-5">
                <?php
                    $aktivna = $conn->query("select * from anketa where aktivna=1")->fetch();
                    if ($aktivna) {
                        echo "<h3>$aktivna->pitanje</h3>";
                    } else {
                        echo "<h3>Wait til we make one</h3>";
                    }
                    ?>
            </div>
        </div>
        <div class="row text-center mt-5">
            <div class="col-lg-6 py-5">

                <form action="" method="post">
                    <input type="hidden" id="hiddenIDuser" value="<?=$_SESSION['korisnik']->kid; ?>">
                    <input type="hidden" id="hiddedIDsurvey" value="<?= $aktivna->idAnkete; ?>">
                    <?php
                        $odgovori = $conn->query("select o.odgovor as odg ,o.idOdgovora as odid from anketa a inner join odgovor o on a.idAnkete=o.anketaID")->fetchAll();
                        foreach ($odgovori as $odg) : ?>

                        <input type="radio" name="glas" id="" class="ml-5" value="<?= $odg->odid ?>"> <?= $odg->odg ?>

                    <?php endforeach ?>
                        
                    <br><button type="button" class="btn btn-dark my-5" id="glasaj" >Vote</button>
                    <div id="glas"></div>
                </form>
            </div>
            <div class="col-lg-6 py-5">
                <p class="mt-3">Wanna see results?</p>
                <button type="button" id="rezultati" class="btn btn-dark">Results</button>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 mx-auto py-3" id="svirezultati">
            </div>
        </div>
    </div>
    </div>
<?php else : header("Location:../index.php?page=home"); ?>
<?php endif ?>