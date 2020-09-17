<?php
ob_start();
include_once("../config.php");
include_once("urunOzellikleri.php");
include  '../errors.php';

if ($_SERVER['SCRIPT_NAME'] == "/eCatalog/Add/urunEkle.php" && !$_POST["takimKodu"]) {
    include_once("../header.php");
}

$urunO = new cls_urunOzellikleri();
$db = new Db();
$user = $_SESSION["login_userAKKO"];
if (!$user) {
    header("location: /eCatalog/login/login.php");
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Ürün Ekle</title>
    <script>
        function isDelete(deleteVariable) {
            var splitVariable = deleteVariable.split("_url=");
            /*console.log(splitVariable[0]);
            console.log(splitVariable[1]);
            console.log(splitVariable[2]);
            console.log("anaref = " + splitVariable[3]);*/
            if (splitVariable[0] == "urunOzellikleri") {
                    window.location = "/eCatalog/Add/urunOzellikleri.php?delete=urunOzellikleri&value=" + splitVariable[1];  
            }
        }
    </script>
    <?php
    // BEGIN - Silme işlemlerinde, şifre kontrolü
    if (@$_POST["password_check"] == "delete") {
        $username = $user["username"];
        $password = $_POST["password"];
        $deleteVariable = $_POST["deleteVariable"];
        $ozellik_ad = $_POST["ozellik_ad"];
        $password = trim($password);
        $password = md5($password);
        $sorgu = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
        $gelen = $db->select($sorgu);
        if ($sonuc = mysqli_fetch_assoc($gelen)) { ?>
            <script>
                console.log('<?php echo $deleteVariable; ?>');
                isDelete('<?php echo $deleteVariable; ?>');
            </script>
    <?php } else {
            header('location: /eCatalog/Add/urunEkle.php?error=sifreYanlis&ozellik_ad=' . $ozellik_ad);
            exit;
        }
    }
    // END - Silme işlemlerinde, şifre kontrolü 
    ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
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

        input[type=button],
        input[type=submit],
        input[type=reset] {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 16px 32px;
            text-decoration: none;
            margin: 4px 2px;
            cursor: pointer;
        }
    </style>
</head>

<?php if ($user) {
    if ($_POST) {
        @$hangisi = $_POST["hangisi"];
        @$buton = $_POST["buton"];
        if ($hangisi == '1') {
            $ana_ad = trim(mb_strtoupper($_POST["ana_ad"], 'UTF-8'));
            if (strpos($ana_ad, '&') == true || strpos($ana_ad, '"') == true) {
                if ($_GET["type"] == 'plus') {
                    header('location: /eCatalog?error=istenmeyenKarakter');
                } else {
                    header('location: /eCatalog/Add/urunEkle?error=istenmeyenKarakter');
                }
                exit;
            }
            $sorgu = 'SELECT * FROM anakatalog';
            $gelen = $db->select($sorgu);
            $kontrol = 0;
            while ($sonuc = mysqli_fetch_assoc($gelen)) {
                if ($ana_ad == $sonuc["ana_ad"]) {
                    $kontrol = 1;
                }
            }
            if ($kontrol) {
                header('location: /eCatalog?error=veritabanındaVar&type=anaYeni');
                exit;
            } else {
                if (isset($_FILES['dosya'])) {
                    $hata = $_FILES['dosya']['error'];
                    if ($hata != 0) {
                        header('location: /eCatalog?error=dosyaSecilmedi&type=anaYeni');
                        exit;
                    } else {
                        $boyut = $_FILES['dosya']['size'];
                        if ($boyut > (1024 * 1024 * 3)) {
                            header('location: /eCatalog?error=dosyaBoyutu&type=anaYeni');
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
                                copy($dosya, $path);
                                /* echo 'Dosyanız upload edildi!<br>'; */
                                $sorgu = 'INSERT INTO anakatalog(ana_ad,ana_imgPath) VALUES ("' . $ana_ad . '","' . str_replace('..', '.', $path) . '");';
                                $db->query($sorgu);
                                $sorgu = 'SELECT * FROM anakatalog WHERE ana_ad ="' . $ana_ad . '"';
                                $gelen = $db->select($sorgu);
                                $sonuc = mysqli_fetch_assoc($gelen);
                                $sorgu = 'INSERT INTO urun_ozellikleri(ana_ref,urunOzellik_ad) VALUES (' . $sonuc['ana_no'] . ',"' . $ana_ad . '");';
                                $db->query($sorgu);
                                $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","EKLEME","\'' . $ana_ad . '\' isimli yeni ana kategori oluşturuldu.");';
                                $db->query($sorgu);
                                header('location: /eCatalog?error=eklendi&type=anaYeni');
                                exit;
                            } else {
                                header('location: /eCatalog?error=dosyaTipi&type=anaYeni');
                                exit;
                            }
                        }
                    }
                }
            }
            if ($_GET["type"] == 'plus') {
                header('location: /eCatalog');
                exit;
            }
        } else if ($hangisi == '2') {
            $alt_ad = trim(mb_strtoupper($_POST["alt_ad"], 'UTF-8'));
            $ana_ref = $_POST["ana_ref"];
            if (strpos($alt_ad, '&') == true || strpos($alt_ad, '"') == true) {
                header('location: /eCatalog?error=istenmeyenKarakter&type=altKategori&ekle=yeni&ana_ref=' . $ana_ref);
                exit;
            }
            $sorgu = 'SELECT * FROM altkatalog';
            $gelen = $db->select($sorgu);
            $kontrol = 0;
            while ($sonuc = mysqli_fetch_assoc($gelen)) {
                if ($alt_ad == $sonuc["alt_ad"]) {
                    $kontrol = 1;
                }
            }
            if ($kontrol) {
                header('location: /eCatalog?error=veritabanındaVar&type=altKategori&ekle=yeni&ana_ref=' . $ana_ref);
                exit;
            } else {
                if (isset($_FILES['dosya'])) {
                    $hata = $_FILES['dosya']['error'];
                    if ($hata != 0) {
                        header('location: /eCatalog?error=dosyaSecilmedi&type=altKategori&ekle=yeni&ana_ref=' . $ana_ref);
                        exit;
                    } else {
                        $boyut = $_FILES['dosya']['size'];
                        if ($boyut > (1024 * 1024 * 3)) {
                            header('location: /eCatalog?error=dosyaBoyutu&type=altKategori&ekle=yeni&ana_ref=' . $ana_ref);
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
                                copy($dosya, $path);
                                /* echo 'Dosyanız upload edildi!<br>'; */
                                $sorgu = 'INSERT INTO altkatalog(alt_ad,ana_ref,altkatalog_img) VALUES ("' . $alt_ad . '","' . $ana_ref . '","' . str_replace('..', '.', $path) . '");';
                                $db->query($sorgu);
                                $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","EKLEME","\'' . $alt_ad . '\' isimli yeni alt kategori oluşturuldu.");';
                                $db->query($sorgu);
                                header('location: /eCatalog?error=eklendi&type=altKategori&ana_ref=' . $ana_ref);
                                exit;
                            } else {
                                header('location: /eCatalog?error=dosyaTipi&type=altKategori&ekle=yeni&ana_ref=' . $ana_ref);
                                exit;
                            }
                        }
                    }
                }
            }
            if ($_GET["type"] == 'plus') {
                header('location: /eCatalog/index.php?type=altKategori&ana_ref=' . $ana_ref . '');
                exit;
            }
        }
        if ($buton == 'Açı Ekle') {
            $altbaslik_ad = trim($_POST["altbaslik_ad"]);
            $altbaslik_ad = ucwords($altbaslik_ad);
            $alt_ref = $_POST["alt_ref"];
            if (strpos($altbaslik_ad, '&') == true || strpos($altbaslik_ad, '"') == true) {
                header('location: /eCatalog?error=istenmeyenKarakter&type=altbaslikKategori&alt_ref=' . $alt_ref . '&ekle=yeni');
                exit;
            }
            $sorgu = 'SELECT * FROM altbaslik WHERE alt_ref = ' . $alt_ref;
            $gelen = $db->select($sorgu);
            $kontrol = 0;
            while ($sonuc = mysqli_fetch_assoc($gelen)) {
                if ($altbaslik_ad == $sonuc["altBaslik_ad"]) {
                    $kontrol = 1;
                }
            }
            if ($kontrol) {
                header('location: /eCatalog?error=veritabanındaVar&type=altbaslikKategori&alt_ref=' . $alt_ref . '&ekle=yeni');
                exit;
            } else {
                $path1;
                $path2;
                if (isset($_FILES['dosya1'])) {
                    $hata = $_FILES['dosya1']['error'];
                    if ($hata != 0) {
                        header('location: /eCatalog?error=dosyaSecilmedi&type=altbaslikKategori&alt_ref=' . $alt_ref . '&ekle=yeni');
                        exit;
                    } else {
                        $boyut = $_FILES['dosya1']['size'];
                        if ($boyut > (1024 * 1024 * 3)) {
                            header('location: /eCatalog?error=dosyaBoyutu&type=altbaslikKategori&alt_ref=' . $alt_ref . '&ekle=yeni');
                            exit;
                        } else {
                            $tip = $_FILES['dosya1']['type'];
                            $isim = $_FILES['dosya1']['name'];
                            $uzanti = explode('.', $isim);
                            $uzanti = $uzanti[count($uzanti) - 1];
                            $isim = $altbaslik_ad . '1.' . $uzanti;
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
                            if ($tip == 'image/jpeg' || $uzanti == 'jpg' || $uzanti == 'jpeg' || $uzanti == 'svg' || $uzanti == 'png' || $uzanti == 'PNG' || $uzanti == 'bmp' || $uzanti == 'gif') {
                                $dosya = $_FILES['dosya1']['tmp_name'];
                                if (!file_exists('../dosyalar/acilar/' . $alt_ref . '')) {
                                    mkdir('../dosyalar/acilar/' . $alt_ref . '', 0777, true);
                                }
                                $path1 = '../dosyalar/acilar/' . $alt_ref . '/' . $isim;
                                copy($dosya, $path1);
                            } else {
                                header('location: /eCatalog?error=dosyaTipi&type=altbaslikKategori&alt_ref=' . $alt_ref . '&ekle=yeni');
                                exit;
                            }
                        }
                    }
                }
                if (isset($_FILES['dosya2'])) {
                    $hata = $_FILES['dosya2']['error'];
                    if ($hata != 0) {
                        header('location: /eCatalog?error=dosyaSecilmedi&type=altbaslikKategori&alt_ref=' . $alt_ref . '&ekle=yeni');
                        exit;
                    } else {
                        $boyut = $_FILES['dosya2']['size'];
                        if ($boyut > (1024 * 1024 * 3)) {
                            header('location: /eCatalog?error=dosyaBoyutu&type=altbaslikKategori&alt_ref=' . $alt_ref . '&ekle=yeni');
                            exit;
                        } else {
                            $tip = $_FILES['dosya2']['type'];
                            $isim = $_FILES['dosya2']['name'];
                            $uzanti = explode('.', $isim);
                            $uzanti = $uzanti[count($uzanti) - 1];
                            $isim = $altbaslik_ad . '2.' . $uzanti;
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
                            if ($tip == 'image/jpeg' || $uzanti == 'jpg' || $uzanti == 'jpeg' || $uzanti == 'svg' || $uzanti == 'png' || $uzanti == 'PNG' || $uzanti == 'bmp' || $uzanti == 'gif') {
                                $dosya = $_FILES['dosya2']['tmp_name'];
                                if (!file_exists('../dosyalar/acilar/' . $alt_ref . '')) {
                                    mkdir('../dosyalar/acilar/' . $alt_ref . '', 0777, true);
                                }
                                $path2 = '../dosyalar/acilar/' . $alt_ref . '/' . $isim;
                                copy($dosya, $path2);
                                $sorgu = 'INSERT INTO altbaslik(altbaslik_ad,altbaslik_imgPath,altbaslik_imgTeknik,alt_ref) VALUES ("' . $altbaslik_ad . '","' . str_replace('..', '.', $path1) . '","' . str_replace('..', '.', $path2) . '","' . $alt_ref . '");';
                                $db->query($sorgu);
                                /* Dosyanız upload edildi! */
                                $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","EKLEME","\'' . $altbaslik_ad . '\' isimli yeni açı kategorisi oluşturuldu.");';
                                $db->query($sorgu);
                                header('location: /eCatalog?error=eklendi&type=altbaslikKategori&alt_ref=' . $alt_ref);
                                exit;
                            } else {
                                header('location: /eCatalog?error=dosyaTipi&type=altbaslikKategori&alt_ref=' . $alt_ref . '&ekle=yeni');
                                exit;
                            }
                        }
                    }
                }
            }
            // URUN EKLE KALDIRILACAKSA BU IF ISLEVSIZ KALIYOR.
            if ($_GET["type"] == 'plus') {
                header('location: /eCatalog/index.php?type=altbaslikKategori&alt_ref=' . $alt_ref . '');
                exit;
            }
        }
        if ($buton == 'Özellikleri Değiştir') {
            $sorgu = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="urun_ozellikleri";';
            $gelenSutun = $db->select($sorgu);
            while ($sutun = mysqli_fetch_assoc($gelenSutun)) {
                if ($sutun["COLUMN_NAME"] == "ana_ref" || $sutun["COLUMN_NAME"] == "urunOzellik_ad") {
                    continue;
                }
                if ($_POST[$sutun["COLUMN_NAME"]] == '1') {
                    $sorgu = 'UPDATE urun_ozellikleri SET ' . $sutun["COLUMN_NAME"] . ' = "1" WHERE ana_ref = "' . $_POST['ana_ref'] . '"';
                    $db->select($sorgu);
                } else {
                    $sorgu = 'UPDATE urun_ozellikleri SET ' . $sutun["COLUMN_NAME"] . ' = "0" WHERE ana_ref = "' . $_POST['ana_ref'] . '"';
                    $db->select($sorgu);
                }
            }
            $sorgu = 'SELECT ana_ad FROM anakatalog WHERE ana_no=' . $_POST["ana_ref"] . ';';
            $gelen = $db->select($sorgu);
            $sonuc = mysqli_fetch_assoc($gelen);
            $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . $sonuc["ana_ad"] . '\' isimli ana kataloğa yeni sütunlar ekledi veya çıkardı.");';
            $db->query($sorgu);
        } else if ($buton == 'Ürün Ekle') {
            $urun_ad = trim($_POST["takimKodu"]);
            $urun_ad = ucwords($urun_ad);
            $alt_ref = $_POST["alt_ref"];
            $baslik_ref = $_POST["baslik_ref"];
            $kesici_uclar_ref = $_POST["kesici_uclar_ref"];
            if (strpos($urun_ad, '&') == true || strpos($urun_ad, '"') == true) {
                header('location: /eCatalog/urunler.php?error=istenmeyenKarakter&type=yeni&kesici_uclar_no=' . $kesici_uclar_ref . '&altbaslik_no=' . $baslik_ref);
                exit;
            }
            $sorgu = 'SELECT * FROM urun';
            $gelen = $db->select($sorgu);
            $kontrol = 0;
            while ($sonuc = mysqli_fetch_assoc($gelen)) {
                if ($urun_ad == $sonuc["urun_ad"]) {
                    $kontrol = 1;
                }
            }
            if ($kontrol) {
                header('location: /eCatalog/urunler.php?error=veritabanındaVar&type=yeni&kesici_uclar_no=' . $kesici_uclar_ref . '&altbaslik_no=' . $baslik_ref);
                exit;
            } else if (isset($_FILES['dosya'])) {
                $hata = $_FILES['dosya']['error'];
                if ($hata != 0) {
                    header('location: /eCatalog/urunler.php?error=dosyaSecilmedi&type=yeni&kesici_uclar_no=' . $kesici_uclar_ref . '&altbaslik_no=' . $baslik_ref);
                    exit;
                } else {
                    $boyut = $_FILES['dosya']['size'];
                    if ($boyut > (1024 * 1024 * 3)) {
                        header('location: /eCatalog/urunler.php?error=dosyaBoyutu&type=yeni&kesici_uclar_no=' . $kesici_uclar_ref . '&altbaslik_no=' . $baslik_ref);
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
                            copy($dosya, $path);
                            /* echo 'Dosyanız upload edildi!<br>'; */
                            $sorgu = 'INSERT INTO urun(urun_ad, alt_ref, baslik_ref, kesici_uclar_ref, created_at) VALUES ("' . $urun_ad . '","' . $alt_ref . '","' . $baslik_ref . '","' . $kesici_uclar_ref . '",NOW());';
                            $db->query($sorgu);
                            $sorgu = 'UPDATE urun SET urun_imgPath="' . str_replace('..', '.', $path) . '"WHERE urun_ad = "' . $urun_ad . '" AND alt_ref="' . $alt_ref . '" AND baslik_ref="' . $baslik_ref . '"';
                            $db->select($sorgu);
                            $sorgu = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="urun_ozellikleri";';
                            $gelenSutun = $db->select($sorgu);
                            while ($sutun = mysqli_fetch_assoc($gelenSutun)) {
                                if ($sutun["COLUMN_NAME"] == "ana_ref" || $sutun["COLUMN_NAME"] == "urunOzellik_ad") {
                                    continue;
                                }
                                if ($_POST[$sutun["COLUMN_NAME"]] != "") {
                                    $sorgu = 'UPDATE urun SET ' . $sutun["COLUMN_NAME"] . ' = "' . $_POST[$sutun["COLUMN_NAME"]] . '" WHERE urun_ad = "' . $urun_ad . '"';
                                    $db->select($sorgu);
                                }
                            }
                            $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","EKLEME","\'' . $urun_ad . '\' isimli yeni ürün oluşturuldu.");';
                            $db->query($sorgu);
                            header('location: /eCatalog/urunler.php?error=eklendi&type=yeni&kesici_uclar_no=' . $kesici_uclar_ref . '&altbaslik_no=' . $baslik_ref);
                            exit;
                        } else {
                            header('location: /eCatalog/urunler.php?error=dosyaTipi&type=yeni&kesici_uclar_no=' . $kesici_uclar_ref . '&altbaslik_no=' . $baslik_ref);
                            exit;
                        }
                    }
                }
            }
            if ($_GET["type"] == 'plus') {
                header('location: /eCatalog/urunler.php?kesici_uclar_no=' . $kesici_uclar_ref . '&altbaslik_no=' . $baslik_ref . '');
                exit;
            }
        }
?>


<?php
        /***************************************** Özellik Düzenleme *****************************************/
        echo '<br><br>';
        @$urunO->tablo($_POST['ana_ref'] ?: $sonuc['ana_no'] ?: '1');
        echo '<br>';
        echo '<hr/>';
    } else {
        echo '<head><style type="text/css">
        .myTable { background-color:#eee;border-collapse:collapse; }
        .myTable th { background-color:#000;color:white;width:50%; }
        .myTable td, .myTable th { padding:5px;border:1px solid #000;}
        .myTable input{width:100%;}
        input[type=button], input[type=submit], input[type=reset] {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 16px 32px;
            text-decoration: none;
            margin: 4px 2px;
            cursor: pointer;
          }
        </style></head>';

        /***************************************** Özellik Düzenleme *****************************************/
        echo '<br><br>';
        $urunO->tablo('1');
        echo '<br>';
        echo '<hr/>';
    }
}
?>

<body>
    <?php if ($user["permission_delete"] == "1") { ?>
        <!-- Begin / BootStrap Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Sil</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" action="">
                        Silmek istediğinize emin misinizzzzzzzzzzzzz ?
                        <form class="form-inline" action="urunEkle.php" method="POST">
                            <input type="hidden" name="password_check" value="delete">
                            <input type="hidden" name="deleteVariable" id="deleteVariable" value="default">
                            <input type="hidden" name="ozellik_ad" value="<?php echo $_GET["ozellik_ad"] ?>">
                            <div class="form-group">
                                <label for="inputPassword6">Password</label>
                                <input type="password" id="inputPassword6" name="password" class="form-control mx-sm-3" aria-describedby="passwordHelpInline">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <script>
                            var deleteVariable;
                            $('#exampleModal').on('show.bs.modal', function(e) {
                                deleteVariable = $(e.relatedTarget).data('delete-data');
                                document.getElementById("deleteVariable").value = deleteVariable;
                                console.log("alttayızz");
                            });
                        </script>
                        <button type="submit" class="btn btn-primary">Evet</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hayır</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End / BootStrap Modal -->
    <?php } ?>
</body>


</html>