window.onload = function () {
    let url = location.href;
    console.log(url)
    sviProizvodi=dohvatiSveProizvodeIzKorpe();
    if(!sviProizvodi){
        sviProizvodi=[];
        localStorage.setItem("products", JSON.stringify(sviProizvodi));
    }
    ispisMreza();
    prebrojUkorpi();
    if (url.indexOf("register") != -1) {
        $("#btnkreirajnalog").click(proveraRegistracije)
    }
    if (url.indexOf("survey") != -1) {
        $("#glasaj").click(proveraGlasa);
        $("#rezultati").click(dohvatiRezultate);
        $("#unosAnkete").click(ubaciNovuAnketuUbazu);
        $("#ddlAnketaResults").change(dohvatiRezultate);
    }
    if (url.indexOf("contact") != -1) {
        $("#kontaktiraj").click(proveraPoruke)
    }
    if (url.indexOf("orders") != -1) {
        $(".detalji").click(dohvatiDetaljePorudzbine)
    }
    if ((url.indexOf("product") != -1) || (url.indexOf("home") != -1)) {
        $("#dodajUkorpu").click(dodajUkorpu);
    }
    if (url.indexOf("cart") != -1) {
        prespiKorpu();
        $("#kupi").hide();
        $("#kupi").click(naruci);
    }
    $("table tr:not(:first)").hover(function () {
        $(this).addClass("bojaReda")
    }, function () {
        $(this).removeClass("bojaReda")
    });
    $(".procitajPoruku").hover(function () {
        $(this).html("See")
    }, function () {
        $(this).html("New")
    });
    $("#velicine").change(imaIlnema);
    $("#ddlKategorije").change(trebaIlneTreba);
}
//provere
function proveraRegistracije() {
    let validno = true;

    let ime = $("#ime").val().trim();
    let prezime = $("#prezime").val().trim();
    let username = $("#username").val().trim();
    let mejl = $("#mejl").val().trim();
    let sifra = $("#sifra").val().trim();

    let reImePrezime = /^[A-Z][a-z]+(\s[A-Z][a-z]+)*$/;
    let remejl = /^[A-z0-9\.\_\-]+\@[A-z0-9\.\_\-]+(\.[A-z]{2,})*$/;
    let reSifra = /^[A-z0-9]{7,50}$/;
    let reUser = /^[\w\d\.\-\@\_]{7,50}$/;

    if (ime == "") {
        validno = false;
        $("#ime").css("border-bottom", "2px solid red");
    }
    else if (!reImePrezime.test(ime)) {
        validno = false;
        $("#ime").css("border-bottom", "2px solid red");
        $("#ime").next().html("Gotta start with an uppercase letter!");
    }
    else {
        $("#ime").css("border-bottom", "2px solid green");
        $("#ime").next().html("");
    }

    if (prezime == "") {
        validno = false;
        $("#prezime").css("border-bottom", "2px solid red");
    }
    else if (!reImePrezime.test(prezime)) {
        validno = false;
        $("#prezime").css("border-bottom", "2px solid red");
        $("#prezime").next().html("Gotta start with an uppercase letter!");
    }
    else {
        $("#prezime").css("border-bottom", "2px solid green");
        $("#prezime").next().html("");
    }

    if (sifra == "") {
        validno = false;
        $("#sifra").css("border-bottom", "2px solid red");
    }
    else if (!reSifra.test(sifra)) {
        validno = false;
        $("#sifra").css("border-bottom", "2px solid red");
        $("#sifra").next().html("At least 7 characters!<br/>**Only letters and numbers!");
    }
    else {
        $("#sifra").css("border-bottom", "2px solid green");
        $("#mejl").next().html("");
    }

    if (username == "") {
        validno = false;
        $("#username").css("border-bottom", "2px solid red");
    }
    else if (!reUser.test(username)) {
        validno = false;
        $("#username").css("border-bottom", "2px solid red");
        $("#username").next().html("At least 7 characters!");
    }
    else {
        $("#username").css("border-bottom", "2px solid green");
        $("#username").next().html("");
    }

    if (mejl == "") {
        validno = false;
        $("#mejl").css("border-bottom", "2px solid red");
    }
    else if (!remejl.test(mejl)) {
        validno = false;
        $("#mejl").css("border-bottom", "2px solid red");
        $("#mejl").next().html("Gotta have @ in it!<br/> Like this: try.again@gmail.com");
    }
    else {
        $("#mejl").css("border-bottom", "2px solid green");
        $("#mejl").next().html("");
    }

    if (validno) {
        var obj = {
            ime: $("#ime").val(),
            prezime: $("#prezime").val(),
            username: $("#username").val(),
            mejl: $("#mejl").val(),
            sifra: $("#sifra").val(),
            send: true
        };

        $.ajax({
            url: "modules/register.php",
            method: "POST",
            data: obj,
            success: function (data) {
                $("#poruka").html("Successfully made!");
                console.log(data)
            },
            error: function (xhr, status, error) {
                var poruka = "Oops, an error occurred...";
                console.log(xhr)
                console.log(xhr.responseJSON)
                var i;
                switch (xhr.status) {
                    case 404: poruka = "Page not found!"; break;
                    case 409: poruka = "Username or email already exists!"; break;
                    case 422: poruka = "Values are not valid!"; break;
                    case 500: poruka = "Error"; break;
                }
                $("#poruka").css("border","2px solid red");
                $("#poruka").html(poruka + "<br/>");
                for( i of xhr.responseJSON){
                    $("#poruka").append(i);
                }
            }
        })
    }
}
function proveraGlasa() {
    var glas = $("input[name=glas]:checked").val();
    var korisnikId = $("#hiddenIDuser").val();
    console.log(glas)
    console.log(korisnikId)
    if (glas == undefined) {
        $("#glas").html("Gotta vout (wance!)")
    }
    else {
        $("#glas").html("")

        $.ajax({
            url: "modules/vote.php",
            method: "POST",
            data: {
                korisnikId: korisnikId,
                glas: glas,
                send: true
            },
            success: function (data) {
                $("#glas").html("<img src='assets/img/slika1.jpg'/ alt='Really?' class='poquito ml-3'>");
                $("#glas").prepend("Wow...Really dawg?");
                console.log(data)
            },
            error: function (xhr, status, error) {
                var poruka = "Oops, an error occurred...";
                console.log(xhr)
                console.log(xhr.responseJSON)
                var i;
                switch (xhr.status) {
                    case 404: poruka = "Page not found!"; break;
                    case 409: poruka = "Already took a vote man!"; break;
                    case 422: poruka = "Values are not valid!"; break;
                    case 500: poruka = "Error"; break;
                }
                $("#glas").css("color","red");
                $("#glas").html(poruka);
                $("#glas").html(poruka + "<br/>");
                for( i of xhr.responseJSON){
                    $("#glas").append(i);
                }
            }
        })
    }
}
function proveraPoruke() {
    let validno = true;

    let ime = $("#ime").val().trim();
    let mejl = $("#mejl").val().trim();
    let poruka = $("#poruka").val().trim();

    let reIme = /^[A-Z][a-z]+(\s[A-Z][a-z]+)*$/;
    let remejl = /^[A-z0-9\.\_\-]+\@[A-z0-9\.\_\-]+(\.[A-z]{2,})+$/;

    if (ime == "") {
        validno = false;
        $("#ime").css("border-bottom", "2px solid red");
    }
    else if (!reIme.test(ime)) {
        validno = false;
        $("#ime").css("border-bottom", "2px solid red");
        $("#ime").next().html("Gotta start with an uppercase letter!");
    }
    else {
        $("#ime").css("border-bottom", "2px solid green");
        $("#ime").next().html("");
    }

    if (mejl == "") {
        validno = false;
        $("#mejl").css("border-bottom", "2px solid red");
    }
    else if (!remejl.test(mejl)) {
        validno = false;
        $("#mejl").css("border-bottom", "2px solid red");
        $("#mejl").next().html("Gotta have @ in it!<br/> Like this: try.again@gmail.com");
    }
    else {
        $("#mejl").css("border-bottom", "2px solid green");
        $("#mejl").next().html("");
    }

    if (poruka == "") {
        validno = false;
        $("#poruka").css("border-bottom", "2px solid red");
        $("#poruka").next().html("Write something");
    }
    else {
        $("#poruka").css("border-bottom", "2px solid green");
        $("#poruka").next().html("");
    }

    if (validno) {
        var obj = {
            ime: $("#ime").val(),
            email: $("#mejl").val(),
            poruka: $("#poruka").val(),
            send: true
        };

        $.ajax({
            url: "modules/contact.php",
            method: "POST",
            data: obj,
            success: function (data) {
                $("#odgovor").html("Successfully sent!");
                console.log(data)
            },
            error: function (xhr, status, error) {
                var poruka = "Oops, an error occurred...";
                console.log(xhr)
                console.log(xhr.responseJSON)
                var i;
                switch (xhr.status) {
                    case 404: poruka = "Page not found!"; break;
                    case 422: poruka = "Values are not valid!"; break;
                    case 500: poruka = "Error"; break;
                }
                $("#odgovor").css("color","red");
                $("#odgovor").html(poruka + "<br/>");
                for( i of xhr.responseJSON){
                    $("#odgovor").append(i);
                }
            }
        })
    }
}
function proveraLogina() {
    let validno = true;

    let mejl = $("#mejl").val().trim();
    let sifra = $("#sifra").val().trim();

    let remejl = /^[A-z0-9\.\_\-]+\@[A-z0-9\.\_\-]+(\.[A-z]{2,})*$/;

    let reSifra = /^[A-z0-9]{7,50}$/;

    if (mejl == "") {
        validno = false;
        $("#mejl").css("border-bottom", "2px solid red");
    }
    else if (!remejl.test(mejl)) {
        validno = false;
        $("#mejl").css("border-bottom", "2px solid red");
        $("#mejl").next().html("Gotta have @ in it!<br/> Like this: try.again@gmail.com");
    }
    else {
        $("#mejl").css("border-bottom", "2px solid green");
        $("#mejl").next().html("");
    }
    if (sifra == "") {
        validno = false;
        $("#sifra").css("border-bottom", "2px solid red");
    }
    else if (!reSifra.test(sifra)) {
        validno = false;
        $("#sifra").css("border-bottom", "2px solid red");
        $("#sifra").next().html("At least 7 characters!<br/>**Only letters and numbers!");
    }
    else {
        $("#sifra").css("border-bottom", "2px solid green");
        $("#sifra").next().html("");
    }

    if (validno) {
        return true;
    }
    else {
        return false;
    }
}
//dohvatanja
function dohvatiRezultate() {
    var idAnkete;

    if (location.href.indexOf("admin-surveys") != -1) {
        idAnkete = $("#ddlAnketaResults").val();
    }
    else {
        idAnkete = $("#hiddedIDsurvey").val();
    }

    console.log(idAnkete)
    $.ajax({
        url: "modules/voteresults.php",
        method: "POST",
        data: {
            idAnkete: idAnkete,
            send: true
        },
        success: function (data) {
            $("#svirezultati").html(ispisRezultata(data));
            if (location.href.indexOf("page=survey") != -1) {
                $("#svirezultati").css("border","2px solid white");
            }
            console.log(data)
        },
        error: function (xhr, status, error) {
            var poruka = "Oops, an error occurred...";
            console.log(xhr.responseJSON)
            switch (xhr.status) {
                case 404: poruka = "Page not found!"; break;
                case 500: poruka = "Error"; break;
            }
            $("#svirezultati").html(poruka);
        }
    })
}
function dohvatiSveProizvodeIzKorpe() {
    return JSON.parse(localStorage.getItem("products"));
}
function dohvatiDetaljePorudzbine() {
    var idKorisnika = $(this).data("idkorisnika");

    console.log(idKorisnika)
    $.ajax({
        url: "modules/admin/orders/change.php",
        method: "POST",
        data: {
            idKorisnika: idKorisnika,
            send: true
        },
        success: function (data) {
            $(".modal-body").html(ispisPorudzbina(data));
            console.log(data)
        }
        ,error: function (xhr, status, error) {
            var poruka = "Oops, an error occurred...";
            console.log(xhr.responseJSON)
            switch (xhr.status) {
                case 404: poruka = "Page not found!"; break;
                case 500: poruka = "Error"; break;
            }
            $("#detalji-por").html(poruka);
        }
    })
}
//ispisi
function ispisRezultata(data) {
    let ispis = "<table>";
    data.forEach(element => {
        ispis += `
            <tr>
                <td>${element.odgovor}</td>
                <td>${element.ukupno}</td>
            </tr>
        `;
    });
    return ispis;
}
function ispisMreza() {
    let ispis = "";
    var mreze = ["facebook", "instagram", "twitter"];
    for (let i = 0; i < mreze.length; i++) {
        ispis += `<a href="https://${mreze[i]}.com" target="_blank" ><i class="crveno fa fa-${mreze[i]} mx-2" aria-hidden="true"></i></a>`;
    }
    $(".mreze").append(ispis);
}
function ispisPorudzbina(data) {
    let ispis = `<table>
    <tr>
        <th>Product</th>
        <th></th>
        <th>Size</th>
        <th>Quantity</th>
    </tr>`;
    data.forEach(element => {
        ispis += `
        <tr>
            <td>${element.pnaz}</td>
            <td>
                <img src="assets/img/${element.putanja}" alt="${element.opis}" class="smanji"/>
            </td>
            <td>`;

        if (element.vnaz == null) {
            ispis += "";
        }
        else {
            ispis += `${element.vnaz}`;
        }
        
        ispis+=`</td>
            <td class="text-center">${element.kolicina}</td>
        </tr>
    `;
    });
    ispis += "</table>";
    return ispis;
}
//korpa
function prebrojUkorpi() {
    let suma = dohvatiSveProizvodeIzKorpe();
    let ukupno = 0;
    suma.forEach(element => {
        ukupno += Number(element.kolicina);
    });
    console.log(suma)
    $("#ukorpi").html(ukupno);
    return ukupno;
}
function dodajUkorpu() {
    var idP = $(this).data("idproizvoda");
    var velicina = $("#velicine option:selected").val();
    var kolicina = $("#kolicinski").val();
    var sviProizvodi = dohvatiSveProizvodeIzKorpe();
    console.log(velicina)
    if (kolicina == undefined) {
        kolicina = 1;
    }
    if (sviProizvodi) {
        if (sviProizvodi.filter(p => p.idP == idP && p.velicina == velicina).length) {
            for (let p of sviProizvodi) {
                if (p.idP == idP && p.velicina == velicina) {
                    p.velicina = velicina;
                    p.kolicina = Number(p.kolicina) + Number(kolicina);
                    break;
                }
            }
            localStorage.setItem("products", JSON.stringify(sviProizvodi));
        }
        else {
            sviProizvodi.push({
                idP: idP,
                velicina: velicina,
                kolicina: kolicina
            });
            localStorage.setItem("products", JSON.stringify(sviProizvodi));
        }
    }
    else {
        let products = [];
        products[0] = {
            idP: idP,
            velicina: velicina,
            kolicina: kolicina
        }
        console.log(sviProizvodi)
        localStorage.setItem("products", JSON.stringify(products));
    }
    var povecaj = prebrojUkorpi();

    $("#ukorpi").html(povecaj);
}
function prespiKorpu() {
    var sviProizvodi = dohvatiSveProizvodeIzKorpe();
    if (sviProizvodi && sviProizvodi.length) {
        $.ajax({
            url: "modules/cartproducts.php",
            method: "POST",
            success: function (data) {
                data = data.filter(p => {
                    for (let proizvod of sviProizvodi) {
                        if (proizvod.idP == p.idProizvoda && proizvod.velicina == p.idVelicine) {
                            p.kolicina = proizvod.kolicina;
                            p.velicina = proizvod.velicina;
                            return true;
                        }
                        else if (proizvod.idP == p.idProizvoda && p.idVelicine == null) {
                            p.kolicina = proizvod.kolicina;
                            p.velicina = proizvod.velicina;
                            //quee?
                            return true;
                        }
                    }
                    return false;
                });
                ispisiKorpu(data);
            }
            ,error: function (xhr, status, error) {
                var poruka = "Oops, an error occurred...";
                console.log(xhr.responseJSON)
                switch (xhr.status) {
                    case 404: poruka = "Page not found!"; break;
                    case 500: poruka = "Error"; break;
                }
                $("#proizvodiKorpe").html(poruka);
            }
        })
    }
    else {
        let ispis = `<p>Your cart is currently empty!</p>Continue browsing <a href="index.php?page=categories" class="crveno">here</a><p></p>`;
        $("#proizvodiKorpe").html(ispis)
    }
}
function ispisiKorpu(data) {
    console.log(data)
    let sve = dohvatiSveProizvodeIzKorpe();
    let ispis = `<table class="text-left">
        <tr class=" border-bottom ">
            <th colspan="2" class="py-5">Products</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>
    `;
    data.forEach(element => {
        ispis += `
            <tr>
                <td class="pt-3">
                    <figure class="slikeKorpe">
                    <img src="assets/img/${element.putanja}" alt="${element.opis}" class="manje"/>
                    </figure>
                </td>
                <td>
                    <p class="crveno">${element.naziv}</p>`;

        sve.forEach(p => {
            if (p.idP == element.idProizvoda && p.velicina == element.idVelicine && element.idVelicine != null) {
                ispis += element.velicinaziv + "-(" + element.skracenica + ")" + "<br/><br/>";
            }
        });

        ispis += `
                    <a href="#" data-idproizv="$" class="crveno removeItem" onclick="izbrisiIzKorpe(event,${element.idProizvoda},${element.idVelicine})">Remove</a>
                </td>
                <td class="crveno">$${element.cena}</td>
                <td class="crveno">${element.kolicina}</td>
                <td class="crveno">$${Number(element.cena) * Number(element.kolicina)}</td>
            </tr>
        `;
    });
    ispis += `
        <tr class="border-top mr-5 mt-5">
            <td colspan="4"></td>
            <td class="text-left pt-5" >$`
    var ukupno = 0
    data.forEach(element => {
        ukupno += Number(element.cena) * Number(element.kolicina);
    });
    ispis += ukupno;
    ispis += `</td>
        </tr>
        </table>
    `;
    $("#proizvodiKorpe").html(ispis);
    $("#kupi").show();

}
function izbrisiIzKorpe(e, idP, idV) {
    var sviProizvodi = dohvatiSveProizvodeIzKorpe();
    e.preventDefault();
    console.log(idP, idV)
    var ostali = sviProizvodi.filter(p => p.velicina != idV || p.idP != idP);
    localStorage.setItem("products", JSON.stringify(ostali));
    location.reload();
}
//ubacivanja u bazu
function naruci() {
    var id = $(this).data("idkorisnika");
    console.log(dohvatiSveProizvodeIzKorpe())
    var svizabrani = dohvatiSveProizvodeIzKorpe();
    var sviIp = [], svaKolicina = [], svaVelicina = [];
    svizabrani.forEach(element => {
        sviIp.push(element.idP)
        svaKolicina.push(element.kolicina)
        if (element.velicina == null) {
            svaVelicina.push("null")
        }
        else {
            svaVelicina.push(element.velicina)
        }
    });
    console.log(id + " korisnik| " + sviIp.toString() + " idP|", svaKolicina.toString() + " kol", svaVelicina.toString() + " vel")
    if (dohvatiSveProizvodeIzKorpe().length) {
        $.ajax({
            url: "modules/order.php",
            method: "post",
            dataType: "json",
            data: {
                id: id,
                idP: sviIp.toString(),
                kol: svaKolicina.toString(),
                vel: svaVelicina.toString(),
                send: true,
            },
            success: function (data, status, xhr) {
                if (xhr.status == 201) {
                    $("#jel-naruceno").html("Successfully ordered!");
                }
                localStorage.setItem("products", JSON.stringify([]));
                ispisiKorpu();
            }
            ,error: function (xhr, status, error) {
                var poruka = "Oops, an error occurred...";
                console.log(xhr.responseJSON)
                switch (xhr.status) {
                    case 404: poruka = "Page not found!"; break;
                    case 500: poruka = "Error"; break;
                }
                $("#detalji-por").html(poruka);
            }
        });
    }

}
function ubaciProizvodUbazu() {
    let validno = true;

    let naziv = $("#naziv").val().trim();
    let cena = $("#cena").val().trim();
    let opis = $("#opis").val().trim();
    let kategorija = $("#ddlKategorije").val();
    let src = $("#putanja").val();
    let alt = $("#slikopis").val();
    var selected = [];
    $('input[type=checkbox]:checked').each(function () {
        selected.push($(this).val());
    });
    console.log(selected)

    let reNaziv = /^[A-Z][A-z]{0,99}$/;
    let reCena = /^[\d]{1,25}\.[\d]{2}$/;
    let rePutanje = /^(http(s?):)*([\/|\.|\w|\s|\-])+\.(?:jpg|gif|png)$/;

    if (naziv == "") {
        validno = false;
        $("#naziv").css("border-bottom", "2px solid red");
    }
    else if (!reNaziv.test(naziv)) {
        validno = false;
        $("#naziv").css("border-bottom", "2px solid red");
        $("#naziv").next().html("Gotta start with an uppercase(dont-forget:only 100 letters)!");
    }
    else {
        $("#naziv").css("border-bottom", "2px solid green");
        $("#naziv").next().html("");
    }

    if (cena == "") {
        validno = false;
        $("#cena").css("border-bottom", "2px solid red");
    }
    else if (!reCena.test(cena)) {
        validno = false;
        $("#cena").css("border-bottom", "2px solid red");
        $("#cena").next().html("Numbers only(with 25 digit limit) and 2 digits after fullstop...like this: 27.00");
    }
    else {
        $("#cena").css("border-bottom", "2px solid green");
        $("#cena").next().html("");
    }

    if (opis == "") {
        validno = false;
        $("#opis").css("border-bottom", "2px solid red");
    }
    else {
        $("#opis").css("border-bottom", "2px solid green");
    }

    if (kategorija == "0") {
        validno = false;
        $("#ddlKategorije").css("border-bottom", "2px solid red");
    }
    else {
        $("#ddlKategorije").css("border-bottom", "2px solid green");
    }

    if (src == "") {
        validno = false;
        $("#putanja").css("border-bottom", "2px solid red");
    }
    else if (!rePutanje.test(src)) {
        validno = false;
        $("#putanja").css("border-bottom", "2px solid red");
        $("#putanja").next().html("It can be link or name with only these extensions: jpg|gif|png");
    }
    else {
        $("#putanja").css("border-bottom", "2px solid green");
        $("#putanja").next().html("");
    }
    if (alt == "") {
        validno = false;
        $("#slikopis").css("border-bottom", "2px solid red");
    }
    else {
        $("#slikopis").css("border-bottom", "2px solid green");
    }

    if (selected.length == 0) {
        // validno = false;
        $("input[type=checkbox").next().css("border-bottom", "2px solid red");
    }
    else {
        $("input[type=checkbox").next().css("border-bottom", "2px solid green");
    }

    if (validno) {
        return true;
    }
    else {
        return false;
    }
}
function ubaciNovuAnketuUbazu() {
    let validno = true;

    let pitanje = $("#pitanje").val().trim();
    let odgovori = $("#odgovori").val().trim();

    let rePitanje = /^[\w\d\/\.\_\-]+(\s[\w\d]+)*\?$/;
    let reOdgovori = /^[\w\d\.\_\-]+(\/[\w\d]+){1,}$/;

    if (pitanje == "") {
        validno = false;
        $("#pitanje").css("border-bottom", "2px solid red");
    }
    else if (!rePitanje.test(pitanje)) {
        validno = false;
        $("#pitanje").css("border-bottom", "2px solid red");
        $("#pitanje").next().html("Gotta End with question mark!");
    }
    else {
        $("#pitanje").css("border-bottom", "2px solid green");
        $("#pitanje").next().html("");
    }

    if (odgovori == "") {
        validno = false;
        $("#odgovori").css("border-bottom", "2px solid red");
    }
    else if (!reOdgovori.test(odgovori)) {
        validno = false;
        $("#odgovori").css("border-bottom", "2px solid red");
        $("#odgovori").next().html("At least two of them(and separate them with slashes ' / ' !!! geez...");
    }
    else {
        $("#odgovori").css("border-bottom", "2px solid green");
        $("#odgovori").next().html("");
    }

    if (validno) {
        var obj = {
            pitanje: $("#pitanje").val(),
            odgovori: $("#odgovori").val(),
            send: true
        };

        $.ajax({
            url: "modules/admin/surveys/insert.php",
            method: "POST",
            data: obj,
            success: function (data) {
                $("#rizalt").html("Successfully added!");
                $("#pitanje").val("");
                $("#odgovori").val("");
                location.reload();
            },
            error: function (xhr, status, error) {
                var poruka = "Oops, an error occurred...";
                console.log(xhr)
                console.log(xhr.responseJSON)
                var i;
                switch (xhr.status) {
                    case 404: poruka = "Page not found!"; break;
                    case 422: poruka = "Values are not valid!"; break;
                    case 500: poruka = "Error"; break;
                }
                $("#rizalt").css("border","2px solid red");
                $("#rizalt").html(poruka + "<br/>");
                for( i of xhr.responseJSON){
                    $("#rizalt").append(i);
                }
            }
        })
    }
}
function imaIlnema() {
    var idV = $(this).val();
    var idP = $("#velicine").data("idproizvoda");

    $.ajax({
        url: "modules/cartproducts.php",
        method: "POST",
        success: function (data) {

            data = data.filter(p => {
                if (p.idProizvoda == idP && p.idVelicine == idV) {
                    if (p.naStanju == 1) {

                        $("#dodajUkorpu").html("Add to cart");
                        $("#dodajUkorpu").removeAttr("disabled");

                    }
                    else {
                        $("#dodajUkorpu").attr("disabled", "disabled");
                        $("#dodajUkorpu").html("Sold out");
                    }
                }
            });
        }
        ,error: function (xhr, status, error) {
            var poruka = "Oops, an error occurred...";
            console.log(xhr.responseJSON)
            switch (xhr.status) {
                case 404: poruka = "Page not found!"; break;
                case 500: poruka = "Error"; break;
            }
            $("#dodajUkorpu").html(poruka);
        }
    })
}
function trebaIlneTreba() {
    let kategorija = $("#ddlKategorije").val();

    if ((kategorija == "3") || (kategorija == "5")) {
        $("#sizes").hide();
        $("#stanje").show();
    }
    else {
        $("#sizes").show();
        $("#stanje").hide();
    }
}