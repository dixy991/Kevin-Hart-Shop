<div class="omotac border-top">
<?php if (isset($_SESSION["korisnik"]) && $_SESSION['korisnik']->uloga == 'Admin') : ?>
    <div class="row">
        <div class="col-lg-12 text-center my-5">
            <h1>Kinda like inbox</h1>
        </div>

        <div class="col-lg-12 text-center">
            <table>
                <tr class=" border-bottom">
                    <th class=" py-4">Name</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Message</th>
                    <th colspan="2">Do sam changes</th>
                </tr>
                <?php $sve = $conn->query("select * from kontaktiranje")->fetchAll(); ?>
                <?php if (true) : ?>
                    <?php foreach ($sve as $s) : ?>

                        <tr>
                            <td><?= $s->ime ?></td>
                            <td><?= $s->email ?></td>
                            <td><?= $s->datum ?></td>
                            <td><?= $s->poruka ?></td>
                            <td>
                                <?php if ($s->seen == 0) : ?>
                                    <form action="modules/admin/messages/change.php" method="post">
                                        <input type="hidden" name="idPoruke" value="<?= $s->idKontaktiranje ?>" />
                                        <button type="submit" name="seenuj" class="btn btn-dark my-5 procitajPoruku"> New</button>
                                    </form>
                                <?php else : ?>
                                    Seen
                                <?php endif ?>
                            </td>
                            <td>
                                <form action="modules/admin/messages/change.php" method="post">
                                    <input type="hidden" name="idPoruke" value="<?= $s->idKontaktiranje ?>" />
                                    <button type="submit" name="delete" class="btn btn-dark my-5">Delete me</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php else : ?>
                    <tr>
                        <td colspan="5">No new messages</td>
                    </tr>
                <?php endif ?>

            </table>
        </div>
        <div class="col-lg-12 text-center crveno">
        <?php
        if ((isset($_SESSION['greske']))) {
            print_r($_SESSION['greske']);
            unset($_SESSION["greske"]);
        }
        if ((isset($_SESSION['uspeh']))) {
            print_r($_SESSION['uspeh']);
            unset($_SESSION["uspeh"]);
        }
        ?>
        </div>
    </div>
    <?php else : header("Location:../index.php?page=home"); ?>
    <?php endif ?>
</div>