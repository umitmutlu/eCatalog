<?php include_once("./config.php");
$db = new Db();
$user = $_SESSION["login_userAKKO"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


</head>

<body>
    <?php
        if ($_GET["type"] == "siralama") {
            $kesici_uclar_no = $_GET["kesici_uclar_no"];
            $altbaslik_no = $_GET["altbaslik_no"];
            $array_siralama = $_GET["value"];
            $sorgu = "UPDATE pozisyon SET siralama = '$array_siralama' WHERE pozisyon_ad = \"siralama_".$altbaslik_no."_".$kesici_uclar_no."\"";
            $db->select($sorgu);
            header('location: /eCatalog/urunler.php?sliderValue=on&kesici_uclar_no='.$kesici_uclar_no.'&altbaslik_no='.$altbaslik_no.'');
            exit;
        }

    /*$sorgu = "SELECT * FROM pozisyon WHERE pozisyon_ad='siralama_56_38'";
    $gelen = $db->select($sorgu);
    $sonuc = mysqli_fetch_assoc($gelen);
    echo $sonuc["siralama"] . '<br>';
    $dizi = explode("\"", $sonuc["siralama"]);

    echo "<pre>";
    print_r($dizi);
    echo "</pre>";
    for ($i = 0; $i < count($dizi); $i++) {
        if ($i % 2 == 1) {
            echo $dizi[$i] . '<br>';
        }
    }

    $dizi2 = explode("\"", $dizi[0]);

    echo "<pre>";
    print_r($dizi2);
    echo "</pre>";*/





    ?>



</body>

</html>