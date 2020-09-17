<?php
include_once("../config.php");
$db = new Db();
$user = $_SESSION["login_userAKKO"];
if ($user) {
    if ($_GET["delete"] == "urunOzellikleri") {
        $sorgu = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="urun_ozellikleri";';
        $gelenSutun = $db->select($sorgu);
        $control = 0;
        echo $_GET["value"];
        while ($sutun = mysqli_fetch_assoc($gelenSutun)) {
            if ($sutun["COLUMN_NAME"] == $_GET["value"]) {
                $control = 1;
                break;
            }
        }
        if ($control) { // silinmesi istenen sutun varsa
            $urunOzellik_ad = $_GET["value"];
            $sorgu = "ALTER TABLE urun_ozellikleri DROP COLUMN `$urunOzellik_ad`";
            $db->query($sorgu);
            $sorgu = "ALTER TABLE urun DROP COLUMN `$urunOzellik_ad`";
            $db->query($sorgu);
            // BEGIN - pozisyon tablosunda varsa öyle bir sutün kaldırcak 
            $sorgu = "SELECT * FROM pozisyon ";
            $gelen = $db->select($sorgu);
            while ($sonuc = mysqli_fetch_assoc($gelen)) {
                $pozisyon_ad = $sonuc["pozisyon_ad"];
                $kontrol = 0;
                $array_siralama = explode("\"", $sonuc["siralama"]);
                for ($i = 0; $i < count($array_siralama); $i++) {
                    if ($i % 2 == 1) {
                        if ($array_siralama[$i] == substr($_GET["value"], 5)) {
                            unset($array_siralama[$i]);
                            unset($array_siralama[$i + 1]);
                            $kontrol = 1;
                            break;
                        }
                    }
                }
                if ($kontrol == 1) {
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
                    $sorgu = "UPDATE pozisyon SET siralama = '$new_array' WHERE pozisyon_ad = '$pozisyon_ad'";
                    $db->select($sorgu);
                }
            }
            // END
            $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . substr($_GET["value"], 5) . '\' isimli özellik sütununu sildi.");';
            $db->query($sorgu);
            header("location: /eCatalog/Add/urunEkle.php?error=silindi");
            exit;
        } else { // oyle bir sutun yoksa
            echo 'bulamadı'; // HATA gönderilecek diğer sayfaya
        }
    } else if ($_POST["urunOzellik_ad"]) {
        $urunOzellik_ad = trim((mb_strtoupper($_POST["urunOzellik_ad"], 'UTF-8')));
        $urunOzellik_ad = str_replace(" ", "_", $urunOzellik_ad);
        $urunOzellik_ad = str_replace("-", "_", $urunOzellik_ad);
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|+¬]/', $urunOzellik_ad)) {
            header("location: /eCatalog/Add/urunEkle.php?error=istenmeyenKarakterSutun");
            exit;
        }
        $urunOzellik_ad = "urun_" . $urunOzellik_ad;
        $sorgu = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="urun_ozellikleri";';
        $gelenSutun = $db->select($sorgu);
        $control = 0;
        while ($sutun = mysqli_fetch_assoc($gelenSutun)) {
            if ($sutun["COLUMN_NAME"] == $urunOzellik_ad) {
                $control = 1;
                break;
            }
        }
        if ($control) {
            header("location: /eCatalog/Add/urunEkle.php?error=veritabanındaVar");
            exit;
        } else {
            echo $urunOzellik_ad;
            $connect = $db->connect();
            $sorgu = "ALTER TABLE urun_ozellikleri ADD COLUMN `$urunOzellik_ad` varchar(20) DEFAULT 0";
            $db->query($sorgu);
            $sorgu = "ALTER TABLE urun ADD COLUMN `$urunOzellik_ad` varchar(20) DEFAULT NULL";
            $db->query($sorgu);
            $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . substr($urunOzellik_ad, 5) . '\' isimli yeni bir özellik sütunu ekledi.");';
            $db->query($sorgu);
            header("location: /eCatalog/Add/urunEkle.php?error=eklendi");
            exit;
        }
    } else if ($_GET["urunOzellik_ad"]) {
        $urunOzellik_ad = trim((mb_strtoupper($_GET["urunOzellik_ad"], 'UTF-8')));
        $urunOzellik_ad = str_replace(" ", "_", $urunOzellik_ad);
        $urunOzellik_ad = str_replace("-", "_", $urunOzellik_ad);
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|+¬]/', $urunOzellik_ad)) {
            header("location: /eCatalog/Add/urunEkle.php?error=istenmeyenKarakterSutun");
            exit;
        }
        $new_Name = $urunOzellik_ad;
        $urunOzellik_ad = "urun_" . $urunOzellik_ad;
        $oldName = $_GET["oldName"];
        $sorgu = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="urun_ozellikleri";';
        $gelenSutun = $db->select($sorgu);
        $control = 0;
        while ($sutun = mysqli_fetch_assoc($gelenSutun)) {
            if ($sutun["COLUMN_NAME"] == $urunOzellik_ad) {
                $control = 1;
                break;
            }
        }
        if ($control) {
            header("location: /eCatalog/Add/urunEkle.php?error=veritabanındaVar");
            exit;
        } else {
            $urunOzellik_ad = "`" . $urunOzellik_ad . "`";
            $oldName = '`' . $_GET["oldName"] . '`';
            $sorgu = 'ALTER TABLE `urun_ozellikleri` CHANGE ' . $oldName . ' ' . $urunOzellik_ad . ' VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_turkish_ci NULL DEFAULT "0"';
            $db->query($sorgu);
            $sorgu = 'ALTER TABLE `urun` CHANGE ' . $oldName . ' ' . $urunOzellik_ad . ' VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_turkish_ci NULL DEFAULT NULL';
            $db->query($sorgu);
            // BEGIN - pozisyon tablosunda varsa öyle bir sutün kaldırcak 
            $sorgu = "SELECT * FROM pozisyon ";
            $gelen = $db->select($sorgu);
            while ($sonuc = mysqli_fetch_assoc($gelen)) {
                $pozisyon_ad = $sonuc["pozisyon_ad"];
                $kontrol = 0;
                $array_siralama = explode("\"", $sonuc["siralama"]);
                for ($i = 0; $i < count($array_siralama); $i++) {
                    if ($i % 2 == 1) {
                        if ($array_siralama[$i] == substr($_GET["oldName"], 5)) {
                            $array_siralama[$i] = $new_Name;
                            $kontrol = 1;
                            break;
                        }
                    }
                }
                if ($kontrol == 1) {
                    $new_array = "";
                    foreach ($array_siralama as $key => $value) {
                        if ($key % 2 == 1) {
                            $new_array = $new_array . '"' . $value . '"';
                        } else {
                            $new_array = $new_array . $value;
                        }
                    }
                    $sorgu = "UPDATE pozisyon SET siralama = '$new_array' WHERE pozisyon_ad = '$pozisyon_ad'";
                    $db->select($sorgu);
                }
            }
            // END
            $sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","DUZENLEME","\'' . substr($_GET["oldName"], 5) . '\' isimli özellikler sütununun adını \'' . $new_Name . '\' olarak değiştirdi.");';
            $db->query($sorgu);
            header("location: /eCatalog/Add/urunEkle.php?error=degistirildi");
            exit;
        }
    }
}

class cls_urunOzellikleri
{
    public function tablo($parametre)
    {
        $user = $_SESSION["login_userAKKO"];
        $db = new Db(); ?>
        <div align="center">
            <form action="urunEkle.php" method="POST" enctype="multipart/form-data">
                <table class="myTable">
                    <tr style="background-color: #B8274C;color:white;font-weight:bold;text-align:center;">
                        <td>Ana Katalog Seçiniz</td>
                        <td>Aktif Özellikler</td>
                    </tr>
                    <tr style="background-color:#FFE6CB;color:white;font-weight:bold;text-align:center;">
                        <td><select name="ana_ref" id="ana_ref" onchange="this.form.submit();">
                                <option value="">--Lütfen Birini Seçiniz--</option>
                                <?php $sorgu = 'SELECT * FROM urun_ozellikleri ORDER BY urunOzellik_ad';
                                $gelen = $db->select($sorgu);
                                while ($sonuc = mysqli_fetch_assoc($gelen)) {
                                    if ($parametre == $sonuc["ana_ref"]) { ?>
                                        <option selected value="<?php echo $sonuc["ana_ref"]; ?>"><?php echo $sonuc["urunOzellik_ad"]; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $sonuc["ana_ref"]; ?>"><?php echo $sonuc["urunOzellik_ad"]; ?></option>
                                <?php }
                                }
                                $sorgu = 'SELECT * FROM urun_ozellikleri WHERE ana_ref ="' . $parametre . '"';
                                $gelen = $db->select($sorgu);
                                $sonuc = mysqli_fetch_assoc($gelen);

                                $sorgu = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="urun_ozellikleri";';
                                $gelenSutun = $db->select($sorgu);
                                $boyut = 0;
                                while ($sutun = mysqli_fetch_assoc($gelenSutun)) {
                                    if ($sutun["COLUMN_NAME"] == "ana_ref" || $sutun["COLUMN_NAME"] == "urunOzellik_ad") {
                                        continue;
                                    }
                                    $boyut++;
                                }
                                $boyut = (int) (($boyut / 2) + 1);
                                $sorgu = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="urun_ozellikleri";';
                                $gelenSutun = $db->select($sorgu);
                                $sayac = 0; ?>
                        <td>
                            <table class="myTable">
                                <tr style="text-align: center;color:black;">
                                    <?php while ($sutun = mysqli_fetch_assoc($gelenSutun)) {
                                        if ($sutun["COLUMN_NAME"] == "ana_ref" || $sutun["COLUMN_NAME"] == "urunOzellik_ad") {
                                            continue;
                                        }
                                        $sayac++;
                                        if ($sayac == $boyut) { ?>
                                <tr style="text-align: center;color:black;">
                                <?php }
                                        $temp = $sutun["COLUMN_NAME"];
                                        if ($sonuc[$temp] == '0') { ?>
                                    <td><input type="checkbox" value="1" name="<?php echo $sutun["COLUMN_NAME"]; ?>" /><?php echo substr($sutun["COLUMN_NAME"], 5); ?></td>
                                <?php } else if ($sonuc[$temp] == '1') { ?>
                                    <td><input type="checkbox" checked value="1" name="<?php echo $sutun["COLUMN_NAME"]; ?>" /><?php echo substr($sutun["COLUMN_NAME"], 5); ?></td>
                            <?php }
                                    } ?>
                                </tr>
                    </tr>
                </table>
                </td>
                </tr>
                </table>
                <td>
                    <input type="hidden" name="hangisi" value="4">
                    <button style="margin-top: 10px;" type="submit" name="buton" value="Özellikleri Değiştir" class="btn btn-primary">Özellikleri Değiştir</button>
                </td>
            </form>
        </div>
        <br>
        <hr>
        <div class="urunlerTablo" style="padding-top:25px;width:25%;margin:0 auto;">
            <form action="/eCatalog/Add/urunOzellikleri.php" method="POST" enctype="multipart/form-data">
                <table class="table" rules="all" style="border-style:None;text-align:center;border-collapse:collapse;">
                    <tr style="background-color: #B8274C;color:white;font-weight:bold;">
                        <td colspan="2">Yeni Özellik Ekle</td>
                    </tr>
                    <tr style="background-color:#FFE6CB;color:white;font-weight:bold;border:0">
                        <td>
                            <input type="text" name="urunOzellik_ad">
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary btn-sm">Ekle</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <br>
        <hr>
        <div class="urunlerTablo" style="padding-top:25px;width:25%;margin:0 auto;">
            <form action="/eCatalog/Add/urunEkle.php" method="GET" enctype="multipart/form-data">
                <table class="table" rules="all" style="border-style:None;text-align:center;border-collapse:collapse;">
                    <tr style="background-color: #B8274C;color:white;font-weight:bold;">
                        <td>Düzenle</td>
                        <?php if ($_GET["edit"]) { ?>
                            <td colspan="2">Yeni İsim</td>
                        <?php } else { ?>
                            <td>Özellik Seçiniz</td>
                        <?php } ?>
                    </tr>
                    <tr style="background-color:#FFE6CB;color:white;font-weight:bold;">
                        <td style="color:black;">
                            <?php if ($user["permission_delete"] == "1") { ?>
                                <a data-toggle="modal" data-target="#exampleModal" data-delete-data="urunOzellikleri_url=<?php echo $_GET["ozellik_ad"]; ?>">
                                    <i class="fas fa-trash-alt" style="color: #007bff;cursor: pointer;"></i>
                                </a>
                            <?php } ?>
                            <a href="/eCatalog/Add/urunEkle.php?edit=<?php echo $_GET["ozellik_ad"]; ?>">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                        <?php if ($_GET["edit"]) { ?>
                            <td scope="col">
                                <input type="text" value="<?php echo substr($_GET["edit"], 5); ?>" name="urunOzellik_ad">
                            </td>
                            <input type="hidden" name="oldName" value="<?php echo $_GET["edit"]; ?>">
                            <td>
                                <button type="submit" class="btn btn-primary btn-sm">Kaydet</button>
                            </td>
                        <?php } else { ?>
                            <td scope="col" style="max-width:100px;min-width:75px;color:white;">
                                <select name="ozellik_ad" onchange="this.form.submit();">
                                    <option value="">--Lütfen Birini Seçiniz--</option>
                                    <?php
                                    $sorgu = 'SELECT * FROM urun_ozellikleri';
                                    $gelen = $db->select($sorgu);
                                    $sonuc = mysqli_fetch_assoc($gelen);

                                    $sorgu = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="urun_ozellikleri";';
                                    $gelenSutun = $db->select($sorgu);
                                    while ($sutun = mysqli_fetch_assoc($gelenSutun)) {
                                        if ($sutun["COLUMN_NAME"] == "ana_ref" || $sutun["COLUMN_NAME"] == "urunOzellik_ad") {
                                            continue;
                                        } else if (@$_GET["ozellik_ad"] == $sutun["COLUMN_NAME"]) { ?>
                                            <option selected value="<?php echo $sutun["COLUMN_NAME"]; ?>"><?php echo substr($sutun["COLUMN_NAME"], 5); ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $sutun["COLUMN_NAME"]; ?>"><?php echo substr($sutun["COLUMN_NAME"], 5); ?></option>
                                        <?php } ?>
                                    <?php } ?>

                                </select>
                            </td>
                        <?php } ?>
                    </tr>
                </table>
            </form>
        </div>
<?php }
}
?>