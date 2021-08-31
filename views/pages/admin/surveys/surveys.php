<div class="omotac border-top">
<div class="container">
<?php if (isset($_SESSION["korisnik"]) && $_SESSION['korisnik']->uloga == 'Admin') :  ?>
    <div class="row text-center">
    
        <div class="col-lg-6 mt-5">
            <h1>New survey</h1>
            <form action="#">
                <input type="text" name="pitanje" id="pitanje" placeholder="Question?"><span></span><br>
                <textarea name="odgovori" id="odgovori" cols="30" rows="10" placeholder="Responses: 1/2/3..."></textarea><span></span><br>
                <button type="button" id="unosAnkete" class="btn btn-dark my-5">Insert</button>
                <p id="rizalt"></p>
            </form>
        </div>

        <div class="col-lg-6 my-5">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="ml-5">Currently active</h2>
                    <form action="modules/admin/surveys/change.php" method="post">
                        <?php $sveankete = $conn->query("select * from anketa")->fetchAll(); ?>
                        <select name="anketa" id="ddlAnketaActivate" class="mt-5 ml-5">
                            <?php foreach ($sveankete as $sa) : ?>
                                <?php if ($sa->aktivna == 1) : ?>
                                    <option value="<?= $sa->idAnkete ?>" selected="selected"><?= $sa->pitanje ?></option>
                                <?php else : ?>
                                    <option value="<?= $sa->idAnkete ?>"><?= $sa->pitanje ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select><br>
                        <button type="submit" name="aktiviraj" class="btn btn-dark my-5 ml-5">Activate</button>
                        <button type="submit" name="deaktiviraj" class="btn btn-dark my-5 mx-5">Deactivate</button>
                        <button type="submit" name="izbrisi" class="btn btn-dark my-5">Delete</button>
                        <p class="ml-5">

                            <?php
                            if (isset($_SESSION['uspeh'])) {
                                print_r($_SESSION['uspeh']);
                                unset($_SESSION['uspeh']);
                            }
                            if (isset($_SESSION['greske'])) {
                                print_r($_SESSION['greske']);
                                unset($_SESSION['greske']);
                            }
                            ?>
                        </p>
                    </form>
                </div>
                <div class="col-lg-12 mt-2 ml-5">
                    <h2>Results</h2>
                    <?php $sveankete = $conn->query("select * from anketa")->fetchAll(); ?>
                    <select name="" id="ddlAnketaResults" class="mt-5">
                        <option value="0">Choose</option>
                        <?php foreach ($sveankete as $sa) : ?>
                            <option value="<?= $sa->idAnkete ?>"><?= $sa->pitanje ?></option>
                        <?php endforeach; ?>
                    </select><br>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
            <div class="col-lg-6 mx-auto py-3" id="svirezultati">
            </div>
        </div>
    <?php
    if(isset($_SESSION["greske"])){
        foreach($_SESSION["greske"] as $greska){
            echo $greska;
           }
        unset($_SESSION["greske"]);
    }
    ?>
    
    <?php else : header("Location:../index.php?page=home"); ?>
    <?php endif ?>
</div>
</div>