<div class="omotac border-top">
    <!-- mora php pre tj prvi inace se ucitava normalno stranica i header je vec poslat -->
    <?php if (isset($_SESSION["korisnik"]) && $_SESSION['korisnik']->uloga == 'Admin') : ?>
        <div class="row">
            <div class="col-lg-12 mb-5">
                <div class="row">
                    <div class="col-lg-6 my-5">
                        <h1>
                            <a class="crveno" href="index.php?page=new-product">New product</a>
                        </h1>
                    </div>
                    <div class="col-lg-6 my-5 text-right">
                        <h1>All products</h1>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 text-center">
                <table>
                    <tr class=" border-bottom">
                        <th class=" py-4">Name</th>
                        <th>Price</th>
                        <th>Photo</th>
                        <th>Category</th>
                        <th>In stock</th>
                        <th colspan="2">Do sam changes</th>
                    </tr>
                    <?php $sve = $conn->query("select k.idKategorije as kid,k.naziv as katnaz,p.idProizvoda as pId,p.naziv as pnaz,p.cena as pcena,s.putanja as sput from  kategorija k inner join proizvod p on k.idKategorije=p.kategorijeID inner join slika s on p.slikeID=s.idSlike")->fetchAll(); ?>
                    <?php foreach ($sve as $s) : ?>
                        <tr>
                            <td class="text-left"><?= $s->pnaz ?></td>
                            <td>$<?= $s->pcena ?></td>
                            <td>
                                <img src="assets/img/<?= $s->sput ?>" alt="" class="smanji" />
                            </td>
                            <td><?= $s->katnaz ?></td>
                            <td>
                                <ul class="nodec">
                                    <?php $vel = $conn->prepare("select v.skracenica as velicina,s.naStanju as ima from stanjeproizvoda s left outer join velicina v on s.velicinaID=v.idVelicine where s.proizvodID=:id");
                                            $id = $s->pId;
                                            $vel->bindParam(":id", $id);
                                            $vel->execute();
                                            foreach ($vel as $v) :
                                                ?>
                                        <?php if (($v->ima == 1) && ($v->velicina != null)) : ?>
                                            <li class="text-success"><?= $v->velicina ?></li>
                                        <?php elseif (($v->ima == 1) && ($v->velicina == null)) : ?>
                                            <li class="text-success">In stock</li>
                                        <?php elseif (($v->ima == 0) && ($v->velicina == null)) : ?>
                                            <li class="text-danger">Not available</li>
                                        <?php else : ?>
                                            <li class="text-danger"><?= $v->velicina ?></li>
                                        <?php endif ?>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            <td>
                                <form action="index.php?page=products-update" method="post">
                                    <input type="hidden" name="idProiz" value="<?= $s->pId ?>" />
                                    <button type="submit" name="apdejtuj" class="btn btn-dark my-5">Update me</button>
                                </form>
                            </td>
                            <td>
                                <form action="modules/admin/products/delete.php" method="post">
                                    <input type="hidden" name="idProiz" value="<?= $s->pId ?>" />
                                    <button type="submit" name="delete" class="btn btn-dark my-5">Delete me</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <?php
                if (isset($_SESSION["greske"])) {
                    print_r($_SESSION["greske"]);
                    unset($_SESSION["greske"]);
                } ?>
        </div>
    <?php else : header("Location:../../../../index.php?page=home"); ?>
    <?php endif ?>
</div>