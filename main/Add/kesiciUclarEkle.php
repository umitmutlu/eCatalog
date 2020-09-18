<?php include_once("../config.php");
$db = new Db();
$user = $_SESSION["login_userAKKO"];
if ($user) {
    if ($_POST) {
        if ($_GET["type"] == "plus") {
            $kesici_uclar_ad = trim($_POST["kesici_uclar_ad"]);
            $kesici_uclar_ad = ucwords($kesici_uclar_ad);
            $baslik_ref = $_POST["baslik_ref"];
            $buton = $_POST["buton"];

            if (strpos($kesici_uclar_ad, '&') == true || strpos($kesici_uclar_ad, '"') == true) {
                header('location: /eCatalog?error=istenmeyenKarakter&type=kesiciUclar&altbaslik_no=' . $baslik_ref . '&ekle=yeni');
                exit;
            }
            $sorgu = 'SELECT * FROM kesici_uclar WHERE baslik_ref = ' . $baslik_ref;
            $gelen = $db->select($sorgu);
            $kontrol = 0;
            while ($sonuc = mysqli_fetch_assoc($gelen)) {
                if ($kesici_uclar_ad == $sonuc["kesici_uclar_ad"]) {
                    $kontrol = 1;
                }
            }
            if ($kontrol) {
                header('location: /eCatalog?error=veritabanındaVar&type=kesiciUclar&altbaslik_no=' . $baslik_ref . '&ekle=yeni');
                exit;
            } else if ($buton == "Kesici Uç Ekle") {
                if (isset($_FILES['dosya'])) {
                    $hata = $_FILES['dosya']['error'];
                    if ($hata != 0) {
                        header('location: /eCatalog?error=dosyaSecilmedi&type=kesiciUclar&altbaslik_no=' . $baslik_ref . '&ekle=yeni');
                        exit;
                    } else {
                        $boyut = $_FILES['dosya']['size'];
                        if ($boyut > (1024 * 1024 * 3)) {
                            header('location: /eCatalog?error=dosyaBoyutu&type=kesiciUclar&altbaslik_no=' . $baslik_ref . '&ekle=yeni');
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
                                if (!file_exists('../dosyalar/kesiciUclar/' . $baslik_ref . '')) {
                                    mkdir('../dosyalar/kesiciUclar/' . $baslik_ref . '', 0777, true);
                                }
                                $path = '../dosyalar/kesiciUclar/' . $baslik_ref . '/' . $isim;
                                copy($dosya, $path);
                                /* echo 'Dosyanız upload edildi!<br>'; */
                                $ana_ref = $_POST["ana_ref"];
                                $sorgu = 'INSERT INTO kesici_uclar(kesici_uclar_ad,kesici_uclar_imgPath,baslik_ref) VALUES ("' . $kesici_uclar_ad . '","' . str_replace('..', '.', $path) . '","' . $baslik_ref . '");';
                                $db->query($sorgu);
                                $sorgu = 'SELECT * FROM kesici_uclar WHERE kesici_uclar_ad = "' . $kesici_uclar_ad . '" AND baslik_ref = ' . $baslik_ref;
                                $gelen = $db->select($sorgu);
                                $sonuc = mysqli_fetch_assoc($gelen);
                                $hurda_kesici_uclar_no = $sonuc["kesici_uclar_no"];
                                $sorgu = 'INSERT INTO urun(urun_ad,baslik_ref,kesici_uclar_ref) VALUES ("hurda_' . $baslik_ref . '_' . $hurda_kesici_uclar_no . '","' . $baslik_ref . '","' . $sonuc["kesici_uclar_no"] . '");';
                                $db->query($sorgu);
                                $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","EKLEME","\'' . $kesici_uclar_ad . '\' isimli yeni kesici uç kategorisi oluşturuldu.");';
                                $db->query($sorgu);

                                $sorgu = 'SELECT * FROM altbaslik WHERE altBaslik_no = ' . $baslik_ref;
                                $gelen = $db->select($sorgu);
                                $sonuc = mysqli_fetch_assoc($gelen);
                                $sorgu = 'SELECT * FROM altkatalog WHERE alt_no = ' . $sonuc["alt_ref"];
                                $gelen = $db->select($sorgu);
                                $sonuc = mysqli_fetch_assoc($gelen);
                                $sorgu = 'SELECT * FROM urun_ozellikleri WHERE ana_ref = ' . $sonuc["ana_ref"];
                                $gelenAna = $db->select($sorgu);
                                $sonucAna = mysqli_fetch_assoc($gelenAna);
                                $sorgu = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="urun_ozellikleri";';
                                $gelenSutun = $db->select($sorgu);
                                $array_siralama = "{";
                                $sayac = 0;
                                while ($sutun = mysqli_fetch_assoc($gelenSutun)) {
                                    if ($sutun["COLUMN_NAME"] == "ana_ref" || $sutun["COLUMN_NAME"] == "urunOzellik_ad") {
                                        continue;
                                    }
                                    if ($sonucAna[$sutun["COLUMN_NAME"]] == "1") {
                                        $sorgu = 'UPDATE urun SET ' . $sutun["COLUMN_NAME"] . ' = "0" WHERE urun_ad = "hurda_' . $baslik_ref . '_' . $hurda_kesici_uclar_no . '"';
                                        $db->select($sorgu);
                                        $array_siralama = $array_siralama . '"' . substr($sutun["COLUMN_NAME"], 5) . '":' . $sayac . ',';
                                        $sayac++;
                                    }
                                }
                                $array_siralama = rtrim($array_siralama, ",");
                                $array_siralama = $array_siralama . "}";
                                $sorgu = "INSERT INTO pozisyon(pozisyon_ad,siralama) VALUES (\"siralama_" . $baslik_ref . "_" . $hurda_kesici_uclar_no . "\",'$array_siralama');";
                                $db->query($sorgu);
                                header('location: /eCatalog?error=eklendi&type=kesiciUclar&altbaslik_no=' . $baslik_ref . '&ekle=yeni');
                                exit;
                            } else {
                                header('location: /eCatalog?error=dosyaTipi&type=kesiciUclar&altbaslik_no=' . $baslik_ref . '&ekle=yeni');
                                exit;
                            }
                        }
                    }
                }
            }
            header('location: /eCatalog/index.php?type=kesiciUclar&altbaslik_no=' . $baslik_ref . '');
            exit;
        }
    }
}
