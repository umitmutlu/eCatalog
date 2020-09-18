<?php
$urun_no = $_GET["urun_no"];
include_once("config.php");
$db = new Db();
$sorgu = 'SELECT * FROM urun WHERE urun_no = "' . $urun_no . '"';
$gelenUrun = $db->select($sorgu);
$sonucUrun = mysqli_fetch_assoc($gelenUrun);
$altbaslik_no = $sonucUrun["baslik_ref"];
$kesici_uclar_no = $sonucUrun["kesici_uclar_ref"];
$urun_imgPath = $sonucUrun["urun_imgPath"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("header.php"); ?>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/mootools/1.4.1/mootools-yui-compressed.js"></script>
    <script type="text/javascript" src="./imageSelect/Source/FancySelect.js"></script>
    <link type="text/css" href="./imageSelect/Source/FancySelect.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/eCatalog/urun.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ürün</title>
    <script>
        function isApply() {
            var r = confirm("Bu yedek parçalar, bu kategorideki tüm ürünlere eklenecek. Emin misiniz ?");
            if (r == true) {
                document.getElementById("butonScript").value = "Hepsine Uygula";
                document.getElementById("butonScript").click();
                //document.yedekParcaForm.submit();
            } else {
                console.log("İptal Edildi.");
            }
        }

        function isDelete() {
            document.getElementById("yedekParcaDeleteForm").submit();
        }

        jQuery.noConflict();
        window.addEvent('domready', function() {
            $('yummy').fancySelect({
                showText: false,
                showImages: true,
                className: 'fancy-select fancy-select-big'
            });
        });
    </script>

</head>
<!-- BURADA 2D RESİM EKSİK O EKLENECEK -->

<body>
    <div class="resimler">
        <ul class="flex-container" style="justify-content: space-between;">
            <?php $sorgu = 'SELECT * FROM urun WHERE urun_no = "' . $urun_no . '"';
            $gelen = $db->select($sorgu);
            $sonucUrun = mysqli_fetch_assoc($gelen);
            $alt_ref = $sonucUrun["alt_ref"];
            $baslik_ref = $sonucUrun["baslik_ref"];
            $kesici_uclar_ref = $sonucUrun["kesici_uclar_ref"];
            $sorgu = 'SELECT * FROM altbaslik WHERE alt_ref = "' . $alt_ref . '" AND altBaslik_no = "' . $baslik_ref . '" ';
            $gelen = $db->select($sorgu);
            while ($sonuc = mysqli_fetch_assoc($gelen)) { ?>
                <div class="birResim">
                    <a href="<?php echo $sonuc["altBaslik_imgPath"]; ?>" download><i class="fas fa-arrow-circle-down" style="float: right;padding-bottom: 10px;"></i></a>
                    <img style="border: solid;border-color: #FFE6CB;" width=" 580px" height="165px" class="imgcat" src="<?php echo $sonuc["altBaslik_imgPath"]; ?>">
                </div>
                <div class="ikiResim">
                    <a href="<?php echo $sonuc["altBaslik_imgTeknik"]; ?>" download><i class="fas fa-arrow-circle-down" style="float: right;padding-bottom: 10px;"></i></a>
                    <img style="border: solid;border-color: #FFE6CB;" width="180px" height="165px" class="imgcat" src="<?php echo $sonuc["altBaslik_imgTeknik"]; ?>">
                </div>
            <?php } ?>
        </ul>
    </div>
    <hr>
    <div class="tablos">
        <div class="urunTablo">
            <table width="75%" cellspacing="0" rules="all" style="background-color:#B8274C;border-style:None;text-align:center;border-collapse:collapse;margin:0 auto;">
                <tbody>
                    <tr class="SpecsOfTable">
                        <th scope="col" style="color:white;">Ürün Adı</th>
                        <?php
                        $sorgu = 'SELECT siralama FROM pozisyon WHERE pozisyon_ad="siralama_' . $altbaslik_no . '_' . $kesici_uclar_no . '"';
                        $gelen = $db->select($sorgu);
                        $sonuc = mysqli_fetch_assoc($gelen);
                        $dizi = explode("\"", $sonuc["siralama"]);
                        for ($i = 0; $i < count($dizi); $i++) {
                            if ($i % 2 == 1) { ?>
                                <th id="<?php echo $dizi[$i]; ?>" scope="col" style="max-width:100px;min-width:75px;color:white;">
                                    <?php echo $dizi[$i]; ?>
                                </th>
                        <?php }
                        } ?>
                    </tr>
                    <?php $sorgu = 'SELECT * FROM urun WHERE urun_no = "' . $urun_no . '"';
                    $gelenUrun = $db->select($sorgu);
                    $sonucUrun = mysqli_fetch_assoc($gelenUrun); ?>
                    <tr class="productsOfTable" style="background-color:#FFE6CB;">
                        <td class="familyGridParams" style="color:black;font-weight:bold;"><?php echo $sonucUrun['urun_ad']; ?></td>
                        <?php
                        $sorgu = 'SELECT siralama FROM pozisyon WHERE pozisyon_ad="siralama_' . $altbaslik_no . '_' . $kesici_uclar_no . '"';
                        $gelen = $db->select($sorgu);
                        $sonuc = mysqli_fetch_assoc($gelen);
                        $dizi = explode("\"", $sonuc["siralama"]);
                        for ($i = 0; $i < count($dizi); $i++) {
                            if ($i % 2 == 1) {
                                $temp = 'urun_' . $dizi[$i]; ?>
                                <td>
                                    <?php if ($temp == 'urun_RIGHT' || $temp == 'urun_LEFT') {
                                        if ($sonucUrun[$temp] == NULL || $sonucUrun[$temp] == "0") { ?>
                                            <i class="far fa-circle"></i>
                                        <?php } else if ($sonucUrun[$temp] == "1") { ?>
                                            <i class="fas fa-circle"></i>
                                        <?php } ?>
                                </td>
                            <?php continue;
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
                        } ?>
                    </tr>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        <br>
    </div>

    <table style="margin:0 auto;margin-bottom:50px;">
        <tbody>
            <div class="teknikOfProduct container" style="background-color: transparent;margin-bottom:50px;">
                <div class="row">
                    <tr>
                        <td style=" text-align:right;">
                            <label for="teknikImg">Teknik Resim</label></td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <div class="birResim" name="teknikImg" style="text-align:right;margin:0;padding:0;border:solid;border-color:orange;">
                                <img width="500px" height="400px" class="imgcat" src="<?php echo $urun_imgPath; ?>">
                            </div>
                        </td>
                    </tr>
        </tbody>
    </table>



    <div class="spareTablo">
        <table cellspacing="0" rules="all" id="content_gvwSpareParts" style="border-width:0px;width:50%;border-collapse:collapse;text-align:center;margin:0 auto;">
            <tbody>
                <tr style="color:white;background-color:#B8274C;">
                    <?php if ($user) { ?>
                        <th>
                            Düzenle
                        </th>
                    <?php } ?>
                    <th scope="col" style="text-align:left;">&nbsp;&nbsp;Yedek Parçalar <?php if ($user) { ?>
                            <a href="/eCatalog/urun.php?type=yeni&urun_no=<?php echo $urun_no; ?>"><i class="fas fa-plus-square"></i></a>
                        <?php } ?>
                    </th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                <!-- while ile yedek parçaları yazdıracağız -->
                <?php $sorgu = 'SELECT * FROM kesici_uclar WHERE kesici_uclar_no = "' . $kesici_uclar_ref . '"';
                $gelen = $db->select($sorgu);
                $sonuc = mysqli_fetch_assoc($gelen); ?>
                <tr>
                    <?php if ($user) { ?>
                        <td></td>
                    <?php } ?>
                    <td>
                        <div><img width="50px" height="50px" class="imgcat" src="<?php echo $sonuc["kesici_uclar_imgPath"]; ?>"></div>
                    </td>
                    <td>
                        <p>Kesici Uç</p>
                    </td>
                    <td>
                        <p><?php echo $sonuc["kesici_uclar_ad"]; ?></p>
                    </td>
                </tr>
                <tr>
                    <?php $sorgu = 'SELECT * FROM urun_yedek WHERE urun_ref = "' . $urun_no . '"';
                    $gelenUrunYedek = $db->select($sorgu);
                    while ($sonucUrunYedek = mysqli_fetch_assoc($gelenUrunYedek)) {
                        $sorgu = 'SELECT * FROM yedek_parca WHERE parca_no = "' . $sonucUrunYedek["parca_ref"] . '"';
                        $gelenParcaAd = $db->select($sorgu);
                        $sonucParcaAd = mysqli_fetch_assoc($gelenParcaAd); ?>
                <tr>
                    <?php if ($user) { ?>
                        <td>
                            <?php if ($_GET["delete"] == $sonucParcaAd["parca_no"]) { ?>
                                <form id="yedekParcaDeleteForm" action="Edit/yedekParcaDuzenle.php?type=yedekDelete&parca_ref=<?php echo $sonucParcaAd["parca_no"]; ?>" method="POST">
                                    <input type="hidden" name="urun_no" value=<?php echo $urun_no; ?>>
                                </form>
                                <script>
                                    isDelete();
                                </script>
                            <?php } ?>
                            <?php if ($user["permission_delete"] == "1") { ?>
                                <a href="/eCatalog/urun.php?delete=<?php echo $sonucParcaAd["parca_no"]; ?>&urun_no=<?php echo $urun_no; ?>"><i class="fas fa-trash-alt"></i></a>
                            <?php } ?>
                            <a href="/eCatalog/urun.php?edit=<?php echo $sonucParcaAd["parca_adi"]; ?>&urun_no=<?php echo $urun_no; ?>"><i class="fas fa-edit"></i></a>
                        </td>
                    <?php } ?>
                    <?php if ($_GET["edit"] == $sonucParcaAd["parca_adi"] && $user) { ?>
                        <!-- Yedek Parça Düzenle -->
                        <form name="yedekParcaEditForm" action="Edit/yedekParcaDuzenle.php?type=yedekDuzenle" method="POST">
                        <?php } ?>
                        <td>
                            <?php
                            if ($_GET["edit"] == $sonucParcaAd["parca_adi"] && $user) {
                                $sorgu = 'SELECT * FROM yedekparca_images WHERE parca_ref = "' . $sonucUrunYedek["parca_ref"] . '"';
                                $gelenImage = $db->select($sorgu); ?>
                                <select name="image_ref" id="yummy">
                                    <?php // yedek parçaların fotoğrafını çekiyoruz
                                    while ($sonucImage = mysqli_fetch_assoc($gelenImage)) { ?>
                                        <option <?php if ($sonucUrunYedek["image_ref"] == $sonucImage["image_no"]) {
                                                    echo "selected";
                                                } ?> value="<?php echo $sonucImage["image_no"]; ?>" data-image="<?php echo $sonucImage["image_path"]; ?>"></option>
                                    <?php } ?>

                                </select>
                            <?php } else {
                                $sorgu = 'SELECT image_path FROM yedekparca_images WHERE image_no = "' . $sonucUrunYedek["image_ref"] . '"';
                                $gelenImage = $db->select($sorgu);
                                $sonucImage = mysqli_fetch_assoc($gelenImage); ?>
                                <div><img width="50px" height="50px" class="imgcat" src="<?php echo $sonucImage["image_path"]; ?>"></div>
                            <?php } ?>
                        </td>
                        <td>
                            <p><?php echo $sonucParcaAd["parca_adi"]; ?></p>
                        </td>
                        <td>
                            <?php if ($_GET["edit"] == $sonucParcaAd["parca_adi"] && $user) { ?>
                                <p><input style="text-align: center" width="10px" type="text" name="parca_Tisim" value="<?php echo $sonucUrunYedek["parca_Tisim"]; ?>" placeholder="Parça Teknik İsim"></p>
                            <?php } else { ?>
                                <p><?php echo $sonucUrunYedek["parca_Tisim"]; ?></p>
                            <?php } ?>
                        </td>
                        <?php if ($_GET["edit"] == $sonucParcaAd["parca_adi"] && $user) { ?>
                            <input type="hidden" name="urun_no" value=<?php echo $urun_no; ?>>
                            <input type="hidden" name="parca_ref" value=<?php echo $sonucParcaAd["parca_no"]; ?>>
                            <td style="border: 0px">
                                <input type="submit" name="buton" value="Kaydet">
                            </td>
                        <?php } ?>
                        </form>
                </tr>
                <tr>
                    <?php }
                    if ($user) {
                        if ($_GET["type"] == "yeni") { ?>
                        <form name="yedekParcaForm" action="Add/yedekParcaEkle.php?type=plus" method="POST">
                            <td></td>
                            <td></td>
                            <td>
                                <select name="parca_ref">
                                    <option value="">--Lütfen Birini Seçiniz--</option>
                                    <?php // yedek parçaların ismini listbox a çekiyoruz
                                    $sorgu = 'SELECT * FROM yedek_parca ORDER BY parca_adi';
                                    $gelen = $db->select($sorgu);
                                    while ($sonuc = mysqli_fetch_assoc($gelen)) {
                                        $sorgu = 'SELECT * FROM urun_yedek WHERE urun_ref = "' . $urun_no . '" AND parca_ref = "' . $sonuc["parca_no"] . '"';
                                        $gelenKontrol = $db->select($sorgu);
                                        if (!$sonucKontrol = mysqli_fetch_assoc($gelenKontrol)) { ?>
                                            <option value="<?php echo $sonuc["parca_no"]; ?>"><?php echo $sonuc["parca_adi"]; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </td>
                            <td>
                                <p><input width="10px" type="text" name="parca_Tisim" placeholder="Parça Teknik İsim"></p>
                            </td>
                            <input width="10px" type="hidden" name="urun_no" value=<?php echo $urun_no; ?>>
                            <td style="border: 0px">
                                <p><input width="10px" type="submit" id="butonScript" name="buton" value="Parça Ekle"></p>
                            </td>

                        </form>
                        <td style="border: 0px">
                            <p><button onclick="isApply()">Hepsine Uygula</button></p>
                        </td>
                <?php }

                        if ($_GET["error"] == "bos") {
                            echo '<div class="alert alert-danger" role="alert">Yedek parça bilgileri boş bırakılamaz.</div>';
                        } else if ($_GET["error"] == "ayni") {
                            echo '<div class="alert alert-danger" role="alert">Bu yedek parça önceden eklenmiş.</div>';
                        } else if ($_GET["error"] == "hepsine") {
                            echo '<div class="alert alert-success" role="alert">Tüm ürünlere başarıyla uygulandı.</div>';
                        } else if ($_GET["error"] == "eklendi") {
                            echo '<div class="alert alert-success" role="alert">Yeni yedek parça kategorisi başarıyla oluşturuldu.</div>';
                        } else if ($_GET["error"] == "dosyaHata") {
                            echo '<div class="alert alert-success" role="alert">Dosya yüklenirken bir hata gerçekleşti.</div>';
                        }
                    } ?>

                </tr>
            </tbody>
        </table>
    </div>



    <?php if ($user) { ?>
        <br><br><br>
        <div>
            <table cellspacing="0" rules="all" id="content_gvwSpareParts" style="border-width:0px;width:30%;border-collapse:collapse;text-align:center;margin:0 auto;">
                <tbody>
                    <!-- <p>Info : Aşağıdaki tablodan sadece kategori oluşturabilirsiniz.</p> -->
                    <tr style="color:white;background-color:#B8274C;">
                        <th scope="col">
                            <div class="inf">Yedek Parça Kategorisi <i class="fas fa-info-circle"></i><a class="infos">Bu tabloda sadece kategori ekleyebilirsiniz.</div>
                        </th>
                        <th scope="col">Düzenle</th>
                        <th></th>
                    </tr>
                    <tr style="background-color:#FFE6CB;">
                        <form action="Add/yedekParcaEkle.php?type=buton" method="POST">
                            <td style="padding: 10px">
                                <input type="text" name="parca_adi" placeholder="Kategori İsmi">
                            </td>
                            <input type="hidden" name="urun_no" value=<?php echo $urun_no; ?>>
                            <td style="padding: 10px">
                                <input type="submit" name="buton" value="Kategori Ekle">
                            </td>
                        </form>
                    </tr>
                </tbody>
            </table>
        </div>
        <br><br><br>
        <div>
            <table cellspacing="0" rules="all" id="content_gvwSpareParts" style="border-width:0px;width:30%;border-collapse:collapse;text-align:center;margin:0 auto;">
                <tbody>
                    <!-- <p>Info : Aşağıdaki tablodan sadece kategori oluşturabilirsiniz.</p> -->
                    <tr style="color:white;background-color:#B8274C;">
                        <th scope="col">
                            <div class="inf">Yedek Parça Kategorisi <i class="fas fa-info-circle"></i><a class="infos">Bu tabloda sadece fotoğraf ekleyebilirsiniz.</div>
                        </th>
                        <th scope="col">Fotoğraf</th>
                        <th scope="col">Düzenle</th>
                    </tr>
                    <tr style="background-color:#FFE6CB;">
                        <form action="Add/yedekParcaEkle.php?type=buton" method="POST" enctype="multipart/form-data">
                            <td style="padding: 10px">
                                <select name="parca_ref">
                                    <option value="">--Lütfen Birini Seçiniz--</option>
                                    <?php // yedek parçaların ismini listbox a çekiyoruz
                                    $sorgu = 'SELECT * FROM yedek_parca ORDER BY parca_adi';
                                    $gelen = $db->select($sorgu);
                                    while ($sonuc = mysqli_fetch_assoc($gelen)) { ?>
                                        <option value="<?php echo $sonuc["parca_no"]; ?>"><?php echo $sonuc["parca_adi"]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td style="padding: 10px">
                                <input type="file" name="dosya" accept=".jpg,.jpeg,.png,.bmp,.gif" />
                            </td>
                            <input type="hidden" name="urun_no" value=<?php echo $urun_no; ?>>
                            <td style="padding: 10px">
                                <input type="submit" name="buton" value="Fotoğraf Ekle">
                            </td>
                        </form>
                    </tr>
                </tbody>
            </table>
        </div>
        <br><br><br>
    <?php } ?>
    </div>
</body>

<style>
    .urunlerTablo {
        width: 50%;
        margin: 0 auto;
    }

    .resimler .ikiResim {
        margin-left: 30px;
    }


    .tablos {
        width: 50%;
        margin: 0 auto;
    }

    @media only screen and (max-width: 400px) {

        .resimler .birResim img {
            width: 100%;
        }

        .resimler .ikiResim img {
            width: 50%;
        }

        .resimler .birResim {
            margin-left: 0px;
            width: 100%;
        }

        .resimler .ikiResim {
            margin-left: 0px;
            width: 100%;
        }

        .tablos {
            width: 100%;
        }

        .tablos .urunTablo {
            margin: 0;
            padding: 0;
            width: 100%;
            overflow-x: auto;
        }

        .tablos .spareTablo {
            margin: 0;
            padding: 0;
            width: 100%;
            overflow-x: auto;
        }
    }
</style>

</html>