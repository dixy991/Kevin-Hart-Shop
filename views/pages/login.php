<div class="omotac border-top my-5">
<div class="container pt-5">
    <div class="row ">
        <div class="col-lg-6 mx-auto ">
            <div class="row">
                <div class="col-lg-6">
                    <h2>Log in</h2>
                </div>
                <div class="col-lg-6 pt-3 text-right">
                    <a href="index.php?page=register" class="crveno">Create account</a>
                </div>
            </div>
            <form action="modules/login.php" onsubmit=" return proveraLogina()" class="text-center" method="post">
                <input type="text" name="mejl" id="mejl" placeholder="Email" class="form-control">
                <span></span>
                <input type="password" name="sifra" id="sifra" placeholder="Password" class="form-control">
                <span></span><br>
                <button type="submit" name="btnulogujse" class="btn btn-dark my-3">Sign in</button><br>
                <a href="index.php" class="crveno">Return to store</a>
                <div id="poruka" class="mt-3 py-3 ">
                    <?php
                        if(isset($_SESSION["greske"])){
                            foreach($_SESSION["greske"] as $greska){
                                echo $greska;
                               }
                            unset($_SESSION["greske"]);
                        }
                    ?>
                </div>
            </form>
        </div>
    </div>
</div>
</div>