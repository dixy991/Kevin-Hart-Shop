<div class="omotac border-top">
<?php if (isset($_SESSION["korisnik"]) && $_SESSION['korisnik']->uloga == 'Admin') : ?>
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center my-5">
            <h1>All orders</h1>
        </div>

        <div class="col-lg-12 text-center">
            <table>
                <tr class=" border-bottom">
                    <th class=" py-4">First Name</th>
                    <th>Last Name</th>
                    <th>Date</th>
                    <th colspan="2">Do sam changes</th>
                </tr>
                <?php $sve = $conn->query("select * from porudzbina p inner join korisnik k on p.korisnikID=k.idKorisnik group by k.idKorisnik")->fetchAll(); ?>
                <?php foreach ($sve as $s) : ?>
                    <tr>
                        <td><?= $s->ime ?></td>
                        <td><?= $s->prezime ?></td>
                        <td><?= $s->datum ?></td>
                        <td>
                            <button type="button" data-idkorisnika="<?= $s->idKorisnik ?>" name="detalji" data-toggle="modal" data-target="#exampleModal" class="detalji btn btn-dark my-5">Details</button>
                        </td>
                        <td>
                            <form action="modules/admin/orders/change.php" method="post">
                                <input type="hidden" name="idKoris" value="<?= $s->idKorisnik ?>" />
                                <button type="submit" name="delete" class="btn btn-dark my-5">Delete me</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div id="detalji-por">
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                            <button type="button" class="close crveno" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if ((isset($_SESSION['greske']))) {
            print_r($_SESSION['greske']);
            unset($_SESSION["greske"]);
        }
        if ( (isset($_SESSION['uspeh']))) {
            print_r($_SESSION['uspeh']);
            unset($_SESSION["uspeh"]);
        }
        ?>
    </div>
    <?php else : header("Location:../index.php?page=home"); ?>
    <?php endif ?>
</div>
</div>