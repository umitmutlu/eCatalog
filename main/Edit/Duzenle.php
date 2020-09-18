<?php
include_once("../config.php");
$user = $_SESSION["login_userAKKO"];
if (!$user) {
    header("location: /eCatalog");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Düzenle</title>
    <?php include  '../header.php'; ?>
    <style type="text/css">
        .myTable {
            background-color: #eee;
            border-collapse: collapse;
        }

        .myTable th {
            background-color: #000;
            color: white;
            width: 50%;
        }

        .myTable td,
        .myTable th {
            padding: 5px;
            border: 1px solid #000;
        }

        .myTable input {
            width: 100%;
        }
    </style>
</head>

</html>
<?php
$db = new Db();
if ($user) {
    if ($_POST || $_GET) {
        $db = new Db();
        @$buton = $_POST["buton"];
        if ($buton == "Ana Kataloğu Düzenle" || $_GET["edit"] == "anaKategori") {
            $ana_no = $_POST["ana_no"];
            $ana_ad = trim(mb_strtoupper($_POST["ana_ad"], 'UTF-8'));
            if (strpos($ana_ad, '&') == true || strpos($ana_ad, '"') == true) {
                if ($_GET["type"] == 'plus') {
                    header('location: /eCatalog?error=istenmeyenKarakter');
                } else {
                    header('location: /eCatalog?error=istenmeyenKarakter');
                }
                exit;
            }
            $sorgu = 'SELECT * FROM anakatalog WHERE ana_no != "' . $ana_no . '"';
            $gelen = $db->select($sorgu);
            $kontrol = 0;
            while ($sonuc = mysqli_fetch_assoc($gelen)) {
                if ($ana_ad == $sonuc["ana_ad"]) {
                    $kontrol = 1;
                }
            }
            if ($kontrol) {
                header('location: /eCatalog?error=veritabanındaVar');
                exit;
            } else {
                if (isset($_FILES['dosya'])) {
                    $hata = $_FILES['dosya']['error'];
                    if ($hata != 0) { // sadece isim değiştirmwek isterse buraya giriyor
                        $sorgu = 'SELECT * FROM anakatalog WHERE ana_no = ' . $ana_no;
                        $gelen = $db->select($sorgu);
                        $sonuc = mysqli_fetch_assoc($gelen);
                        $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . $sonuc["ana_ad"] . '\' isimli anakataloğun ismini \'' . $ana_ad . '\' olarak değiştirdi.");';
                        $db->query($sorgu);
                        $old_path = $sonuc["ana_imgPath"];
                        $uzanti = explode('.', $old_path);
                        $uzanti = $uzanti[count($uzanti) - 1];
                        $isim = $ana_ad . '.' . $uzanti;
                        // BEGIN - Ozel Karakterleri Düzeltme
                        $isim = str_replace("/", "-", $isim);
                        $isim = str_replace("\\", "-", $isim);
                        $isim = str_replace(":", "-", $isim);
                        $isim = str_replace("*", "-", $isim);
                        $isim = str_replace("?", "-", $isim);
                        $isim = str_replace("\"", "-", $isim);
                        $isim = str_replace("<", "-", $isim);
                        $isim = str_replace(">", "-", $isim);
                        $isim = str_replace("|", "-", $isim);
                        //END
                        $new_path = './dosyalar/' . $isim;
                        rename('.' . $old_path, '.' . $new_path);
                        $sorgu = 'UPDATE anakatalog SET ana_ad = "' . $ana_ad . '", ana_imgPath = "' . $new_path . '" WHERE ana_no = "' . $ana_no . '"';
                        $db->query($sorgu);
                        $sorgu = 'UPDATE urun_ozellikleri SET urunOzellik_ad = "' . $ana_ad . '" WHERE ana_ref = "' . $ana_no . '"';
                        $db->query($sorgu);
                        header('location: /eCatalog?error=degistirildi');
                        exit;
                    } else {
                        $boyut = $_FILES['dosya']['size'];
                        if ($boyut > (1024 * 1024 * 3)) {
                            header('location: /eCatalog?error=dosyaBoyutu');
                            exit;
                        } else {
                            $tip = $_FILES['dosya']['type'];
                            $isim = $_FILES['dosya']['name'];
                            $uzanti = explode('.', $isim);
                            $uzanti = $uzanti[count($uzanti) - 1];
                            $isim = $ana_ad . '.' . $uzanti;
                            // BEGIN - Ozel Karakterleri Düzeltme
                            $isim = str_replace("/", "-", $isim);
                            $isim = str_replace("\\", "-", $isim);
                            $isim = str_replace(":", "-", $isim);
                            $isim = str_replace("*", "-", $isim);
                            $isim = str_replace("?", "-", $isim);
                            $isim = str_replace("\"", "-", $isim);
                            $isim = str_replace("<", "-", $isim);
                            $isim = str_replace(">", "-", $isim);
                            $isim = str_replace("|", "-", $isim);
                            //END
                            if ($tip == 'image/jpeg' || $uzanti == 'jpg' || $uzanti == 'jpeg' || $uzanti == 'png' || $uzanti == 'PNG' || $uzanti == 'bmp' || $uzanti == 'gif') {
                                $dosya = $_FILES['dosya']['tmp_name'];
                                $path = '../dosyalar/' . $isim;
                                /* echo 'Dosyanız upload edildi!<br>'; */
                                $sorgu = 'SELECT * FROM anakatalog WHERE ana_no="' . $ana_no . '"';
                                $gelen = $db->select($sorgu);
                                $sonuc = mysqli_fetch_assoc($gelen);
                                if ($sonuc["ana_ad"] == $ana_ad) {
                                    $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . $sonuc["ana_ad"] . '\' isimli anakataloğun fotoğrafını değiştirdi.");';
                                    $db->query($sorgu);
                                } else {
                                    $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . $sonuc["ana_ad"] . '\' isimli anakataloğun ismini \'' . $ana_ad . '\' olarak değiştirdi.(Fotoğrafta değiştirildi)");';
                                    $db->query($sorgu);
                                }
                                $file_pointer = "." . $sonuc["ana_imgPath"];
                                if (!unlink($file_pointer)) {
                                    echo ("$file_pointer cannot be deleted due to an error");
                                } else {
                                    echo ("$file_pointer has been deleted");
                                }
                                copy($dosya, $path);
                                $sorgu = 'UPDATE anakatalog SET ana_ad = "' . $ana_ad . '", ana_imgPath = "' . str_replace('..', '.', $path) . '" WHERE ana_no = "' . $ana_no . '"';
                                $db->query($sorgu);
                                $sorgu = 'UPDATE urun_ozellikleri SET urunOzellik_ad = "' . $ana_ad . '" WHERE ana_ref = "' . $ana_no . '"';
                                $db->query($sorgu);
                                header('location: /eCatalog?error=degistirildi');
                                exit;
                            } else {
                                header('location: /eCatalog?error=dosyaTipi');
                                exit;
                            }
                        }
                    }
                }
            }
        } else if ($buton == "Alt Kataloğu Düzenle" || $_GET["edit"] == "altKategori") {
            $alt_no = $_POST["alt_no"];
            $ana_ref = $_POST["ana_ref"];
            $alt_ad = trim(mb_strtoupper($_POST["alt_ad"], 'UTF-8'));
            if (strpos($alt_ad, '&') == true || strpos($alt_ad, '"') == true) {
                if ($_GET["type"] == 'plus') {
                    header('location: /eCatalog?error=istenmeyenKarakter&type=altKategori&ana_ref=' . $ana_ref . '&edit=' . $alt_no . '');
                } else {
                    header('location: /eCatalog?error=istenmeyenKarakter&type=altKategori&ana_ref=' . $ana_ref . '&edit=' . $alt_no . '');
                }
                exit;
            }
            $sorgu = 'SELECT * FROM altkatalog WHERE ana_ref = ' . $ana_ref . ' AND alt_no != ' . $alt_no;
            $gelen = $db->select($sorgu);
            $kontrol = 0;
            while ($sonuc = mysqli_fetch_assoc($gelen)) {
                if ($alt_ad == $sonuc["alt_ad"]) {
                    $kontrol = 1;
                }
            }
            if ($kontrol) {
                header('location: /eCatalog?error=veritabanındaVar&type=altKategori&ana_ref=' . $ana_ref . '&edit=' . $alt_no);
                exit;
            } else {
                if (isset($_FILES['dosya'])) {
                    $hata = $_FILES['dosya']['error'];
                    if ($hata != 0) { // sadece isim değiştirmwek isterse buraya giriyor
                        $sorgu = 'SELECT * FROM altkatalog WHERE alt_no = ' . $alt_no;
                        $gelen = $db->select($sorgu);
                        $sonuc = mysqli_fetch_assoc($gelen);
                        $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . $sonuc["alt_ad"] . '\' isimli alt kataloğun ismini \'' . $alt_ad . '\' olarak değiştirdi.");';
                        $db->query($sorgu);
                        $old_path = $sonuc["altkatalog_img"];
                        $uzanti = explode('.', $old_path);
                        $uzanti = $uzanti[count($uzanti) - 1];
                        $isim = $alt_ad . '.' . $uzanti;
                        // BEGIN - Ozel Karakterleri Düzeltme
                        $isim = str_replace("/", "-", $isim);
                        $isim = str_replace("\\", "-", $isim);
                        $isim = str_replace(":", "-", $isim);
                        $isim = str_replace("*", "-", $isim);
                        $isim = str_replace("?", "-", $isim);
                        $isim = str_replace("\"", "-", $isim);
                        $isim = str_replace("<", "-", $isim);
                        $isim = str_replace(">", "-", $isim);
                        $isim = str_replace("|", "-", $isim);
                        //END
                        $new_path = './dosyalar/' . $isim;
                        rename('.' . $old_path, '.' . $new_path);
                        $sorgu = 'UPDATE altkatalog SET alt_ad = "' . $alt_ad . '", altkatalog_img = "' . $new_path . '" WHERE alt_no = "' . $alt_no . '"';
                        $db->query($sorgu);
                        header('location: /eCatalog?error=degistirildi&type=altKategori&ana_ref=' . $ana_ref . '&edit=' . $alt_no . '');
                        exit;
                    } else {
                        $boyut = $_FILES['dosya']['size'];
                        if ($boyut > (1024 * 1024 * 3)) {
                            header('location: /eCatalog?error=dosyaBoyutu&type=altKategori&ana_ref=' . $ana_ref . '&edit=' . $alt_no);
                            exit;
                        } else {
                            $tip = $_FILES['dosya']['type'];
                            $isim = $_FILES['dosya']['name'];
                            $uzanti = explode('.', $isim);
                            $uzanti = $uzanti[count($uzanti) - 1];
                            $isim = $alt_ad . '.' . $uzanti;
                            // BEGIN - Ozel Karakterleri Düzeltme
                            $isim = str_replace("/", "-", $isim);
                            $isim = str_replace("\\", "-", $isim);
                            $isim = str_replace(":", "-", $isim);
                            $isim = str_replace("*", "-", $isim);
                            $isim = str_replace("?", "-", $isim);
                            $isim = str_replace("\"", "-", $isim);
                            $isim = str_replace("<", "-", $isim);
                            $isim = str_replace(">", "-", $isim);
                            $isim = str_replace("|", "-", $isim);
                            //END
                            if ($tip == 'image/jpeg' || $uzanti == 'jpg' || $uzanti == 'jpeg' || $uzanti == 'png' || $uzanti == 'PNG' || $uzanti == 'bmp' || $uzanti == 'gif') {
                                $dosya = $_FILES['dosya']['tmp_name'];
                                $path = '../dosyalar/' . $isim;
                                /* echo 'Dosyanız upload edildi!<br>'; */
                                $sorgu = 'SELECT * FROM altkatalog WHERE alt_no="' . $alt_no . '"';
                                $gelen = $db->select($sorgu);
                                $sonuc = mysqli_fetch_assoc($gelen);
                                if ($sonuc["alt_ad"] == $alt_ad) {
                                    $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . $sonuc["alt_ad"] . '\' isimli alt kataloğun fotoğrafını değiştirdi.");';
                                    $db->query($sorgu);
                                } else {
                                    $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . $sonuc["alt_ad"] . '\' isimli alt kataloğun ismini \'' . $alt_ad . '\' olarak değiştirdi.(Fotoğrafta değiştirildi)");';
                                    $db->query($sorgu);
                                }
                                $file_pointer = "." . $sonuc["altkatalog_img"];
                                if (!unlink($file_pointer)) {
                                    echo ("$file_pointer cannot be deleted due to an error");
                                } else {
                                    echo ("$file_pointer has been deleted");
                                }
                                copy($dosya, $path);
                                $sorgu = 'UPDATE altkatalog SET alt_ad = "' . $alt_ad . '", altkatalog_img = "' . str_replace('..', '.', $path) . '" WHERE alt_no = "' . $alt_no . '"';
                                $db->query($sorgu);
                                header('location: /eCatalog?error=degistirildi&type=altKategori&ana_ref=' . $ana_ref . '&edit=' . $alt_no . '');
                                exit;
                            } else {
                                header('location: /eCatalog?error=dosyaTipi&type=altKategori&ana_ref=' . $ana_ref . '&edit=' . $alt_no);
                                exit;
                            }
                        }
                    }
                }
                /*$sorgu = 'UPDATE altkatalog SET alt_ad = "' . $alt_ad . '" WHERE alt_no = "' . $alt_no . '"';
                $db->query($sorgu);*/
            }
        } else if ($_GET["edit"] == "altbaslikKategori") {
            $altBaslik_no = $_POST["altBaslik_no"];
            $alt_ref = $_POST["alt_ref"];
            $altBaslik_ad = trim($_POST["altBaslik_ad"]);
            $altBaslik_ad = ucwords($altBaslik_ad);
            if (strpos($altBaslik_ad, '&') == true || strpos($altBaslik_ad, '"') == true) {
                if ($_GET["type"] == 'plus') {
                    header('location: /eCatalog?error=istenmeyenKarakter&type=altbaslikKategori&alt_ref=' . $alt_ref . '&edit=' . $altBaslik_no . '');
                } else {
                    header('location: /eCatalog?error=istenmeyenKarakter&type=altbaslikKategori&alt_ref=' . $alt_ref . '&edit=' . $altBaslik_no . '');
                }
                exit;
            }
            $sorgu = 'SELECT * FROM altbaslik WHERE alt_ref = ' . $alt_ref;
            $gelen = $db->select($sorgu);
            $kontrol = 0;
            while ($sonuc = mysqli_fetch_assoc($gelen)) {
                if ($altBaslik_ad == $sonuc["altBaslik_ad"]) {
                    $kontrol = 1;
                }
            }
            if ($kontrol) {
                header('location: /eCatalog?error=veritabanındaVar&type=altbaslikKategori&alt_ref=' . $alt_ref . '&edit=' . $altBaslik_no);
                exit;
            } else {
                $sorgu = 'SELECT * FROM altbaslik WHERE altBaslik_no = ' . $altBaslik_no;
                $gelen = $db->select($sorgu);
                $sonuc = mysqli_fetch_assoc($gelen);
                $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . $sonuc["altBaslik_ad"] . '\' isimli açılar kataloğunun  ismini \'' . $altBaslik_ad . '\' olarak değiştirdi.");';
                $db->query($sorgu);
                $old_path = $sonuc["altBaslik_imgPath"];
                $old_path_Teknik = $sonuc["altBaslik_imgTeknik"];
                /* 1. resim için */
                $uzanti = explode('.', $old_path);
                $uzanti = $uzanti[count($uzanti) - 1];
                $isim = $altBaslik_ad . '1.' . $uzanti;
                // BEGIN - Ozel Karakterleri Düzeltme
                $isim = str_replace("/", "-", $isim);
                $isim = str_replace("\\", "-", $isim);
                $isim = str_replace(":", "-", $isim);
                $isim = str_replace("*", "-", $isim);
                $isim = str_replace("?", "-", $isim);
                $isim = str_replace("\"", "-", $isim);
                $isim = str_replace("<", "-", $isim);
                $isim = str_replace(">", "-", $isim);
                $isim = str_replace("|", "-", $isim);
                $isim = str_replace("°", "-", $isim);
                //END
                $new_path = './dosyalar/acilar/' . $alt_ref . '/' . $isim;
                rename('.' . $old_path, '.' . $new_path);
                /* 2. Resim için (Teknik Resim) */
                $uzanti_Teknik = explode('.', $old_path_Teknik);
                $uzanti_Teknik = $uzanti_Teknik[count($uzanti_Teknik) - 1];
                $isim = $altBaslik_ad . '2.' . $uzanti;
                // BEGIN - Ozel Karakterleri Düzeltme
                $isim = str_replace("/", "-", $isim);
                $isim = str_replace("\\", "-", $isim);
                $isim = str_replace(":", "-", $isim);
                $isim = str_replace("*", "-", $isim);
                $isim = str_replace("?", "-", $isim);
                $isim = str_replace("\"", "-", $isim);
                $isim = str_replace("<", "-", $isim);
                $isim = str_replace(">", "-", $isim);
                $isim = str_replace("|", "-", $isim);
                $isim = str_replace("°", "-", $isim);
                //END
                $new_path_Teknik = './dosyalar/acilar/' . $alt_ref . '/' . $isim;
                rename('.' . $old_path_Teknik, '.' . $new_path_Teknik);
                /* Sorgular */
                $sorgu = 'UPDATE altbaslik SET altBaslik_ad = "' . $altBaslik_ad . '", altBaslik_imgPath = "' . $new_path . '", altBaslik_imgTeknik = "' . $new_path_Teknik . '"  WHERE altBaslik_no = "' . $altBaslik_no . '"';
                $db->query($sorgu);
                header('location: /eCatalog?type=altbaslikKategori&alt_ref=' . $alt_ref . '&edit=' . $altBaslik_no . '');
                exit;
            }
            // Resim değiştirmeler eklenecek daha 
        } else if ($_POST["edit"] == "aciImg") {
            $altbaslik_no = $_POST["altbaslik_no"];
            $kesici_uclar_no = $_POST["kesici_uclar_no"];
            $img_no = $_POST["img_no"];
            if (isset($_FILES['dosya'])) {
                $hata = $_FILES['dosya']['error'];
                if ($hata != 0) { // dosya seçmediyse

                    header('location: /eCatalog/urunler.php?error=dosyaSecilmedi&editImg=' . $img_no . '&altbaslik_no=' . $altbaslik_no . '&kesici_uclar_no=' . $kesici_uclar_no . '');
                    exit;
                } else {
                    $boyut = $_FILES['dosya']['size'];
                    if ($boyut > (1024 * 1024 * 3)) {
                        header('location: /eCatalog/urunler.php?error=dosyaBoyutu&editImg=' . $img_no . '&altbaslik_no=' . $altbaslik_no . '&kesici_uclar_no=' . $kesici_uclar_no . '');
                        exit;
                    } else {
                        $sorgu = 'SELECT * FROM altbaslik WHERE altBaslik_no="' . $altbaslik_no . '"';
                        $gelen = $db->select($sorgu);
                        $sonuc = mysqli_fetch_assoc($gelen);
                        $tip = $_FILES['dosya']['type'];
                        $isim = $_FILES['dosya']['name'];
                        $uzanti = explode('.', $isim);
                        $uzanti = $uzanti[count($uzanti) - 1];
                        $isim = $sonuc["altBaslik_ad"] . $img_no . '.' . $uzanti;
                        // BEGIN - Ozel Karakterleri Düzeltme
                        $isim = str_replace("/", "-", $isim);
                        $isim = str_replace("\\", "-", $isim);
                        $isim = str_replace(":", "-", $isim);
                        $isim = str_replace("*", "-", $isim);
                        $isim = str_replace("?", "-", $isim);
                        $isim = str_replace("\"", "-", $isim);
                        $isim = str_replace("<", "-", $isim);
                        $isim = str_replace(">", "-", $isim);
                        $isim = str_replace("|", "-", $isim);
                        $isim = str_replace("°", "-", $isim);
                        //END
                        if ($tip == 'image/jpeg' || $uzanti == 'jpg' || $uzanti == 'jpeg' || $uzanti == 'png' || $uzanti == 'PNG' || $uzanti == 'bmp' || $uzanti == 'gif') {
                            $dosya = $_FILES['dosya']['tmp_name'];
                            $path = '../dosyalar/acilar/' . $sonuc["alt_ref"] . '/' . $isim;
                            $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . $sonuc["altBaslik_ad"] . '\' isimli açılar kataloğunun fotoğrafını değiştirdi.");';
                            $db->query($sorgu);
                            if ($img_no == "1") {
                                $file_pointer = "." . $sonuc["altBaslik_imgPath"];
                            } else {
                                $file_pointer = "." . $sonuc["altBaslik_imgTeknik"];
                            }
                            if (!unlink($file_pointer)) {
                                echo ("$file_pointer cannot be deleted due to an error");
                            } else {
                                echo ("$file_pointer has been deleted");
                            }
                            copy($dosya, $path);
                            if ($img_no == "1") {
                                $sorgu = 'UPDATE altbaslik SET altBaslik_imgPath = "' . str_replace('..', '.', $path) . '" WHERE altBaslik_no = "' . $altbaslik_no . '"';
                            } else {
                                $sorgu = 'UPDATE altbaslik SET altBaslik_imgTeknik = "' . str_replace('..', '.', $path) . '" WHERE altBaslik_no = "' . $altbaslik_no . '"';
                            }
                            $db->query($sorgu);
                            header('location: /eCatalog/urunler.php?error=degistirildi&altbaslik_no=' . $altbaslik_no . '&kesici_uclar_no=' . $kesici_uclar_no . '');
                            exit;
                        } else {
                            header('location: /eCatalog/urunler.php?error=dosyaTipi&editImg=' . $img_no . '&altbaslik_no=' . $altbaslik_no . '&kesici_uclar_no=' . $kesici_uclar_no . '');
                            exit;
                        }
                    }
                }
            }
        } else if ($_GET["edit"] == "kesiciUclar") {
            $kesici_uclar_no = $_POST["kesici_uclar_no"];
            $baslik_ref = $_POST["baslik_ref"];
            $kesici_uclar_ad = trim($_POST["kesici_uclar_ad"]);
            $kesici_uclar_ad = ucwords($kesici_uclar_ad);
            if (strpos($kesici_uclar_ad, '&') == true || strpos($kesici_uclar_ad, '"') == true) {
                if ($_GET["type"] == 'plus') {
                    header('location: /eCatalog?error=istenmeyenKarakter&type=kesiciUclar&altbaslik_no=' . $baslik_ref . '&edit=' . $kesici_uclar_no . '');
                } else {
                    header('location: /eCatalog?error=istenmeyenKarakter&type=kesiciUclar&altbaslik_no=' . $baslik_ref . '&edit=' . $kesici_uclar_no . '');
                }
                exit;
            }
            $sorgu = 'SELECT * FROM kesici_uclar WHERE baslik_ref = ' . $baslik_ref . ' AND kesici_uclar_no != ' . $kesici_uclar_no . '';
            $gelen = $db->select($sorgu);
            $kontrol = 0;
            while ($sonuc = mysqli_fetch_assoc($gelen)) {
                if ($kesici_uclar_ad == $sonuc["kesici_uclar_ad"]) {
                    $kontrol = 1;
                }
            }
            if ($kontrol) {
                header('location: /eCatalog?error=veritabanındaVar&type=kesiciUclar&altbaslik_no=' . $baslik_ref . '&edit=' . $kesici_uclar_no);
                exit;
            } else {
                if (isset($_FILES['dosya'])) {
                    $hata = $_FILES['dosya']['error'];
                    if ($hata != 0) { // sadece isim değiştirmek isterse buraya giriyor
                        $sorgu = 'SELECT * FROM kesici_uclar WHERE kesici_uclar_no = ' . $kesici_uclar_no;
                        $gelen = $db->select($sorgu);
                        $sonuc = mysqli_fetch_assoc($gelen);
                        $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . $sonuc["kesici_uclar_ad"] . '\' isimli kesici uçlar kataloğun ismini \'' . $kesici_uclar_ad . '\' olarak değiştirdi.");';
                        $db->query($sorgu);
                        $old_path = $sonuc["kesici_uclar_imgPath"];
                        $uzanti = explode('.', $old_path);
                        $uzanti = $uzanti[count($uzanti) - 1];
                        $isim = $kesici_uclar_ad . '.' . $uzanti;
                        // BEGIN - Ozel Karakterleri Düzeltme
                        $isim = str_replace("/", "-", $isim);
                        $isim = str_replace("\\", "-", $isim);
                        $isim = str_replace(":", "-", $isim);
                        $isim = str_replace("*", "-", $isim);
                        $isim = str_replace("?", "-", $isim);
                        $isim = str_replace("\"", "-", $isim);
                        $isim = str_replace("<", "-", $isim);
                        $isim = str_replace(">", "-", $isim);
                        $isim = str_replace("|", "-", $isim);
                        //END
                        $new_path = './dosyalar/kesiciUclar/' . $baslik_ref . '/' . $isim;
                        rename('.' . $old_path, '.' . $new_path);
                        $sorgu = 'UPDATE kesici_uclar SET kesici_uclar_ad = "' . $kesici_uclar_ad . '", kesici_uclar_imgPath = "' . $new_path . '" WHERE kesici_uclar_no = "' . $kesici_uclar_no . '"';
                        $db->query($sorgu);
                        header('location: /eCatalog?error=degistirildi&type=kesiciUclar&altbaslik_no=' . $baslik_ref . '&edit=' . $kesici_uclar_no . '');
                        exit;
                    } else {
                        $boyut = $_FILES['dosya']['size'];
                        if ($boyut > (1024 * 1024 * 3)) {
                            header('location: /eCatalog?error=dosyaBoyutu&type=kesiciUclar&altbaslik_no=' . $baslik_ref . '&edit=' . $kesici_uclar_no);
                            exit;
                        } else {
                            $tip = $_FILES['dosya']['type'];
                            $isim = $_FILES['dosya']['name'];
                            $uzanti = explode('.', $isim);
                            $uzanti = $uzanti[count($uzanti) - 1];
                            $isim = $kesici_uclar_ad . '.' . $uzanti;
                            // BEGIN - Ozel Karakterleri Düzeltme
                            $isim = str_replace("/", "-", $isim);
                            $isim = str_replace("\\", "-", $isim);
                            $isim = str_replace(":", "-", $isim);
                            $isim = str_replace("*", "-", $isim);
                            $isim = str_replace("?", "-", $isim);
                            $isim = str_replace("\"", "-", $isim);
                            $isim = str_replace("<", "-", $isim);
                            $isim = str_replace(">", "-", $isim);
                            $isim = str_replace("|", "-", $isim);
                            //END
                            if ($tip == 'image/jpeg' || $uzanti == 'jpg' || $uzanti == 'jpeg' || $uzanti == 'png' || $uzanti == 'PNG' || $uzanti == 'bmp' || $uzanti == 'gif') {
                                $dosya = $_FILES['dosya']['tmp_name'];
                                $path = '../dosyalar/kesiciUclar/' . $baslik_ref . '/' . $isim;
                                /* echo 'Dosyanız upload edildi!<br>'; */
                                $sorgu = 'SELECT * FROM kesici_uclar WHERE kesici_uclar_no="' . $kesici_uclar_no . '"';
                                $gelen = $db->select($sorgu);
                                $sonuc = mysqli_fetch_assoc($gelen);
                                if ($sonuc["kesici_uclar_ad"] == $kesici_uclar_ad) {
                                    $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . $sonuc["kesici_uclar_ad"] . '\' isimli kesici uçlar kataloğunun fotoğrafını değiştirdi.");';
                                    $db->query($sorgu);
                                } else {
                                    $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . $sonuc["kesici_uclar_ad"] . '\' isimli kesici uçlar kataloğunun ismini \'' . $kesici_uclar_ad . '\' olarak değiştirdi.(Fotoğrafta değiştirildi)");';
                                    $db->query($sorgu);
                                }
                                $file_pointer = "." . $sonuc["kesici_uclar_imgPath"];
                                if (!unlink($file_pointer)) {
                                    echo ("$file_pointer cannot be deleted due to an error");
                                } else {
                                    echo ("$file_pointer has been deleted");
                                }
                                copy($dosya, $path);
                                $sorgu = 'UPDATE kesici_uclar SET kesici_uclar_ad = "' . $kesici_uclar_ad . '", kesici_uclar_imgPath = "' . str_replace('..', '.', $path) . '" WHERE kesici_uclar_no = "' . $kesici_uclar_no . '"';
                                $db->query($sorgu);
                                header('location: /eCatalog?error=degistirildi&type=kesiciUclar&altbaslik_no=' . $baslik_ref . '&edit=' . $kesici_uclar_no . '');
                                exit;
                            } else {
                                header('location: /eCatalog?error=dosyaTipi&type=kesiciUclar&altbaslik_no=' . $baslik_ref . '&edit=' . $kesici_uclar_no);
                                exit;
                            }
                        }
                    }
                }
                /*$sorgu = 'UPDATE altkatalog SET alt_ad = "' . $alt_ad . '" WHERE alt_no = "' . $alt_no . '"';
                $db->query($sorgu);*/
            }
        } else if ($_POST["edit"] == "urun") {
            $urun_no = $_POST["urun_no"];
            $urun_ad = trim($_POST["urun_ad"]);
            $urun_ad = ucwords($urun_ad);
            $altbaslik_no = $_POST["altbaslik_no"];
            $kesici_uclar_no = $_POST["kesici_uclar_no"];
            if (strpos($urun_ad, '&') == true || strpos($urun_ad, '"') == true) {
                header('location: /eCatalog/urunler.php?error=istenmeyenKarakter&altbaslik_no=' . $altbaslik_no . '&kesici_uclar_no=' . $kesici_uclar_no . '&edit=' . $urun_no . '');
                exit;
            }
            $sorgu = 'SELECT * FROM urun WHERE urun_no != ' . $urun_no;
            $gelen = $db->select($sorgu);
            $kontrol = 0;
            while ($sonuc = mysqli_fetch_assoc($gelen)) {
                if ($urun_ad == $sonuc["urun_ad"]) {
                    $kontrol = 1;
                }
            }
            if ($kontrol) {
                header('location: /eCatalog/urunler.php?error=veritabanındaVar&altbaslik_no=' . $altbaslik_no . '&kesici_uclar_no=' . $kesici_uclar_no . '&edit=' . $urun_no . '');
                exit;
            } else {
                if (isset($_FILES['dosya'])) {
                    $hata = $_FILES['dosya']['error'];
                    if ($hata != 0) { // sadece isim değiştirmek isterse buraya giriyor
                        $sorgu = 'SELECT * FROM urun WHERE urun_no = ' . $urun_no;
                        $gelen = $db->select($sorgu);
                        $sonuc = mysqli_fetch_assoc($gelen);
                        $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . $sonuc["urun_ad"] . '\' isimli ürünün ismini \'' . $urun_ad . '\' olarak değiştirdi.");';
                        $db->query($sorgu);
                        $old_path = $sonuc["urun_imgPath"];
                        $uzanti = explode('.', $old_path);
                        $uzanti = $uzanti[count($uzanti) - 1];
                        $isim = $urun_ad . '.' . $uzanti;
                        // BEGIN - Ozel Karakterleri Düzeltme
                        $isim = str_replace("/", "-", $isim);
                        $isim = str_replace("\\", "-", $isim);
                        $isim = str_replace(":", "-", $isim);
                        $isim = str_replace("*", "-", $isim);
                        $isim = str_replace("?", "-", $isim);
                        $isim = str_replace("\"", "-", $isim);
                        $isim = str_replace("<", "-", $isim);
                        $isim = str_replace(">", "-", $isim);
                        $isim = str_replace("|", "-", $isim);
                        //END
                        $new_path = './dosyalar/' . $isim;
                        rename('.' . $old_path, '.' . $new_path);
                        $sorgu = 'UPDATE urun SET urun_ad = "' . $urun_ad . '", urun_imgPath = "' . $new_path . '" WHERE urun_no = "' . $urun_no . '"';
                        $db->query($sorgu);

                        $sorgu = 'SELECT siralama FROM pozisyon WHERE pozisyon_ad="siralama_' . $altbaslik_no . '_' . $kesici_uclar_no . '"';
                        $gelen = $db->select($sorgu);
                        $sonuc = mysqli_fetch_assoc($gelen);
                        $dizi = explode("\"", $sonuc["siralama"]);
                        for ($i = 0; $i < count($dizi); $i++) {
                            if ($i % 2 == 1) {
                                $temp = 'urun_' . $dizi[$i];
                                $value = $_POST[$temp];
                                if ($value) {
                                    $sorgu = 'UPDATE urun SET ' . $temp . ' = "' . $value . '" WHERE urun_no = "' . $urun_no . '"';
                                } else {
                                    $sorgu = 'UPDATE urun SET ' . $temp . ' = "0" WHERE urun_no = "' . $urun_no . '"';
                                }
                                $db->query($sorgu);
                            }
                        }
                        header('location: /eCatalog/urunler.php?error=degistirildi&altbaslik_no=' . $altbaslik_no . '&kesici_uclar_no=' . $kesici_uclar_no . '&edit=' . $urun_no . '');
                        exit;
                    } else {
                        $boyut = $_FILES['dosya']['size'];
                        if ($boyut > (1024 * 1024 * 3)) {
                            header('location: /eCatalog/urunler.php?error=dosyaBoyutu&altbaslik_no=' . $altbaslik_no . '&kesici_uclar_no=' . $kesici_uclar_no . '&edit=' . $urun_no . '');
                            exit;
                        } else {
                            $tip = $_FILES['dosya']['type'];
                            $isim = $_FILES['dosya']['name'];
                            $uzanti = explode('.', $isim);
                            $uzanti = $uzanti[count($uzanti) - 1];
                            $isim = $urun_ad . '.' . $uzanti;
                            // BEGIN - Ozel Karakterleri Düzeltme
                            $isim = str_replace("/", "-", $isim);
                            $isim = str_replace("\\", "-", $isim);
                            $isim = str_replace(":", "-", $isim);
                            $isim = str_replace("*", "-", $isim);
                            $isim = str_replace("?", "-", $isim);
                            $isim = str_replace("\"", "-", $isim);
                            $isim = str_replace("<", "-", $isim);
                            $isim = str_replace(">", "-", $isim);
                            $isim = str_replace("|", "-", $isim);
                            //END
                            if ($tip == 'image/jpeg' || $uzanti == 'jpg' || $uzanti == 'jpeg' || $uzanti == 'png' || $uzanti == 'PNG' || $uzanti == 'bmp' || $uzanti == 'gif') {
                                $dosya = $_FILES['dosya']['tmp_name'];
                                $path = '../dosyalar/' . $isim;
                                /* echo 'Dosyanız upload edildi!<br>'; */
                                $sorgu = 'SELECT * FROM urun WHERE urun_no="' . $urun_no . '"';
                                $gelen = $db->select($sorgu);
                                $sonuc = mysqli_fetch_assoc($gelen);
                                if ($sonuc["urun_ad"] == $urun_ad) {
                                    $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . $sonuc["urun_ad"] . '\' isimli ürünün fotoğrafını değiştirdi.");';
                                    $db->query($sorgu);
                                } else {
                                    $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . $sonuc["urun_ad"] . '\' isimli ürünün ismini \'' . $kesici_uclar_ad . '\' olarak değiştirdi.(Fotoğrafta değiştirildi)");';
                                    $db->query($sorgu);
                                }
                                $file_pointer = "." . $sonuc["urun_imgPath"];
                                if (!unlink($file_pointer)) {
                                    echo ("$file_pointer cannot be deleted due to an error");
                                } else {
                                    echo ("$file_pointer has been deleted");
                                }
                                copy($dosya, $path);
                                $sorgu = 'UPDATE urun SET urun_ad = "' . $urun_ad . '", urun_imgPath = "' . str_replace('..', '.', $path) . '" WHERE urun_no = "' . $urun_no . '"';
                                $db->query($sorgu);

                                $sorgu = 'SELECT siralama FROM pozisyon WHERE pozisyon_ad="siralama_' . $altbaslik_no . '_' . $kesici_uclar_no . '"';
                                $gelen = $db->select($sorgu);
                                $sonuc = mysqli_fetch_assoc($gelen);
                                $dizi = explode("\"", $sonuc["siralama"]);
                                for ($i = 0; $i < count($dizi); $i++) {
                                    if ($i % 2 == 1) {
                                        $temp = 'urun_' . $dizi[$i];
                                        $value = $_POST[$temp];
                                        if ($value) {
                                            $sorgu = 'UPDATE urun SET ' . $temp . ' = "' . $value . '" WHERE urun_no = "' . $urun_no . '"';
                                        } else {
                                            $sorgu = 'UPDATE urun SET ' . $temp . ' = "0" WHERE urun_no = "' . $urun_no . '"';
                                        }
                                        $db->query($sorgu);
                                    }
                                }
                                header('location: /eCatalog/urunler.php?error=degistirildi&altbaslik_no=' . $altbaslik_no . '&kesici_uclar_no=' . $kesici_uclar_no . '&edit=' . $urun_no . '');
                                exit;
                            } else {
                                header('location: /eCatalog/urunler.php?error=dosyaTipi&altbaslik_no=' . $altbaslik_no . '&kesici_uclar_no=' . $kesici_uclar_no . '&edit=' . $urun_no . '');
                                exit;
                            }
                        }
                    }
                }
            }
        } else if ($buton == "Ana Kataloğu Sil" || $_GET["delete"] == "anaKategori" && $user["permission_delete"] == "1") {
            $ana_no = $_GET["value"];
            $sorgu = 'SELECT alt_no FROM altkatalog WHERE ana_ref=' . $ana_no;
            $gelenAltKatalog = $db->select($sorgu);
            while ($sonucAltKatalog = mysqli_fetch_assoc($gelenAltKatalog)) {
                $sorgu = 'SELECT altBaslik_no FROM altbaslik WHERE alt_ref=' . $sonucAltKatalog["alt_no"];
                $gelenAltBaslik = $db->select($sorgu);
                while ($sonucAltBaslik = mysqli_fetch_assoc($gelenAltBaslik)) {
                    $sorgu = 'SELECT kesici_uclar_no FROM kesici_uclar WHERE baslik_ref=' . $sonucAltBaslik["altBaslik_no"];
                    $gelenKesiciUc = $db->select($sorgu);
                    while ($sonucKesiciUc = mysqli_fetch_assoc($gelenKesiciUc)) {
                        $sorgu = 'SELECT urun_no FROM urun WHERE alt_ref=' . $sonucAltKatalog["alt_no"] . ' AND baslik_ref =' . $sonucAltBaslik["altBaslik_no"] . ' AND kesici_uclar_ref = ' . $sonucKesiciUc["kesici_uclar_no"];
                        $gelenUrun = $db->select($sorgu);
                        while ($sonucUrun = mysqli_fetch_assoc($gelenUrun)) {
                            $sorgu = 'DELETE FROM urun_yedek WHERE urun_ref =' . $sonucUrun["urun_no"];
                            $db->select($sorgu);
                        }
                        $sorgu = 'DELETE FROM urun WHERE alt_ref=' . $sonucAltKatalog["alt_no"] . ' AND baslik_ref =' . $sonucAltBaslik["altBaslik_no"] . ' AND kesici_uclar_ref = ' . $sonucKesiciUc["kesici_uclar_no"];
                        $db->select($sorgu);
                        $sorgu = 'DELETE FROM urun WHERE urun_ad = "hurda_' . $sonucAltBaslik["altBaslik_no"] . '_' . $sonucKesiciUc["kesici_uclar_no"] . '"';
                        $db->select($sorgu);
                        $sorgu = 'DELETE FROM pozisyon WHERE pozisyon_ad = "siralama_' . $sonucAltBaslik["altBaslik_no"] . '_' . $sonucKesiciUc["kesici_uclar_no"] . '"';
                        $db->select($sorgu);
                    }
                    $sorgu = 'DELETE FROM kesici_uclar WHERE baslik_ref =' . $sonucAltBaslik["altBaslik_no"];
                    $db->select($sorgu);
                }
                $sorgu = 'DELETE FROM altbaslik WHERE alt_ref=' . $sonucAltKatalog["alt_no"];
                $db->select($sorgu);
            }
            $sorgu = 'DELETE FROM altkatalog WHERE ana_ref=' . $ana_no;
            $db->select($sorgu);
            $sorgu = 'DELETE FROM urun_ozellikleri WHERE ana_ref=' . $ana_no;
            $db->select($sorgu);
            // BEGIN - Log islemleri
            $sorgu = 'SELECT ana_ad FROM anakatalog WHERE ana_no=' . $ana_no;
            $gelen = $db->select($sorgu);
            $sonuc = mysqli_fetch_assoc($gelen);
            $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","SILME","\'' . $sonuc["ana_ad"] . '\' isimli ana katalog silindi.");';
            $db->query($sorgu);
            // END
            $sorgu = 'DELETE FROM anakatalog WHERE ana_no=' . $ana_no;
            $db->select($sorgu);
            header('location: /eCatalog');
            exit;
        } else if ($buton == "Alt Kataloğu Sil" || $_GET["delete"] == "altKategori" && $user["permission_delete"] == "1") {
            $alt_no = $_GET["value"];

            $sorgu = 'SELECT altBaslik_no FROM altbaslik WHERE alt_ref=' . $alt_no;
            $gelenAltBaslik = $db->select($sorgu);
            while ($sonucAltBaslik = mysqli_fetch_assoc($gelenAltBaslik)) {
                $sorgu = 'SELECT kesici_uclar_no FROM kesici_uclar WHERE baslik_ref=' . $sonucAltBaslik["altBaslik_no"];
                $gelenKesiciUc = $db->select($sorgu);
                while ($sonucKesiciUc = mysqli_fetch_assoc($gelenKesiciUc)) {
                    $sorgu = 'SELECT urun_no FROM urun WHERE alt_ref=' . $alt_no . ' AND baslik_ref =' . $sonucAltBaslik["altBaslik_no"] . ' AND kesici_uclar_ref = ' . $sonucKesiciUc["kesici_uclar_no"];
                    $gelenUrun = $db->select($sorgu);
                    while ($sonucUrun = mysqli_fetch_assoc($gelenUrun)) {
                        $sorgu = 'DELETE FROM urun_yedek WHERE urun_ref =' . $sonucUrun["urun_no"];
                        $db->select($sorgu);
                    }
                    $sorgu = 'DELETE FROM urun WHERE alt_ref=' . $alt_no . ' AND baslik_ref =' . $sonucAltBaslik["altBaslik_no"] . ' AND kesici_uclar_ref = ' . $sonucKesiciUc["kesici_uclar_no"];
                    $db->select($sorgu);
                    $sorgu = 'DELETE FROM urun WHERE urun_ad = "hurda_' . $sonucAltBaslik["altBaslik_no"] . '_' . $sonucKesiciUc["kesici_uclar_no"] . '"';
                    $db->select($sorgu);
                    $sorgu = 'DELETE FROM pozisyon WHERE pozisyon_ad = "siralama_' . $sonucAltBaslik["altBaslik_no"] . '_' . $sonucKesiciUc["kesici_uclar_no"] . '"';
                    $db->select($sorgu);
                }
                $sorgu = 'DELETE FROM kesici_uclar WHERE baslik_ref =' . $sonucAltBaslik["altBaslik_no"];
                $db->select($sorgu);
            }
            $sorgu = 'DELETE FROM altbaslik WHERE alt_ref=' . $alt_no;
            $db->select($sorgu);
            // BEGIN - Log islemleri
            $sorgu = 'SELECT alt_ad FROM altkatalog WHERE alt_no=' . $alt_no;
            $gelen = $db->select($sorgu);
            $sonuc = mysqli_fetch_assoc($gelen);
            $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","SILME","\'' . $sonuc["alt_ad"] . '\' isimli alt katalog silindi.");';
            $db->query($sorgu);
            // END
            $sorgu = 'DELETE FROM altkatalog WHERE alt_no=' . $alt_no;
            $db->select($sorgu);
            $ana_ref = $_GET["ana_ref"];
            header('location: /eCatalog/index.php?type=altKategori&ana_ref=' . $ana_ref);
            exit;
        } else if ($buton == "Alt Baslığı Sil" || $_GET["delete"] == "altBaslik" && $user["permission_delete"] == "1") {
            $altBaslik_no = $_GET["value"];
            $alt_no = $_GET["alt_ref"];

            $sorgu = 'SELECT kesici_uclar_no FROM kesici_uclar WHERE baslik_ref=' . $altBaslik_no;
            $gelenKesiciUc = $db->select($sorgu);
            while ($sonucKesiciUc = mysqli_fetch_assoc($gelenKesiciUc)) {
                $sorgu = 'SELECT urun_no FROM urun WHERE alt_ref=' . $alt_no . ' AND baslik_ref =' . $altBaslik_no . ' AND kesici_uclar_ref = ' . $sonucKesiciUc["kesici_uclar_no"];
                $gelenUrun = $db->select($sorgu);
                while ($sonucUrun = mysqli_fetch_assoc($gelenUrun)) {
                    $sorgu = 'DELETE FROM urun_yedek WHERE urun_ref =' . $sonucUrun["urun_no"];
                    $db->select($sorgu);
                }
                $sorgu = 'DELETE FROM urun WHERE alt_ref=' . $alt_no . ' AND baslik_ref =' . $altBaslik_no . ' AND kesici_uclar_ref = ' . $sonucKesiciUc["kesici_uclar_no"];
                $db->select($sorgu);
                $sorgu = 'DELETE FROM urun WHERE urun_ad = "hurda_' . $altBaslik_no . '_' . $sonucKesiciUc["kesici_uclar_no"] . '"';
                $db->select($sorgu);
                $sorgu = 'DELETE FROM pozisyon WHERE pozisyon_ad = "siralama_' . $altBaslik_no . '_' . $sonucKesiciUc["kesici_uclar_no"] . '"';
                $db->select($sorgu);
            }
            $sorgu = 'DELETE FROM kesici_uclar WHERE baslik_ref =' . $altBaslik_no;
            $db->select($sorgu);
            // BEGIN - Log islemleri
            $sorgu = 'SELECT altBaslik_ad FROM altbaslik WHERE altBaslik_no=' . $altBaslik_no;
            $gelen = $db->select($sorgu);
            $sonuc = mysqli_fetch_assoc($gelen);
            $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","SILME","\'' . $sonuc["altBaslik_ad"] . '\' isimli açılar kataloğu silindi.");';
            $db->query($sorgu);
            // END
            $sorgu = 'DELETE FROM altbaslik WHERE altBaslik_no=' . $altBaslik_no;
            $db->select($sorgu);
            header('location: /eCatalog/index.php?type=altbaslikKategori&alt_ref=' . $alt_no);
            exit;
        } else if ($_GET["delete"] == "kesiciUclar" && $user["permission_delete"] == "1") {
            $kesici_uclar_no = $_GET["value"];
            $altBaslik_no = $_GET["baslik_ref"];

            $sorgu = 'SELECT alt_ref FROM altbaslik WHERE altBaslik_no = ' . $altBaslik_no;
            $gelen = $db->select($sorgu);
            $sonuc = mysqli_fetch_assoc($gelen);
            $alt_no = $sonuc["alt_ref"];

            $sorgu = 'SELECT urun_no FROM urun WHERE alt_ref=' . $alt_no . ' AND baslik_ref =' . $altBaslik_no . ' AND kesici_uclar_ref = ' . $kesici_uclar_no;
            $gelenUrun = $db->select($sorgu);
            while ($sonucUrun = mysqli_fetch_assoc($gelenUrun)) {
                $sorgu = 'DELETE FROM urun_yedek WHERE urun_ref =' . $sonucUrun["urun_no"];
                $db->select($sorgu);
            }
            $sorgu = 'DELETE FROM urun WHERE alt_ref=' . $alt_no . ' AND baslik_ref =' . $altBaslik_no . ' AND kesici_uclar_ref = ' . $kesici_uclar_no;
            $db->select($sorgu);
            $sorgu = 'DELETE FROM urun WHERE urun_ad = "hurda_' . $altBaslik_no . '_' . $kesici_uclar_no . '"';
            $db->select($sorgu);
            $sorgu = 'DELETE FROM pozisyon WHERE pozisyon_ad = "siralama_' . $altBaslik_no . '_' . $kesici_uclar_no . '"';
            $db->select($sorgu);
            // BEGIN - Log islemleri
            $sorgu = 'SELECT kesici_uclar_ad FROM kesici_uclar WHERE kesici_uclar_no=' . $kesici_uclar_no;
            $gelen = $db->select($sorgu);
            $sonuc = mysqli_fetch_assoc($gelen);
            $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","SILME","\'' . $sonuc["kesici_uclar_ad"] . '\' isimli kesici uçlar kataloğu silindi.");';
            $db->query($sorgu);
            // END
            $sorgu = 'DELETE FROM kesici_uclar WHERE kesici_uclar_no =' . $kesici_uclar_no;
            $db->select($sorgu);
            header('location: /eCatalog/index.php?type=kesiciUclar&altbaslik_no=' . $altBaslik_no);
            exit;
        } else if ($buton == "Ürünü Sil" || $_GET["delete"] == "urun" && $user["permission_delete"] == "1") {
            $urun_no = $_GET["urun_no"];
            $kesici_uclar_no = $_GET["kesici_uclar_no"];
            $altbaslik_no = $_GET["altbaslik_no"];
            // BEGIN - Log islemleri
            $sorgu = 'SELECT urun_ad FROM urun WHERE urun_no=' . $urun_no;
            $gelen = $db->select($sorgu);
            $sonuc = mysqli_fetch_assoc($gelen);
            $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","SILME","\'' . $sonuc["urun_ad"] . '\' isimli ürün silindi.");';
            $db->query($sorgu);
            // END
            $sorgu = 'DELETE FROM urun WHERE urun_no=' . $urun_no;
            $db->select($sorgu);
            $sorgu = 'DELETE FROM urun_yedek WHERE urun_ref=' . $urun_no;
            $db->select($sorgu);
            header('location: /eCatalog/urunler.php?altbaslik_no=' . $altbaslik_no . '&kesici_uclar_no=' . $kesici_uclar_no);
            exit;
        }
    }
}
?>