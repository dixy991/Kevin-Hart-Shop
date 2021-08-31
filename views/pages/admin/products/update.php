<div class="container">
<?php if (isset($_SESSION["korisnik"]) && $_SESSION['korisnik']->uloga == 'Admin') : ?>
    <div class="row">
        <div class="col-lg-6 mx-auto text-center">
            <?php if (isset($_POST['apdejtuj'])) : ?>
                <form action="modules/admin/products/update.php" method="post" onsubmit="return ubaciProizvodUbazu()">
                    <?php
                        $id = $_POST["idProiz"];
                        $izabran = $conn->prepare("select k.idKategorije as kid,k.naziv as katnaz,p.idProizvoda as pId,p.opis as popis,s.opis as sopis,p.naziv as pnaz,p.cena as pcena,s.putanja as sput,s.idSlike as sId from  kategorija k inner join proizvod p on k.idKategorije=p.kategorijeID inner join slika s on p.slikeID=s.idSlike where p.idProizvoda=:id");
                        $izabran->bindParam(":id", $id);
                        $izabran->execute();
                        if ($izabran->rowCount() == 1) :
                            $podaci = $izabran->fetch(); ?>
                        <input type="hidden" name="idProiz" value="<?= $podaci->pId ?>">
                        <input type="hidden" name="idSlike" value="<?= $podaci->sId ?>">
                        <input type="text" name="naziv" id="" value="<?= $podaci->pnaz ?>" /><br>
                        <input type="text" name="cena" id="" value="<?= $podaci->pcena ?>" /><br>
                        <textarea name="opis" id="" cols="30" rows="10"><?= $podaci->popis ?></textarea><br>
                        <?php
                                $kategorije = $conn->query("select idKategorije,naziv from kategorija")->fetchAll();
                                $kategorija = $conn->prepare("select k.idKategorije as kid from kategorija k inner join proizvod p on k.idKategorije=p.kategorijeID where p.idProizvoda=:id");
                                $idpk = $podaci->pId;
                                $kategorija->bindParam(":id", $idpk);
                                $kategorija->execute();
                                echo "<select name='kategorije' id=''>";
                                foreach ($kategorije as $ks) :
                                    ?>
                            <?php
                                        foreach ($kategorija as $kj) {
                                            $druga = $kj->kid;
                                        }
                                        $prva = $ks->idKategorije;
                                        if ($prva == $druga) : ?>
                                <option value="<?= $ks->idKategorije ?>" selected><?= $ks->naziv ?></option>

                            <?php else : ?>
                                <option value="<?= $ks->idKategorije ?>"><?= $ks->naziv ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                        </select><br><br>
                        <?php $vel = $conn->prepare("select v.skracenica as velicina,s.naStanju as ima,v.idVelicine as vid from stanjeproizvoda s left outer join velicina v on s.velicinaID=v.idVelicine where s.proizvodID=:id");
                                $id = $podaci->pId;
                                $vel->bindParam(":id", $id);
                                $vel->execute();
                                foreach ($vel as $v) :
                                    ?>
                            <?php if (($v->ima == 1) && ($v->velicina != null)) : ?>
                                <input type="checkbox" class="mx-3" checked name="velicine[]" id="" value="<?= $v->vid ?>"><i class="text-success"> <?= $v->velicina ?> </i>
                            <?php elseif (($v->ima == 1) && ($v->velicina == null)) : ?>
                                <input type="radio" class="mx-3" checked name="stanje" id="" value="Yes"><i class="text-success"> In stock </i>
                                <input type="radio" class="mx-3"  name="stanje" id="" value="No"><i class="text-danger"> No moe </i>
                            <?php elseif (($v->ima == 0) && ($v->velicina == null)) : ?>
                                <input type="radio" class="mx-3" checked name="stanje" id="" value="No"><i class="text-danger"> Not available </i>
                                <input type="radio" class="mx-3"  name="stanje" id="" value="Yes"><i class="text-success"> Now yes </i>
                            <?php else : ?>
                                <input type="checkbox" class="mx-3" name="velicine[]" id="" value="<?= $v->vid ?>"><i class="text-danger">
                                    <?= $v->velicina ?>
                                </i>
                            <?php endif ?>
                        <?php endforeach; ?>
                        <br><br>
                        <label for="putanja">Picture src:</label><br>
                        <input type="text" name="putanja" id="putanja" value="<?= $podaci->sput ?>" /><span></span><br>
                        <label for="slikopis">Picture alt:</label><br>
                        <input type="text" name="slikopis" id="slikopis" value="<?= $podaci->sopis ?>" /><br>
                    <?php endif ?><br>
                    <button type="submit" name="update" class="btn btn-dark my-5">Change</button>
                </form>
            <?php endif ?>
            <?php 
            if(isset($_SESSION["greske"])){
                print_r($_SESSION["greske"]);
                unset($_SESSION["greske"]);
        } ?>
        </div>
    </div>
    <?php else : header("Location:../index.php?page=home"); ?>
    <?php endif ?>
</div>