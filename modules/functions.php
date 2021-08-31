<?php
    function insertKorisnika($ime, $prezime, $mejl, $username, $sifra){
        global $conn;
        $vreme=date("Y-m-d",time());
        $insertuj=$conn->prepare("insert into korisnik(ime,prezime,username,email,password,active,ulogaID,datumReg) values(:ime,:prezime,:username,:email,:password,1,2,:datumReg)");
        $insertuj->bindParam(":ime",$ime);
        $insertuj->bindParam(":prezime",$prezime);
        $insertuj->bindParam(":username",$username);
        $insertuj->bindParam(":email",$mejl);
        $insertuj->bindParam(":password",$sifra);
        $insertuj->bindParam(":datumReg",$vreme);
        return $insertuj->execute();
    }
    function unesiGlas($glas,$korisnikId){
        global $conn;
        $glasaj=$conn->prepare("insert into glasanje(odgovorID,korisnikID) values(:glas,:korisnikId) ");
        $glasaj->bindParam(":glas",$glas);
        $glasaj->bindParam(":korisnikId",$korisnikId);
        return $glasaj->execute();
    }
    function insertPorudzbine($idK,$idP,$kolicina){
        global $conn;
        $vreme=date("Y-m-d",time());
        // foreach($obj as $o){
        //     $idP[]=$o["idP"];
        //     $kolicina[]=$o["kolicina"];
        // }
        for($i=0;$i<count($idP);$i++){
            $naruci=$conn->prepare("insert into porudzbina(korisnikID,proizvodID,kolicina,datum) values(:idK,:idP,:kolicina,:datumNar)");
            $naruci->bindParam(":idK",$idK);
            $naruci->bindParam(":idP",$idP[$i]);
            $naruci->bindParam(":kolicina",$kolicina[$i]);
            $naruci->bindParam(":datumNar",$vreme);
            $naruci->execute();
        }
    }
    function updatujProizvod($idP,$name,$price,$desc,$cat){
        global $conn;
        $insertuj=$conn->prepare("update proizvod set naziv=:naziv,cena=:cena,opis=:opis,kategorijeID=:idKat where idProizvoda=:id");
        $insertuj->bindParam(":id",$idP);
        $insertuj->bindParam(":naziv",$name);
        $insertuj->bindParam(":cena",$price);
        $insertuj->bindParam(":opis",$desc);
        $insertuj->bindParam(":idKat",$cat);
        return $insertuj->execute();
    }
    function izbrisiProizvod($id){
        global $conn;
        $izbrisi=$conn->prepare("delete from proizvod where idProizvoda=:id");
        $izbrisi->bindParam(":id",$id);
        return $izbrisi->execute();
    }
    function updatujKorisnika($id,$ime,$prezime,$username,$email,$uloga,$active){
        global $conn;
        $insertuj=$conn->prepare("update korisnik set ime=:ime,prezime=:prezime,username=:username,email=:email,active=:active,ulogaId=:uloga where idKorisnik=:id");
        $insertuj->bindParam(":id",$id);
        $insertuj->bindParam(":ime",$ime);
        $insertuj->bindParam(":prezime",$prezime);
        $insertuj->bindParam(":username",$username);
        $insertuj->bindParam(":email",$email);
        $insertuj->bindParam(":active",$active);
        $insertuj->bindParam(":uloga",$uloga);
        return $insertuj->execute();
    }
    function izbrisiKorisnika($id){
        global $conn;
        $izbrisi=$conn->prepare("delete from korisnik where idKorisnik=:id");
        $izbrisi->bindParam(":id",$id);
        return $izbrisi->execute();
    }
    function izbrisiAnketu($id){
        global $conn;
        $izbrisi=$conn->prepare("delete from anketa where idAnkete=:id");
        $izbrisi->bindParam(":id",$id);
        return $izbrisi->execute();
    }
    function aktivirajAnketu($id){
        global $conn;
        $glasaj=$conn->prepare("update anketa set aktivna=1 where idAnkete=:id ");
        $glasaj->bindParam(":id",$id);
        return $glasaj->execute();
    }
    function deaktivirajAnketu($id){
        global $conn;
        $glasaj=$conn->prepare("update anketa set aktivna=0 where idAnkete=:id ");
        $glasaj->bindParam(":id",$id);
        return $glasaj->execute();
    }
    function izbrisiPorudzbinu($id){
        global $conn;
        $izbrisi=$conn->prepare("delete from porudzbina where korisnikID=:id");
        $izbrisi->bindParam(":id",$id);
        return $izbrisi->execute();
    }
    function insertPoruke($ime, $email, $poruka){
        global $conn;
        $vreme=date("Y-m-d H:i:s",time());
        $insertuj=$conn->prepare("insert into kontaktiranje(ime,email,poruka,datum) values(:ime,:mejl,:poruka,:datum)");
        $insertuj->bindParam(":ime",$ime);
        $insertuj->bindParam(":mejl",$email);
        $insertuj->bindParam(":poruka",$poruka);
        $insertuj->bindParam(":datum",$vreme);
        return $insertuj->execute();
    }
    function izbrisiPoruku($id){
        global $conn;
        $izbrisi=$conn->prepare("delete from kontaktiranje where idKontaktiranje=:id");
        $izbrisi->bindParam(":id",$id);
        return $izbrisi->execute();
    }
    function seenujPoruku($id){
        global $conn;
        $seenuj=$conn->prepare("update kontaktiranje set seen=1 where idKontaktiranje=:id");
        $seenuj->bindParam(":id",$id);
        return  $seenuj->execute();
    }
?>