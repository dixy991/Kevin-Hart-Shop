<?php ?>
<button class="navbar-toggler bg-danger" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon "></span>
</button>
<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto ml-5">
        <?php
        $rmeni = $conn->query("select * from meni where roditelj is null order by pozicija")->fetchAll();
        foreach ($rmeni as $rm) :

            if ($rm->naziv == "Account") {
                if (isset($_SESSION['korisnik'])) {
                    continue;
                }
            }
            if (($rm->naziv == "User")) {
                if (!isset($_SESSION['korisnik'])) {
                    continue;
                } elseif ($_SESSION['korisnik']->uloga == 'Admin') {
                    continue;
                }
            }

            if ($rm->naziv == "Admin panel") {
                if (!isset($_SESSION['korisnik'])) {
                    continue;
                } elseif ($_SESSION['korisnik']->uloga == 'Korisnik') {
                    continue;
                }
            }

            if ($rm->naziv == "Contact") {
                if (isset($_SESSION['korisnik']) && ($_SESSION['korisnik']->uloga == 'Admin')) {
                    continue;
                }
            }

            if ($rm->putanja == "#") {
                $rpodmeni = $conn->prepare("SELECT * FROM meni WHERE roditelj=:roditelj ORDER BY pozicija");
                $rpodmeni->bindParam(":roditelj", $rm->idMenija);
                $rpodmeni->execute();
                if ($rpodmeni->rowCount() > 0) {
                    $rpodmeni = $rpodmeni->fetchAll();
                    echo "<li class='nav-item dropdown '>
                        <a class='nav-link crveno h4 dropdown-toggle' href='$rm->putanja' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        $rm->naziv
                        </a><div class='dropdown-menu border' aria-labelledby='navbarDropdown'>";
                    foreach ($rpodmeni as $rpm) {
                        echo "<a class='dropdown-item crveno py-2 crno' href='$rpm->putanja'>$rpm->naziv</a>";
                    }
                }
            } else {
                echo "<li class='nav-item active'>
                <a class='nav-link crveno h4' href='$rm->putanja'>  $rm->naziv <span class='sr-only'>(current)</span></a>
                </li>";
            }
        endforeach ?>
        <?php
        if (isset($_SESSION['korisnik']) && ($_SESSION['korisnik']->uloga == 'Admin')) {
            echo "<li class='nav-item active'>
                <a class='nav-link crveno h4' href='index.php?page=logout'>  Logout <span class='sr-only'>(current)</span></a>
                </li>";
        }
        ?>
    </ul>
    <div class="mr-3 mreze ml-5">
        <a href="index.php?page=cart">
            <i id="ukorpi"></i>
            <i class="crveno fa fa-shopping-cart ml-1 mr-4" aria-hidden="true"></i>
        </a>
    </div>
</div>