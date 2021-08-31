<?php
session_start();
ob_start();
require_once("config/connection.php");
require_once("modules/functions.php");
require_once("views/fixed/head.php");

//navigacioni meni
if (isset($_GET["page"])) :
    $page = $_GET["page"];
    if ($page == "home") : ?>
        <nav class="navbar navbar-expand-lg navbar-light h1 mb-5 pt-5" id="navigacija">
            <?php require_once("views/fixed/nav.php"); ?>
        </nav>
    <?php else : ?>
        <div class="omotac mb-3">
            <nav class="navbar navbar-expand-lg navbar-light mb-5 pt-5 container-fluid">
                <a href="index.php">
                    <img src="assets/img/LOGO.png" alt="Logo" class="manje" title="Home page">
                </a>
                <?php require_once("views/fixed/nav.php"); ?>
            </nav>
        </div>
    <?php endif ?>
<?php else : ?>
    <nav class="navbar navbar-expand-lg navbar-light  h1 mb-5 pt-5 " id="navigacija">
        <?php require_once("views/fixed/nav.php"); ?>
    </nav>
<?php endif ?>

<!--sadrzaj -->
<?php
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'home':
            require_once("views/fixed/home.php");
            break;
        case 'categories':
            require_once("views/pages/categories.php");
            break;
        case 'products':
            require_once("views/pages/products.php");
            break;
        case 'product':
            require_once("views/pages/product.php");
            break;
        case 'contact':
            require_once("views/pages/contact.php");
            break;
        case 'register':
            require_once("views/pages/register.php");
            break;
        case 'login':
            require_once("views/pages/login.php");
            break;
        case 'logout':
            require_once("modules/logout.php");
            break;
        case 'survey':
            require_once("views/pages/korisnik/anketa.php");
            break;
        case 'cart':
            require_once("views/pages/korisnik/korpa.php");
            break;
        case 'admin-products':
            require_once("views/pages/admin/products/products.php");
            break;
        case 'admin-users':
            require_once("views/pages/admin/users/users.php");
            break;
        case 'admin-surveys':
            require_once("views/pages/admin/surveys/surveys.php");
            break;
        case 'admin-orders':
            require_once("views/pages/admin/orders/orders.php");
            break;
        case 'admin-contact':
            require_once("views/pages/admin/messages/contact.php");
            break;
        case 'products-update':
            require_once("views/pages/admin/products/update.php");
            break;
        case 'users-update':
            require_once("views/pages/admin/users/update.php");
            break;
        case 'new-product':
            require_once("views/pages/admin/products/new.php");
            break;

        //najbolje za kraj
        case 'author':require_once("views/pages/author.php");break;    

        default:
            require_once("views/fixed/home.php");
            break;
    }
} else {
    require_once("views/fixed/home.php");
}
?>
<!-- futer -->
<?php require_once("views/fixed/footer.php"); ?>