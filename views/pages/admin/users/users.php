<div class="omotac border-top">
<div class="container">
<?php  if (isset($_SESSION["korisnik"]) && $_SESSION['korisnik']->uloga == 'Admin') :  ?>
    <div class="row">
        <div class="col-lg-12 text-center my-5">
            <h1>All users</h1>
        </div>
        <div class="col-lg-12 text-center">
            <table >
                <tr  class=" border-bottom">
                    <th class=" py-4">Name</th>
                    <th>Last name</th>
                    <th>User name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th colspan="2">Do sam changes</th>
                </tr>
                <?php $korisnici = $conn->query("select * from korisnik k inner join uloga u on k.ulogaID=u.idUloga")->fetchAll(); ?>
                <?php foreach ($korisnici as $k) : ?>
                    <tr>
                        <td><?= $k->ime ?></td>
                        <td><?= $k->prezime ?></td>
                        <td><?= $k->username ?></td>
                        <td><?= $k->email ?></td>
                        <td><?= $k->naziv ?></td>
                        <td>
                            <form action="index.php?page=users-update" method="post">
                                <input type="hidden" name="idKoris" value="<?= $k->idKorisnik ?>" />
                                <button type="submit" name="apdejtuj" class="btn btn-dark my-5">Update me</button>
                            </form>
                        </td>
                        <td>
                            <form action="modules/admin/users/delete.php" method="post">
                                <input type="hidden" name="idKoris" value="<?= $k->idKorisnik ?>" />
                                <button type="submit" name="delete" class="btn btn-dark my-5">Delete me</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php
        if(isset($_SESSION["greske"])){
            print_r($_SESSION["greske"]);
            unset($_SESSION["greske"]);
        }
        ?>
    </div>
    <?php else : header("Location:../index.php?page=home"); ?>
    <?php endif ?>
</div>
</div>