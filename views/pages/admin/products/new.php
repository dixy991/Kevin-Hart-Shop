<div class="container">
<?php if (isset($_SESSION["korisnik"]) && $_SESSION['korisnik']->uloga == 'Admin') : ?>
    <div class="row">
        <div class="col-lg-6 mx-auto text-center">
            <form action="modules/admin/products/insert.php" method="post" onsubmit="return ubaciProizvodUbazu()" >
                <?php
                $postojeci = $conn->query("select k.idKategorije as kid,k.naziv as katnaz,p.idProizvoda as pId,p.opis as popis,p.naziv as pnaz,p.cena as pcena,s.putanja as sput from  kategorija k inner join proizvod p on k.idKategorije=p.kategorijeID inner join slika s on p.slikeID=s.idSlike")->fetchAll(); ?>
                <label for="naziv">Name</label><br>
                <input type="text" name="naziv" id="naziv"><span></span><br>
                <label for="cena">Price</label><br>
                <input type="text" name="cena" id="cena" /><span></span><br>
                <label for="opis">Description</label><br>
                <textarea name="opis" id="opis" cols="30" rows="10" placeholder="Separate with dots.!"></textarea><br>
                <label for="kategorije">Category</label><br>
                <?php
                $kategorije = $conn->query("select idKategorije,naziv from kategorija")->fetchAll();
                echo "<select name='kategorije' id='ddlKategorije'><option value='0'>Choose</option>";
                foreach ($kategorije as $k) :
                    ?>
                    <option value="<?= $k->idKategorije ?>"><?= $k->naziv ?></option>

                <?php endforeach ?>
                </select><br><br>
                <div id="sizes">
                <label for="velicine[]">Available in sizes:</label><br>
                <?php $vel = $conn->query("select * from velicina")->fetchAll();
                    foreach ($vel as $v) : ?>
                        <input type="checkbox" class="mx-3" name="velicine[]" id="" value="<?= $v->idVelicine ?>"><i class="text-danger"><?= $v->skracenica ?></i>
                    <?php endforeach; ?>
                <br><br>
                </div>
                <div id="stanje">
                <input type="radio" class="mx-3" name="stanje" id="" value="Yes"><i class="text-success">In stock</i>
                <input type="radio" class="mx-3" name="stanje" id="" value="No"><i class="text-danger">Not in stock</i>
                </div>
                <label for="putanja">Picture src:</label><br>
                <input type="text" name="putanja" id="putanja" /><span></span><br>
                <label for="slikopis">Picture alt:</label><br>
                <input type="text" name="slikopis" id="slikopis" /><br>
                <button type="submit" name="insert" class="btn btn-dark my-5">Insert</button>
                <p>
                     <?php
                    if (isset($_SESSION['uspeh'])) {
                        print_r($_SESSION['uspeh']);
                        unset($_SESSION['uspeh']);
                    }
                    if (isset($_SESSION['greske'])) {
                        foreach($_SESSION["greske"] as $greska){
                                echo $greska;
                        }
                        unset($_SESSION['greske']);
                    }
                    ?>
                </p>
            </form>
        </div>
    </div>
    <?php else : header("Location:../index.php?page=home"); ?>
    <?php endif ?>
</div>