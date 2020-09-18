<?php include_once("../config.php");

$db = new Db();

$user = $_SESSION["login_userAKKO"];
if ($user) {
    if ($_POST) {
        $error = 0;
        $urun_no = $_POST["urun_no"];
        $parca_ref = $_POST["parca_ref"];
        if ($_GET["type"] == "plus") {
            $parca_Tisim = $_POST["parca_Tisim"];
            $buton = $_POST["buton"];
            if ($buton == "Parça Ekle") {
                $sorgu = 'SELECT * FROM urun_yedek WHERE parca_ref = ' . $parca_ref . ' AND urun_ref = ' . $urun_no . '';
                $gelen = $db->select($sorgu);
                if ($parca_ref == '' || $parca_Tisim == '') {
                    $error = "bos";
                } else if ($sonuc = mysqli_fetch_assoc($gelen)) {
                    $error = "ayni";
                } else {
                    $sorgu = 'SELECT * FROM yedekparca_images WHERE parca_ref = ' . $parca_ref . '';
                    $gelen = $db->select($sorgu);
                    $sonuc = mysqli_fetch_assoc($gelen);
                    $sorgu = 'INSERT INTO urun_yedek(parca_ref,parca_Tisim,urun_ref,image_ref) VALUES ("' . $parca_ref . '","' . $parca_Tisim . '","' . $urun_no . '","' . $sonuc["image_no"] . '");';
                    $db->query($sorgu);
                }
            }
            if ($buton == "Hepsine Uygula") {
                $sorgu = 'SELECT * FROM urun WHERE urun_no = ' . $urun_no . '';
                $gelen = $db->select($sorgu);
                $sonuc = mysqli_fetch_assoc($gelen);
                $kesici_uclar_ref = $sonuc["kesici_uclar_ref"];
                $alt_ref = $sonuc["alt_ref"];

                $sorgu = 'SELECT * FROM urun WHERE kesici_uclar_ref = ' . $kesici_uclar_ref . ' AND alt_ref = ' . $alt_ref . '';
                $gelen = $db->select($sorgu);
                while ($sonuc = mysqli_fetch_assoc($gelen)) {
                    if ($sonuc["urun_no"] != $urun_no) {
                        $sorgu = 'SELECT * FROM urun_yedek WHERE urun_ref = ' . $urun_no . '';
                        $gelen_yedek = $db->select($sorgu);
                        while ($sonuc_yedek = mysqli_fetch_assoc($gelen_yedek)) {
                            $sorgu = 'SELECT * FROM urun_yedek WHERE parca_ref = ' . $sonuc_yedek["parca_ref"] . ' AND urun_ref = ' . $sonuc["urun_no"] . '';
                            $gelen_kontrol = $db->select($sorgu);
                            $sorgu = 'SELECT * FROM yedekparca_images WHERE image_no = ' . $sonuc_yedek["image_ref"] . '';
                            $gelenImage = $db->select($sorgu);
                            $sonucImage = mysqli_fetch_assoc($gelenImage);
                            if (!($sonuc_kontrol = mysqli_fetch_assoc($gelen_kontrol))) {
                                $sorgu = 'INSERT INTO urun_yedek(parca_ref,parca_Tisim,urun_ref,image_ref) VALUES ("' . $sonuc_yedek["parca_ref"] . '","' . $sonuc_yedek["parca_Tisim"] . '","' . $sonuc["urun_no"] . '","' . $sonucImage["image_no"] . '");';
                                $db->query($sorgu);
                            }
                        }
                    }
                }
                $error = "hepsine";
            }
            header('location: /eCatalog/urun.php?type=yeni&urun_no=' . $urun_no . '&error=' . $error . '');
            exit;
        } else if ($_GET["type"] == "buton") {
            $parca_adi = $_POST["parca_adi"];
            $parca_adi = ucwords($parca_adi);
            $buton = $_POST["buton"];
            if ($buton == "Kategori Ekle") {
                $sorgu = 'SELECT * FROM yedek_parca WHERE parca_adi = "' . $parca_adi . '"';
                $gelen = $db->select($sorgu);
                if ($parca_adi == '') {
                    $error = "bos";
                } else if ($sonuc = mysqli_fetch_assoc($gelen)) {
                    $error = "ayni";
                } else {
                    $sorgu = 'INSERT INTO yedek_parca(parca_adi) VALUES ("' . $parca_adi . '");';
                    $db->query($sorgu);
                    $error = "eklendi";
                }
            }
            if ($buton == "Fotoğraf Ekle") {
                $sorgu = 'SELECT * FROM yedek_parca WHERE parca_no = "' . $parca_ref . '"';
                $gelen = $db->select($sorgu);
                $sonuc = mysqli_fetch_assoc($gelen);
                $parca_adi = $sonuc["parca_adi"];
                if (isset($_FILES['dosya'])) {
                    $hata = $_FILES['dosya']['error'];
                    if ($hata != 0) {
                        $error = "dosyaHata";
                    } else {
                        $tip = $_FILES['dosya']['type'];
                        $isim = $_FILES['dosya']['name'];
                        $uzanti = explode('.', $isim);
                        $uzanti = $uzanti[count($uzanti) - 1];
                        $sorgu = 'SELECT * FROM yedekparca_images WHERE parca_ref = "' . $parca_ref . '"';
                        $gelen = $db->select($sorgu);
                        $sayac = 0;
                        while ($sonuc = mysqli_fetch_assoc($gelen)) {
                            $sayac++;
                        }
                        $sayac++;
                        $isim = $parca_adi . $sayac . '.' . $uzanti;
                        if ($tip == 'image/jpeg' || $uzanti == 'jpg' || $uzanti == 'jpeg' || $uzanti == 'png' || $uzanti == 'PNG' || $uzanti == 'bmp' || $uzanti == 'gif') {
                            $dosya = $_FILES['dosya']['tmp_name'];
                            if (!file_exists('../dosyalar/yedekParca/' . $parca_adi . '')) {
                                mkdir('../dosyalar/yedekParca/' . $parca_adi . '', 0777, true);
                            }
                            $path = '../dosyalar/yedekParca/' . $parca_adi . '/' . $isim;
                            copy($dosya, $path);
                            /* echo 'Dosyanız upload edildi!<br>'; */
                            $sorgu = 'INSERT INTO yedekparca_images(parca_ref,image_path) VALUES ("' . $parca_ref . '","' . str_replace('..', '.', $path) . '");';
                            $db->query($sorgu);
                        } else {
                            echo 'Yanlızca JPG/JPEG/PNG/BMP/GIF dosyaları gönderebilirsiniz.';
                        }
                    }
                }
            }

            header('location: /eCatalog/urun.php?urun_no=' . $urun_no . '&error=' . $error . '');
            exit;
        }
    }
}
