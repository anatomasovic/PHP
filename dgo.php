<?php

if(isset($_GET['a'])){ $a = $_GET['a']; } else { $a = ''; }
switch($a){
    case 'korisnik': korisnik(); break;
    case 'unos'    : unos(); break;
    default        : narudzba(); break;
}

function unos(){
    // OVDJE DOBIVAMO PODATKE O NARUDZBI + KORISNIKU
    // OBRADJUJEMO POSLANE PODATKE I UNOSIMO U NARUDZBE.TXT
    // ISPISUJEMO DETALJE NARUDZBE KORISNIKU (IZNOS, ITD)
    $narudzba = array();
    foreach($_POST as $key=>$value){
        if(strpos($key,'kol_')!==FALSE){
            $id = substr($key,4);
            $narudzba[$id]=array($value);
        }
        if(strpos($key,'special_')!==FALSE){
            $id = substr($key,8);
            $narudzba[$id][1]=$value;
        }
        if(strpos($key,'velicina_')!==FALSE){
            $id = substr($key,9);
            $narudzba[$id][2]=$value;
        }
    }
    $email = $_POST['email'];
    $adresa = $_POST['adresa'];
    var_dump($narudzba);
    echo 'EMAIL = '.$email.'<br>';
    echo 'ADRESA = '.$adresa.'<br>';
}

function narudzba(){
    // 1. GENERIRANJE POLJA ZA UNOS TEXTA
// KONTEKST: Imamo popis proizvoda u datoteci, pored svakog
//           proizvoda moramo staviti polje za unos količine
//           kako bi se izvršila narudzba
echo '<form action="?a=korisnik" method="post">';
echo '<table border="1" cellpadding="4">';
$proizvodi = file('proizvodi.txt');
foreach($proizvodi as $p){
    $polje = explode("\t",$p);
    echo '<tr>';
    echo '<td>'.$polje[1].'</td>';
    echo '<td>'.$polje[2].'</td>';
    echo '<td>'.$polje[3].'</td>';
    echo '<td> <input type="text" name="kol_'.$polje[0].'"> </td>';
    echo '<td> <input type="checkbox" name="special_'.$polje[0].'"> </td>';
    echo '<td>';
    echo '<select name="velicina_'.$polje[0].'">';
        echo '<option value="S">  Small       </option>';
        echo '<option value="M">  Medium      </option>';
        echo '<option value="L">  Large       </option>';
        echo '<option value="XL"> Extra Large </option>';
    echo '</select>';
    echo '</td>';
    echo '</tr>';
}
echo '<tr>';
echo '<td colspan="4" align="center">';
echo '<input type="submit" name="submit" value="NARUCI">';
echo '</td>';
echo '</tr>';

echo '</table>';
echo '</form>';
}



function korisnik(){
    // OVDJE DOHVACAMO PODATKE ZA NARUDZBU
    // PREGLED DOBIVENIH PODATAKA
    // PODACI O NARUCITELJU? ISPORUCI? PLACANJU?
    
    // DOHVATITI PODATKE O NARUDZBI I PRIPREMITI IH
    // ZA PRIJENOS NA SLJEDECI KORAK NARUDZBE
    // OPCIJE: 
    // 1. UPISATI NARUDZBU U PRIVREMENU DATOTEKU?
    // 2. SPREMITI SVE PODATKE IZ POST-a U SKRIVENA POLJA    
    // 3. OBLIKOVATI PODATKE U MANJI BROJ ILI JEDNO SKRIVENO POLJE
    echo '<form method="post" action="?a=unos">';
foreach($_POST as $key=>$value){
echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
}
    echo '<p>Email: <input type="text" name="email"></p>';
    echo '<p>Adresa za isporuku<br>';
    echo '<textarea name="adresa"></textarea></p>';
    echo '<p><input type="submit" name="submit" value="NARUCI"></p>';
    echo '</form>';
    
}

?>
