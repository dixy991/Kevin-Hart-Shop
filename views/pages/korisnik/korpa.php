<div class="omotac border-top">
    <div class="row ">
        <div class="col-lg-12 my-5">
            <h2>Shopping cart</h2>
        </div>
        <?php
        if (isset($_SESSION["korisnik"]) && $_SESSION['korisnik']->uloga == 'Korisnik') :
            ?>
            <div class="col-lg-12 text-center" id="proizvodiKorpe"></div>
            <div class="col-lg-12">
                <p id="jel-naruceno" class="text-center"></p>
                <button type="button" id="kupi" data-idkorisnika="<?= $_SESSION["korisnik"]->kid ?>" class="btn btn-dark my-5 float-right">Buy</button>
            </div>
        <?php else : ?>
            <div class="col-lg-12">
                <p>Your cart is currently empty!</p>Continue browsing <a href="index.php?page=categories" class="crveno">here</a>
                <p></p>
                <p>**Gotta <a href="index.php?page=login" class="crveno">login</a> first!</p>
            </div>
        <?php endif ?>
        <?php
        if(isset($_SESSION["greske"])){
            foreach($_SESSION["greske"] as $greska){
                echo $greska;
               }
            unset($_SESSION["greske"]);
        }
        ?>
    </div>
</div>