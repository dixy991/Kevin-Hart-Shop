<div class="omotac">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb border-top py-3">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Collections</li>
        </ol>
    </nav>
    <div class="row border-bottom my-5">
        <div class="col-lg-6">
        <h1>Categories</h1>
        </div>
        <div class="col-lg-6 text-right">
            <p>
                <i>"Make your mirrors look sexier... "</i>
            </p>
        </div>
    </div>
    <div class="row text-center my-5">
        
        <?php $rkategorije = $conn->query("select k.idKategorije as kid,k.naziv as katnaz, p.idProizvoda as pId,p.naziv as pnaz,p.cena as pcena,s.putanja as sput,s.opis as altic from  kategorija k inner join proizvod p on k.idKategorije=p.kategorijeID inner join slika s on p.slikeID=s.idSlike group by k.naziv order by k.naziv desc")->fetchAll(); ?>

        <div class="row">
            <?php foreach ($rkategorije as $rkat) : ?>
                <div class="col-lg-3">
                    <a href="index.php?page=products&id=<?= $rkat->kid ?>">
                        <figure>
                            <img src="assets/img/<?= $rkat->sput ?>" alt="<?= $rkat->altic ?>" class="w-100">
                            <caption><?= $rkat->katnaz ?></caption>
                        </figure>
                    </a>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>