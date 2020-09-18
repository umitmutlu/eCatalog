<?php
include_once("config.php");
$db = new Db();
$user = $_SESSION["login_userAKKO"];
$altbaslik_no = $_GET["altbaslik_no"];
$kesici_uclar_no = $_GET["kesici_uclar_no"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ürünler</title>
    <?php include("header.php"); ?>
    <script>
        function isDelete(deleteVariable) {
            console.log("girdiiiiiiii");
            var splitVariable = deleteVariable.split("=");
            console.log(splitVariable[0]);
            console.log(splitVariable[1]);
            console.log(splitVariable[2]);
            console.log("anaref = " + splitVariable[3]);
            if (splitVariable[0] == "ozellik") {
                window.location = "/eCatalog/Edit/ozellikDuzenle.php?delete=ozellik&ozellik_ad=" + splitVariable[1] + "=" + splitVariable[2] + "=" + splitVariable[3];
            } else if (splitVariable[0] == "urun") {
                window.location = "/eCatalog/Edit/Duzenle.php?delete=urun&urun_no=" + splitVariable[1] + "=" + splitVariable[2] + "=" + splitVariable[3];
            }
        }

        function sliderChange() {
            sliderValue = document.getElementById("sliderValue").value;
            window.location = "/eCatalog/urunler.php?sliderValue=" + sliderValue + "&altbaslik_no=" + '<?php echo $altbaslik_no; ?>' + "&kesici_uclar_no=" + '<?php echo $kesici_uclar_no ?>';
        }
    </script>
    <?php
    // BEGIN - Silme işlemlerinde, şifre kontrolü
    if (@$_POST["password_check"] == "delete") {
        $username = $user["username"];
        $password = $_POST["password"];
        $altbaslik_no = $_POST["altbaslik_no"];
        $kesici_uclar_no = $_POST["kesici_uclar_no"];
        $deleteVariable = $_POST["deleteVariable"];
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
            header('location: /eCatalog/urunler.php?error=sifreYanlis&altbaslik_no=' . $altbaslik_no . '&kesici_uclar_no=' . $kesici_uclar_no);
            exit;
        }
    }
    // END - Silme işlemlerinde, şifre kontrolü 
    ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <?php if ($user && $_GET["sliderValue"] == "on") { ?>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
        <script src="./jquery/jquery.dragtable.js"></script>
        <link rel="stylesheet" type="text/css" href="./jquery/dragtable.css" />
    <?php } ?>
</head>

<body>
    <div style="width: 50%;margin: 0 auto;">
        <?php include("errors.php"); ?>
    </div>
    <div class="resimler" style="display:flex;justify-content:center;padding-top:25px;">
        <?php
        $sorgu = 'SELECT * FROM altbaslik WHERE altBaslik_no = "' . $altbaslik_no . '" ';
        $gelen = $db->select($sorgu);
        while ($sonuc = mysqli_fetch_assoc($gelen)) {
            if ($_GET["editImg"] == "1" && $user) { ?>
                <div style="text-align: center;">
                    <a href="/eCatalog/urunler.php?altbaslik_no=<?php echo $altbaslik_no; ?>&kesici_uclar_no=<?php echo $kesici_uclar_no; ?>"><i class="fas fa-times-circle" style="padding-bottom: 10px;"></i> İptal</a>
                    <div class="bir" style="align-self: center; border: solid;border-color: #FFE6CB; margin-right: 50px; padding: 50px;">
                        <form action="/eCatalog/Edit/Duzenle.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="edit" value="aciImg">
                            <input type="hidden" name="img_no" value="1">
                            <input type="hidden" name="kesici_uclar_no" value="<?php echo $kesici_uclar_no; ?>">
                            <input type="hidden" name="altbaslik_no" value="<?php echo $altbaslik_no; ?>">
                            <input style="width: 200px;" type="file" name="dosya" accept=".jpg,.jpeg,.png,.bmp,.gif">
                            <input type="submit" value="Kaydet">
                        </form>
                    </div>
                </div>

            <?php } else { ?>
                <div class="bir" style="text-align: center;">
                    <?php if ($user) { ?>
                        <a href="/eCatalog/urunler.php?editImg=1&altbaslik_no=<?php echo $altbaslik_no; ?>&kesici_uclar_no=<?php echo $kesici_uclar_no; ?>"><i class="fas fa-edit" style="padding-bottom: 10px;"></i></a>
                    <?php } ?>
                    <img style="border: solid;border-color: #FFE6CB;" width="580px" height="165px" class="imgcat" src=" <?php echo $sonuc["altBaslik_imgPath"]  ?>">
                </div>
            <?php }
            if ($_GET["editImg"] == "2" && $user) {  ?>
                <div style="text-align: center;">
                    <a href="/eCatalog/urunler.php?altbaslik_no=<?php echo $altbaslik_no; ?>&kesici_uclar_no=<?php echo $kesici_uclar_no; ?>"><i class="fas fa-times-circle" style="padding-bottom: 10px;"></i> İptal</a>
                    <div class="bir" style="align-self: center; border: solid;border-color: #FFE6CB; margin-right: 50px; padding: 50px;">
                        <form action="/eCatalog/Edit/Duzenle.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="edit" value="aciImg">
                            <input type="hidden" name="img_no" value="2">
                            <input type="hidden" name="kesici_uclar_no" value="<?php echo $kesici_uclar_no; ?>">
                            <input type="hidden" name="altbaslik_no" value="<?php echo $altbaslik_no; ?>">
                            <input style="width: 200px;" type="file" name="dosya" accept=".jpg,.jpeg,.png,.bmp,.gif">
                            <input type="submit" value="Kaydet">
                        </form>
                    </div>
                </div>
            <?php } else { ?>
                <div class="iki" style="text-align: center;">
                    <?php if ($user) { ?>
                        <a href="/eCatalog/urunler.php?editImg=2&altbaslik_no=<?php echo $altbaslik_no; ?>&kesici_uclar_no=<?php echo $kesici_uclar_no; ?>"><i class="fas fa-edit" style="padding-bottom: 10px;"></i></a>
                    <?php } ?>
                    <img style="border: solid;border-color: #FFE6CB;" width="180px" height="165px" class="imgcat" src=" <?php echo $sonuc["altBaslik_imgTeknik"]  ?>">
                </div>
            <?php } ?>
        <?php  }
        ?>
    </div>
    <hr />
    <?php if ($user) { ?>
        <div style="text-align: -webkit-right; padding-right: 50px;">
            <table>
                <tr>
                    <td>
                        Sütun Taşıma : &nbsp;
                    </td>
                    <td>
                        <label class="switch">
                            <?php if ($_GET["sliderValue"] == "on") { ?>
                                <input id="sliderValue" value="off" type="checkbox" onchange="sliderChange();" checked>
                            <?php } else { ?>
                                <input id="sliderValue" value="on" type="checkbox" onchange="sliderChange();">
                            <?php } ?>
                            <span class="slider round"></span>
                        </label>
                    </td>
                </tr>
            </table>
        </div>
    <?php } ?>
    <div <?php if ($_GET["sliderValue"] != "on") { ?> class="urunlerTablo" <?php } ?>>
        <table <?php if ($user && $_GET["sliderValue"] == "on") {
                    echo 'id="demo"';
                } ?> class="table" rules="all" style="width:60%;">
            <thead>
                <tr style="background-color: #B8274C;">
                    <?php
                    $sorgu = 'SELECT * FROM altbaslik WHERE altBaslik_no = "' . $altbaslik_no . '"';
                    $gelen = $db->select($sorgu);
                    $sonuc = mysqli_fetch_assoc($gelen);
                    $alt_ref = $sonuc["alt_ref"];
                    if ($user && $_GET["sliderValue"] != "on") { ?>
                        <th scope="col" style="color:white;width:50px;">Düzenle</th>
                    <?php } ?>
                    <?php if ($_GET["sliderValue"] != "on") { ?>
                        <th scope="col" style="color:white;">Ürün Adı <?php if ($user) { ?>
                                <a href="/eCatalog/urunler.php?type=yeni&kesici_uclar_no=<?php echo $kesici_uclar_no; ?>&altbaslik_no=<?php echo $altbaslik_no; ?>">
                                    <i class="fas fa-plus-square" style="color: white;"></i>
                                </a>
                            <?php } ?>
                        </th>
                    <?php } ?>
                    <?php
                    $sorgu = 'SELECT * FROM altkatalog WHERE alt_no = "' . $alt_ref . '"';
                    $gelen = $db->select($sorgu);
                    $sonuc = mysqli_fetch_assoc($gelen);
                    $ana_ref = $sonuc["ana_ref"];

                    /*$sorgu = 'SELECT * FROM urun WHERE urun_ad = "hurda_' . $altbaslik_no . '_' . $kesici_uclar_no . '"';
                    $gelen = $db->select($sorgu);
                    $sonuc = mysqli_fetch_assoc($gelen);*/

                    $sorgu = 'SELECT siralama FROM pozisyon WHERE pozisyon_ad="siralama_' . $altbaslik_no . '_' . $kesici_uclar_no . '"';
                    $gelen = $db->select($sorgu);
                    $sonuc = mysqli_fetch_assoc($gelen);
                    $dizi = explode("\"", $sonuc["siralama"]);
                    for ($i = 0; $i < count($dizi); $i++) {
                        if ($i % 2 == 1) {
                            if ($_GET["changeSutun"] == $dizi[$i]) { ?>
                                <th id="<?php echo $dizi[$i]; ?>" scope="col" style="max-width:125px;min-width:75px;color:white;">
                                    <?php $sorgu = 'SELECT * FROM urun WHERE urun_ad = "hurda_' . $altbaslik_no . '_' . $kesici_uclar_no . '"';
                                    $gelen = $db->select($sorgu);
                                    $sonuc = mysqli_fetch_assoc($gelen);
                                    $sorgu = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="urun_ozellikleri";';
                                    $gelenSutun = $db->select($sorgu);
                                    echo $dizi[$i]; ?>
                                    <a style="color : white; float: right;" href="/eCatalog/urunler.php?kesici_uclar_no=<?php echo $kesici_uclar_no; ?>&altbaslik_no=<?php echo $altbaslik_no; ?>">
                                        İptal <i class="fas fa-times-circle"></i>
                                    </a>
                                    <form action="/eCatalog/Edit/ozellikDuzenle.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="type" value="changeSutun">
                                        <input type="hidden" name="kesici_uclar_no" value="<?php echo $kesici_uclar_no; ?>">
                                        <input type="hidden" name="altbaslik_no" value="<?php echo $altbaslik_no; ?>">
                                        <input type="hidden" name="ozellik_ad_eski" value="<?php echo "urun_" . $_GET["changeSutun"]; ?>">
                                        <select name="ozellik_ad_yeni">
                                            <option value="">Sütun Seçin</option>
                                            <?php while ($sutun = mysqli_fetch_assoc($gelenSutun)) {
                                                $temp = $sutun["COLUMN_NAME"];
                                                if ($temp == "ana_ref" || $temp == "urunOzellik_ad" || $temp == "urun_RIGHT" || $temp == "urun_LEFT") {
                                                    continue;
                                                } else if ($sonuc[$temp] == NULL) { ?>
                                                    <option value="<?php echo $sutun["COLUMN_NAME"]; ?>"><?php echo substr($sutun["COLUMN_NAME"], 5); ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <button type="submit" id="completed-task" class="fabutton">
                                            <i class="fas fa-save" style="color : white;"></i>
                                        </button>
                                    </form>
                                <?php } else { ?>
                                <th id="<?php echo $dizi[$i]; ?>" scope="col" style="max-width:100px;min-width:75px;color:white;"><?php echo $dizi[$i]; ?>
                                    <?php if ($user && $_GET["sliderValue"] != "on") { ?>
                                        <a class="duzenleIconlar" data-toggle="modal" data-target="#exampleModal" data-delete-data="ozellik=<?php echo 'urun_' . $dizi[$i]; ?>&kesici_uclar_no=<?php echo $kesici_uclar_no ?>&altbaslik_no=<?php echo $altbaslik_no ?>">
                                            <i class="fas fa-minus-circle" style="color : white;"></i>
                                        </a>
                                        <?php if ($dizi[$i] != "RIGHT" && $dizi[$i] != "LEFT") { ?>
                                            <a href="/eCatalog/urunler.php?changeSutun=<?php echo $dizi[$i]; ?>&altbaslik_no=<?php echo $altbaslik_no; ?>&kesici_uclar_no=<?php echo $kesici_uclar_no; ?>">
                                                <i class="fas fa-exchange-alt" style="color : white;"></i>
                                            </a>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                </th>
                        <?php }
                    } ?>
                        <?php
                        if ($user && $_GET["sliderValue"] != "on") { ?>
                            <th style="text-align: center;">
                                <?php $sorgu = 'SELECT * FROM urun WHERE urun_ad = "hurda_' . $altbaslik_no . '_' . $kesici_uclar_no . '"';
                                $gelen = $db->select($sorgu);
                                $sonuc = mysqli_fetch_assoc($gelen);
                                $sorgu = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="urun_ozellikleri";';
                                $gelenSutun = $db->select($sorgu); ?>
                                <form action="/eCatalog/Edit/ozellikDuzenle.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="type" value="ekleOzellik">
                                    <input type="hidden" name="kesici_uclar_no" value="<?php echo $kesici_uclar_no; ?>">
                                    <input type="hidden" name="altbaslik_no" value="<?php echo $altbaslik_no; ?>">
                                    <select name="ozellik_ad">
                                        <option value="">Sütun Ekle</option>
                                        <?php while ($sutun = mysqli_fetch_assoc($gelenSutun)) {
                                            $temp = $sutun["COLUMN_NAME"];
                                            if ($temp == "ana_ref" || $temp == "urunOzellik_ad") {
                                                continue;
                                            } else if ($sonuc[$temp] == NULL) { ?>
                                                <option value="<?php echo $sutun["COLUMN_NAME"]; ?>"><?php echo substr($sutun["COLUMN_NAME"], 5); ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <button type="submit" id="completed-task" class="fabutton">
                                        <i class="fas fa-plus-circle" style="color: white;"></i>
                                    </button>
                                </form>
                            </th>
                        <?php } ?>
                </tr>
            </thead>

            <?php
            $sorgu = 'SELECT * FROM urun WHERE alt_ref = "' . $alt_ref . '" AND baslik_ref = "' . $altbaslik_no . '" AND kesici_uclar_ref = "' . $kesici_uclar_no . '"';
            $gelenUrun = $db->select($sorgu);
            while ($sonucUrun = mysqli_fetch_assoc($gelenUrun)) { ?>
                <tbody>
                    <tr style="background-color:#FFE6CB;">
                        <?php if ($user && $_GET["sliderValue"] != "on") { ?>
                            <th style="background-color:#FFE6CB;">
                                <?php if ($_GET["edit"] == $sonucUrun["urun_no"]) { ?>
                                    <a href="/eCatalog/urunler.php?kesici_uclar_no=<?php echo $kesici_uclar_no; ?>&altbaslik_no=<?php echo $altbaslik_no; ?>">
                                        <i class="fas fa-times-circle"></i> İptal
                                    </a>
                                <?php } else { ?>
                                    <?php if ($user["permission_delete"] == "1") { ?>
                                        <a class="duzenleIconlar" data-toggle="modal" data-target="#exampleModal" data-delete-data="urun=<?php echo $sonucUrun["urun_no"]; ?>&kesici_uclar_no=<?php echo $kesici_uclar_no ?>&altbaslik_no=<?php echo $altbaslik_no ?>">
                                            <i class="fas fa-trash-alt" style="color: #007bff"></i>
                                        </a>
                                    <?php } ?>
                                    <a href="/eCatalog/urunler.php?edit=<?php echo $sonucUrun["urun_no"]; ?>&altbaslik_no=<?php echo $altbaslik_no; ?>&kesici_uclar_no=<?php echo $kesici_uclar_no; ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                <?php } ?>
                            </th>
                        <?php }
                        if ($_GET["sliderValue"] != "on") { ?>
                            <td style="background-color: #D4937D;">
                                <?php if ($_GET["edit"] == $sonucUrun["urun_no"]) { ?>
                                    <form action="./Edit/Duzenle.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="edit" value="urun">
                                        <input type="hidden" name="urun_no" value="<?php echo $sonucUrun["urun_no"]; ?>">
                                        <input type="text" name="urun_ad" value="<?php echo $sonucUrun["urun_ad"]; ?>">
                                        <input type="hidden" name="altbaslik_no" value="<?php echo $altbaslik_no ?>">
                                        <input type="hidden" name="kesici_uclar_no" value="<?php echo $kesici_uclar_no ?>">
                                    <?php } else { ?>
                                        <a style="color:black;font-weight:bold;text-decoration: none;" href="urun.php?urun_no=<?php echo $sonucUrun["urun_no"]; ?> "><?php echo $sonucUrun["urun_ad"]; ?> </a>
                                    <?php } ?>
                            </td>
                            <?php }
                        $sorgu = 'SELECT siralama FROM pozisyon WHERE pozisyon_ad="siralama_' . $altbaslik_no . '_' . $kesici_uclar_no . '"';
                        $gelen = $db->select($sorgu);
                        $sonuc = mysqli_fetch_assoc($gelen);
                        $dizi = explode("\"", $sonuc["siralama"]);
                        if ($_GET["edit"] == $sonucUrun["urun_no"]) {
                            for ($i = 0; $i < count($dizi); $i++) {
                                if ($i % 2 == 1) {
                                    $temp = 'urun_' . $dizi[$i]; ?>
                                    <td>
                                        <?php if ($temp == 'urun_RIGHT' || $temp == 'urun_LEFT') {
                                            if ($sonucUrun[$temp] == NULL || $sonucUrun[$temp] == "0") { ?>
                                                <input type="checkbox" name="<?php echo $temp; ?>" value="1">
                                            <?php } else if ($sonucUrun[$temp] == "1") { ?>
                                                <input type="checkbox" checked name="<?php echo $temp; ?>" value="1">
                                            <?php } ?>
                                    </td>
                                <?php continue;
                                        } ?>
                                <a style="font-weight:bold;">
                                    <?php if ($sonucUrun[$temp] == NULL || $sonucUrun[$temp] == "0") { ?>
                                        <input style="width: 50px;box-sizing: border-box;-webkit-box-sizing:border-box;-moz-box-sizing: border-box;" type="text" name="<?php echo $temp; ?>" value="0">
                                    <?php } else { ?>
                                        <input style="width: 50px;box-sizing: border-box;-webkit-box-sizing:border-box;-moz-box-sizing: border-box;" type="text" name="<?php echo $temp; ?>" value="<?php echo $sonucUrun[$temp]; ?>">
                                    <?php } ?>
                                </a>
                                </td>
                        <?php
                                }
                            } ?>
                        <td>
                            <input type="file" name="dosya" accept=".jpg,.jpeg,.png,.bmp,.gif" style="color: transparent; width:100px;" />
                        </td>
                        <td>
                            <p><input width="10px" type="submit" name="buton" value="Kaydet"></p>
                        </td>
                        </form>
                        <?php } else {
                            for ($i = 0; $i < count($dizi); $i++) {
                                if ($i % 2 == 1) {
                                    $temp = 'urun_' . $dizi[$i]; ?>
                                <td>
                                    <?php if ($temp == 'urun_RIGHT' || $temp == 'urun_LEFT') {
                                        if ($sonucUrun[$temp] == NULL || $sonucUrun[$temp] == "0") { ?>
                                            <i class="far fa-circle"></i>
                                        <?php } else if ($sonucUrun[$temp] == "1") { ?>
                                            <i class="fas fa-circle"></i>
                                    <?php }
                                        echo '</td>';
                                        continue;
                                    } ?>
                                    <a style="font-weight:bold;">
                                        <?php if ($sonucUrun[$temp] == NULL || $sonucUrun[$temp] == "0") {
                                            echo '-';
                                        } else {
                                            echo $sonucUrun[$temp];
                                        }
                                        ?>
                                    </a>
                                </td>
                    <?php }
                            }
                        } ?>
                    </tr>
                    <?php }
                if ($user) {
                    if ($_GET["type"] == "yeni") { ?>
                        <form action="Add/urunEkle.php?type=plus" method="POST" enctype="multipart/form-data" class="login-container">
                            <tr style="background-color:#FFE6CB;">
                                <td>
                                    <a href="/eCatalog/urunler.php?kesici_uclar_no=<?php echo $kesici_uclar_no; ?>&altbaslik_no=<?php echo $altbaslik_no; ?>">
                                        <i class="fas fa-times-circle"></i> <b>İptal</b>
                                    </a>
                                </td>
                                <td>
                                    <p><input width="10px" type="text" name="takimKodu" placeholder="Ürün Adı"></p>
                                </td>
                                <?php
                                $sorgu = 'SELECT siralama FROM pozisyon WHERE pozisyon_ad="siralama_' . $altbaslik_no . '_' . $kesici_uclar_no . '"';
                                $gelen = $db->select($sorgu);
                                $sonuc = mysqli_fetch_assoc($gelen);
                                $dizi = explode("\"", $sonuc["siralama"]);
                                for ($i = 0; $i < count($dizi); $i++) {
                                    if ($i % 2 == 1) {
                                        $temp = 'urun_' . $dizi[$i];
                                        if ($temp == 'urun_RIGHT') { ?>
                                            <td>
                                                <input type="checkbox" id="box-right" name="<?php echo $temp; ?>" value="1">
                                            </td>
                                        <?php continue;
                                        } else if ($temp == 'urun_LEFT') { ?>
                                            <td>
                                                <input type="checkbox" id="box-left" name="<?php echo $temp; ?>" value="1">
                                            </td>
                                        <?php continue;
                                        } ?>
                                        <div class="yeniurunler">
                                            <td>
                                                <p><input style="width: 50px;box-sizing: border-box;-webkit-box-sizing:border-box;-moz-box-sizing: border-box;" type="text" name=<?php echo $temp; ?> placeholder="<?php echo substr($temp, 5); ?> Değeri"></p>
                                            </td>
                                        </div>
                                <?php }
                                } ?>
                                <input type="hidden" name="alt_ref" value=<?php echo $alt_ref;  ?>>
                                <input type="hidden" name="baslik_ref" value=<?php echo $altbaslik_no; ?>>
                                <input type="hidden" name="kesici_uclar_ref" value=<?php echo $kesici_uclar_no; ?>>
                                <td><input type="file" name="dosya" accept=".jpg,.jpeg,.png,.bmp,.gif" style="color: transparent; width:100px;" /></td>
                                <td>
                                    <p><input width="10px" type="submit" name="buton" value="Ürün Ekle"></p>
                                </td>
                            </tr>
                        </form>
    </div>
<?php   }  /* PHPDEN HTMLE ÇEVRİLDİ KONTROL EDİLECEK!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! */
                } ?>
</tbody>
</table>
</div>
<?php if ($user && $_GET["sliderValue"] == "on") { ?>
    <script type="text/javascript">
        $('#demo').dragtable({
            persistState: function(table) {
                if (!window.sessionStorage) return;
                var ss = window.sessionStorage;
                table.el.find('th').each(function(i) {
                    if (this.id != '') {
                        table.sortOrder[this.id] = i;
                    }
                });
                //ss.setItem('tableorder', JSON.stringify(table.sortOrder));
                var tabloSirasi = JSON.stringify(table.sortOrder);
                console.log(tabloSirasi);
                //$('#str').val(JSON.stringify(tabloSirasi)); 
                window.location = "/eCatalog/siralama.php?type=siralama&kesici_uclar_no=" + '<?php echo $_GET["kesici_uclar_no"]; ?>' + "&altbaslik_no=" + '<?php echo $_GET["altbaslik_no"]; ?>' + "&value=" + tabloSirasi;
            },
        });
    </script>
<?php } ?>
</div>
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
                    Silmek istediğinize emin misiniz ?
                    <form class="form-inline" action="urunler.php" method="POST">
                        <input type="hidden" name="password_check" value="delete">
                        <input type="hidden" name="altbaslik_no" value="<?php echo $altbaslik_no; ?>">
                        <input type="hidden" name="kesici_uclar_no" value="<?php echo $kesici_uclar_no; ?>">
                        <input type="hidden" name="deleteVariable" id="deleteVariable" value="default">
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
<style>
    @media screen and (max-width:990px) {
        .resimler {
            -webkit-flex-flow: row wrap;
            text-align: -webkit-center;
        }

        .resimler img {
            display: block;
            width: 50%;
            text-align: -webkit-center;
        }
    }

    img {
        padding: 0;
        display: block;
        margin: 0 auto;
        max-height: 100%;
        max-width: 100%;
    }

    .resimler .bir {
        margin-right: 20px;
    }

    @media only screen and (max-width: 400px) {

        .resimler .bir,
        .resimler .iki {
            padding: 0;
            margin: 0;
            width: 100%;
        }

        /* .urunlerTablo table {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        .urunlerTablo {
            width: 100%;
            overflow-x: auto;

        }*/
    }

    .urunlerTablo {
        margin-top: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .urunlerTablo th {
        /*asdasdasdasdasdasdasdasdasdasds */
    }

    .fabutton {
        background: none;
        padding: 0px;
        border: none;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>

</html>