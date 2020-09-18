<?php

include("config.php");
$db = new Db();
$user = $_SESSION["login_userAKKO"];
if ($user) {
    $output = '';
    if (isset($_POST["excel_cikti"])) {
        $output = fopen("php://output", "w");
        $sorgu = "SELECT * from urun ORDER BY created_at DESC";
        $gelenUrun = $db->select($sorgu);
        while ($sonucUrun = mysqli_fetch_assoc($gelenUrun)) {
            if (strstr($sonucUrun["urun_ad"], "hurda_") == true) {
                continue;
            } else {
                $sorgu = 'SELECT * from pozisyon WHERE pozisyon_ad = "siralama_' . $sonucUrun["baslik_ref"] . '_' . $sonucUrun["kesici_uclar_ref"] . '"';
                $gelen = $db->select($sorgu);
                $sonuc = mysqli_fetch_assoc($gelen);
                $dizi = explode("\"", $sonuc["siralama"]);
                $row = array();
                array_push($row, "Ürün Adı");
                for ($i = 0; $i < count($dizi); $i++) {
                    if ($i % 2 == 1) {
                        array_push($row, $dizi[$i]);
                    }
                }
                array_push($row, "Oluşturulma Tarihi");
                fputcsv($output, $row);
                unset($row);
                $row = array();
                array_push($row, $sonucUrun["urun_ad"]);
                $sorgu = 'SELECT * from pozisyon WHERE pozisyon_ad = "siralama_' . $sonucUrun["baslik_ref"] . '_' . $sonucUrun["kesici_uclar_ref"] . '"';
                $gelen = $db->select($sorgu);
                $sonuc = mysqli_fetch_assoc($gelen);
                $dizi = explode("\"", $sonuc["siralama"]);
                for ($i = 0; $i < count($dizi); $i++) {
                    if ($i % 2 == 1) {
                        $temp = 'urun_' . $dizi[$i];
                        if ($sonucUrun[$temp] == NULL || $sonucUrun[$temp] == "0") {
                            array_push($row, "-");
                        } else {
                            array_push($row, $sonucUrun[$temp]);
                        }
                    }
                }
                array_push($row, $sonucUrun["created_at"]);
                fputcsv($output, $row);
            }
        }

        fclose($output);
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=download.xls");
    }
}
