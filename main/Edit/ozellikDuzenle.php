<?php
include_once("../config.php");
$db = new Db();
$user = $_SESSION["login_userAKKO"];

if ($user) {
    if ($_POST["type"] == "ekleOzellik") {
        $kesici_uclar_no = $_POST["kesici_uclar_no"];
        $altbaslik_no = $_POST["altbaslik_no"];
        $ozellik_ad = $_POST["ozellik_ad"];
        if ($ozellik_ad != "") {
            $sorgu = 'SELECT * FROM urun WHERE urun_ad = "hurda_' . $altbaslik_no . '_' . $kesici_uclar_no . '"';
            $gelen = $db->select($sorgu);
            $sonuc = mysqli_fetch_assoc($gelen);
            $sorgu = 'UPDATE urun SET ' . $ozellik_ad . ' = "0" WHERE urun_ad = "hurda_' . $altbaslik_no . '_' . $kesici_uclar_no . '"';
            $db->select($sorgu);
            $sorgu = "SELECT * FROM pozisyon WHERE pozisyon_ad=\"siralama_" . $altbaslik_no . "_" . $kesici_uclar_no . "\"";
            $gelen = $db->select($sorgu);
            $sonuc = mysqli_fetch_assoc($gelen);
            $array_siralama = $sonuc["siralama"];
            $array_siralama = rtrim($array_siralama, "}");
            $array_siralama = $array_siralama . ',"' . substr($ozellik_ad, 5) . '":99}';
            $sorgu = "UPDATE pozisyon SET siralama = '$array_siralama' WHERE pozisyon_ad = \"siralama_" . $altbaslik_no . "_" . $kesici_uclar_no . "\"";
            $db->select($sorgu);
        }
        header("location: /eCatalog/urunler.php?altbaslik_no=$altbaslik_no&kesici_uclar_no=$kesici_uclar_no");
        exit;
    } else if ($_GET["delete"] == "ozellik") {
        $kesici_uclar_no = $_GET["kesici_uclar_no"];
        $altbaslik_no = $_GET["altbaslik_no"];
        $ozellik_ad = $_GET["ozellik_ad"];
        $sorgu = 'UPDATE urun SET ' . $ozellik_ad . ' = NULL WHERE urun_ad = "hurda_' . $altbaslik_no . '_' . $kesici_uclar_no . '"';
        $db->select($sorgu);
        $sorgu = "SELECT * FROM pozisyon WHERE pozisyon_ad=\"siralama_" . $altbaslik_no . "_" . $kesici_uclar_no . "\"";
        $gelen = $db->select($sorgu);
        $sonuc = mysqli_fetch_assoc($gelen);
        $array_siralama = explode("\"", $sonuc["siralama"]);
        for ($i = 0; $i < count($array_siralama); $i++) {
            if ($i % 2 == 1) {
                if ($array_siralama[$i] == substr($ozellik_ad, 5)) {
                    unset($array_siralama[$i]);
                    unset($array_siralama[$i + 1]);
                    break;
                }
            }
        }
        $array_siralama = array_values($array_siralama);
        $length = count($array_siralama);
        $array_siralama[$length - 1] = ":99}";
        $new_array = "";
        foreach ($array_siralama as $key => $value) {
            if ($key % 2 == 1) {
                $new_array = $new_array . '"' . $value . '"';
            } else {
                $new_array = $new_array . $value;
            }
        }
        $sorgu = "UPDATE pozisyon SET siralama = '$new_array' WHERE pozisyon_ad = \"siralama_" . $altbaslik_no . "_" . $kesici_uclar_no . "\"";
        $db->select($sorgu);
        header("location: /eCatalog/urunler.php?altbaslik_no=$altbaslik_no&kesici_uclar_no=$kesici_uclar_no");
        exit;
    } else if ($_POST["type"] == "changeSutun") {
        $kesici_uclar_no = $_POST["kesici_uclar_no"];
        $altbaslik_no = $_POST["altbaslik_no"];
        $ozellik_ad_eski = $_POST["ozellik_ad_eski"];
        $ozellik_ad_yeni = $_POST["ozellik_ad_yeni"];
        if ($ozellik_ad_yeni != "") {
            $sorgu = 'SELECT ' . $ozellik_ad_eski . ',urun_ad FROM urun WHERE baslik_ref =' . $altbaslik_no . ' AND kesici_uclar_ref = ' . $kesici_uclar_no . '';
            $gelen = $db->select($sorgu);
            while ($sonuc = mysqli_fetch_assoc($gelen)) {
                $sorgu = 'UPDATE urun SET ' . $ozellik_ad_yeni . ' = "' . $sonuc[$ozellik_ad_eski] . '" WHERE urun_ad = "' . $sonuc["urun_ad"] . '"';
                $db->select($sorgu);
                $sorgu = 'UPDATE urun SET ' . $ozellik_ad_eski . ' = NULL WHERE urun_ad = "' . $sonuc["urun_ad"] . '"';
                $db->select($sorgu);
            }
            $sorgu = 'UPDATE urun SET ' . $ozellik_ad_yeni . ' = "0" WHERE urun_ad = "hurda_' . $altbaslik_no . '_' . $kesici_uclar_no . '"';
            $db->select($sorgu);
            $sorgu = 'UPDATE urun SET ' . $ozellik_ad_eski . ' = NULL WHERE urun_ad = "hurda_' . $altbaslik_no . '_' . $kesici_uclar_no . '"';
            $db->select($sorgu);
            $sorgu = "SELECT * FROM pozisyon WHERE pozisyon_ad=\"siralama_" . $altbaslik_no . "_" . $kesici_uclar_no . "\"";
            $gelen = $db->select($sorgu);
            $sonuc = mysqli_fetch_assoc($gelen);
            $array_siralama = explode("\"", $sonuc["siralama"]);
            for ($i = 0; $i < count($array_siralama); $i++) {
                if ($i % 2 == 1) {
                    if ($array_siralama[$i] == substr($ozellik_ad_eski, 5)) {
                        $array_siralama[$i] = substr($ozellik_ad_yeni, 5);
                        break;
                    }
                }
            }
            $array_siralama = array_values($array_siralama);
            $length = count($array_siralama);
            $array_siralama[$length - 1] = ":99}";
            $new_array = "";
            foreach ($array_siralama as $key => $value) {
                if ($key % 2 == 1) {
                    $new_array = $new_array . '"' . $value . '"';
                } else {
                    $new_array = $new_array . $value;
                }
            }
            $sorgu = "UPDATE pozisyon SET siralama = '$new_array' WHERE pozisyon_ad = \"siralama_" . $altbaslik_no . "_" . $kesici_uclar_no . "\"";
            $db->select($sorgu);
        }
        header("location: /eCatalog/urunler.php?altbaslik_no=$altbaslik_no&kesici_uclar_no=$kesici_uclar_no");
        exit;
    }
}
