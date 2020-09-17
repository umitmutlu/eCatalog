<?php
$alt_ref = $_GET["alt_ref"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/1e0e0aaef1.js" crossorigin="anonymous"></script>
    <title>Alt Başlık Kategoriler</title> -->
</head>

<body>
    <section class="maincontent clearer">
        <div class="centralcontent flexwrapper">
            <div id="topDivTitle" style="margin-top: 2%;">
                <?php $sorgu = 'SELECT * FROM altkatalog WHERE alt_no = "' . $alt_ref . '"';
                $gelen = $db->select($sorgu);
                $sonuc = mysqli_fetch_assoc($gelen); ?>

                <h3><span id="lblTitle" style="color:#F7941D;">Açı / <?php echo $sonuc["alt_ad"]; ?></span></h3>
                <hr>
            </div>
            <div class="degreesProducts" style="margin-top: 2%;display:flex;flex-wrap:wrap;">
                <?php $sorgu = 'SELECT * FROM altbaslik WHERE alt_ref = "' . $alt_ref . '"';
                $gelen = $db->select($sorgu);
                while ($sonuc = mysqli_fetch_assoc($gelen)) { ?>
                    <div class="subCats" style="max-width: 120px; min-width: 120px;max-height: 110px; min-height: 110px;">
                        <?php if ($user) { ?>
                            <div style="text-align-last:right;">
                                <?php if ($user["permission_delete"] == "1") { ?>
                                    <a class="duzenleIconlar" data-toggle="modal" data-target="#exampleModal" data-delete-data="altBaslik=<?php echo $sonuc["altBaslik_no"]; ?>&alt_ref=<?php echo $alt_ref; ?>">
                                        <img src="./dosyalar/icons/trash-alt-solid.svg" style="color:rgb(25, 45, 161);max-width:14px;max-height:16px;width:35px;">
                                    </a>
                                <?php } ?>
                                <a class="duzenleIconlar" href="index.php?type=altbaslikKategori&alt_ref=<?php echo  $sonuc["alt_ref"]; ?>&edit=<?php echo $sonuc["altBaslik_no"]; ?>">
                                    <img src="./dosyalar/icons/edit-solid.svg" style="color:rgb(25, 45, 161);max-width:14px;max-height:16px;width:35px;">
                                </a>
                            </div>
                        <?php } ?>
                        <div id="subCatsImg" style="border:solid;border-color:orange;position:relative;">
                            <a href="index.php?type=kesiciUclar&altbaslik_no=<?php echo $sonuc["altBaslik_no"]; ?>" class="stretched-link"></a>
                            <div class="imgOfSubCats"> <a href="index.php?type=kesiciUclar&altbaslik_no=<?php echo $sonuc["altBaslik_no"]; ?>">
                                    <img src="<?php echo $sonuc["altBaslik_imgTeknik"]; ?>" alt="" width="100%" style="max-width:80px;min-width:80px;max-height:80px;min-height:80px;">
                                </a> </div>
                            <div class="nameOfSubCat">
                                <a style="color:inherit;text-decoration:none;color:blue;" href="index.php?type=kesiciUclar&altbaslik_no=<?php echo $sonuc["altBaslik_no"]; ?>">
                                    <?php echo $sonuc["altBaslik_ad"]; ?>
                                </a> </div>
                        </div>
                    </div>
                    <?php if ($user) {
                        if ($_GET["edit"] == $sonuc["altBaslik_no"]) { ?>
                            <div style="border-color:#F7941D;">
                                <div class="login">
                                    <div class="login-triangle"></div>
                                    <h2 class="login-header">Düzenle</h2>
                                    <form action="Edit/Duzenle.php?edit=altbaslikKategori" method="POST" enctype="multipart/form-data" class="login-container">
                                        <p><input type="text" name="altBaslik_ad" placeholder="Yeni Açı Adı" value="<?php echo $sonuc["altBaslik_ad"]; ?>"></p>
                                        <input type="hidden" name="altBaslik_no" value="<?php echo $sonuc["altBaslik_no"]; ?>">
                                        <input type="hidden" name="alt_ref" value="<?php echo $_GET["alt_ref"]; ?>">
                                        <p><input type="submit" value="Kaydet"></p>
                                        <p class="inf">Resimler <i class="fas fa-info-circle"></i><a class="infos" style="color:white;">Açı resimleri ürünler sayfasında değiştirilmektedir.</p>
                                    </form>
                                </div>
                            </div>
                        <?php }
                    }
                }
                if ($user) {
                    if ($_GET["ekle"] == 'yeni') { ?>
                        <div id="content_dlSubApplications_subAppsDiv_0">
                            <div class="login">
                                <div class="login-triangle"></div>
                                <h2 class="login-header">Yeni Kayıt</h2>
                                <form action="Add/urunEkle.php?type=plus" method="POST" enctype="multipart/form-data" class="login-container">
                                    <p><input type="text" name="altbaslik_ad" placeholder="Açı Adı"></p>
                                    <p><input type="file" name="dosya2" accept=".jpg,.jpeg,.png,.bmp,.gif"></p>
                                    <p><input type="file" name="dosya1" accept=".jpg,.jpeg,.png,.bmp,.gif"></p>
                                    <input type="hidden" name="alt_ref" value="<?php echo $alt_ref; ?>">
                                    <p><input type="submit" name="buton" value="Açı Ekle"></p>
                                </form>
                            </div>
                        </div>
                    <?php } else {
                        $ana_ref = $_GET["ana_ref"]; ?>
                        <div style="padding-top:50px;">
                            <a href="/eCatalog/index.php?type=altbaslikKategori&ekle=yeni&alt_ref=<?php echo $alt_ref; ?>" style="align-self:center;">
                                <img width="226px" height="115px" class="imgcat" src="./dosyalar/plus.svg">
                            </a>
                        </div>
                <?php }
                } ?>
            </div>
        </div>
    </section>
</body>

</html>