<div class="container">
    <div class="row">
    <?php
        if (isset($_SESSION["korisnik"]) && $_SESSION['korisnik']->uloga == 'Admin') :
            ?>
        <div class="col-lg-6 mx-auto text-center">
            <?php if (isset($_POST['apdejtuj'])) : ?>
                <form action="modules/admin/users/update.php" method="post">
                    <?php
                        $id = $_POST["idKoris"];
                        $izabran = $conn->prepare("select * from korisnik k inner join uloga u on k.ulogaID=u.idUloga where idKorisnik=:id");
                        $izabran->bindParam(":id", $id);
                        $izabran->execute();
                        if ($izabran->rowCount() == 1) :
                            $podaci = $izabran->fetch(); ?>
                        <input type="hidden" name="id" value="<?= $podaci->idKorisnik ?>">
                        <input type="text" name="ime" id="" value="<?= $podaci->ime ?>" /><br>
                        <input type="text" name="prezime" id="" value="<?= $podaci->prezime ?>" /><br>
                        <input type="text" name="username" id="" value="<?= $podaci->username ?>" /><br>
                        <input type="text" name="email" id="" value="<?= $podaci->email ?>" /><br>
                        
                        <?php
                                $roles = $conn->query("select * from uloga")->fetchAll();
                                $role = $conn->prepare("select u.idUloga as uid from uloga u inner join korisnik k on k.ulogaID=u.idUloga where k.idKorisnik=:id");
                                $idk = $podaci->idKorisnik;
                                $role->bindParam(":id", $idk);
                                $role->execute();
                                echo "<select name='uloga' id=''>";
                                foreach ($roles as $rs) :
                                    ?>
                            <?php
                                        foreach ($role as $r) {
                                            $druga = $r->uid;
                                        }
                                        $prva = $rs->idUloga;
                                        if ($prva == $druga) : ?>
                                 <option value="<?= $rs->idUloga ?>" selected><?= $rs->naziv ?></option>
                            <?php else : ?>
                            <option value="<?= $rs->idUloga ?>"><?= $rs->naziv ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                        </select><br><br>
                        <label for="active">Active?</label><br>
                        <?php if ($podaci->active == 1) : ?>
                            <input type="radio" name="active" value="1" checked="checked">Yes
                            <input type="radio" name="active" value="0">Nope
                        <?php else : ?>
                            <input type="radio" name="active" value="1">Yes
                            <input type="radio" name="active" value="0" checked="checked">Nope
                        <?php endif ?>
                    <?php endif ?><br>
                    <button type="submit" name="update" class="btn btn-dark my-5">Change</button>
                </form>
            <?php endif ?>
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