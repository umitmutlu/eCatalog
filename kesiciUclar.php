<?php
$baslik_ref = $_GET["altbaslik_no"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Açılar</title>
</head>
<!-- mobilede header açılıp kapanmıyor!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! ve urunler.phpde de ve urun.phpde de -->

<body>
  <section class="maincontent clearer">
    <div class="centralcontent flexwrapper">
      <div id="topDivTitle" style="margin-top: 2%;">
        <?php
        $sorgu = 'SELECT * FROM altbaslik WHERE altBaslik_no = "' . $baslik_ref . '"';
        $gelen = $db->select($sorgu);
        $sonuc = mysqli_fetch_assoc($gelen); ?>
        <h3><span id="lblTitle" style="color:#F7941D;">Kesici Uç / <?php echo $sonuc["altBaslik_ad"]; ?></span></h3>
        <hr>
      </div>
      <div class="degreesProducts" style="margin-top: 2%;display:flex;flex-wrap:wrap;">
        <?php
        $sorgu = 'SELECT * FROM kesici_uclar WHERE baslik_ref = "' . $baslik_ref . '"';
        $gelen = $db->select($sorgu);
        while ($sonuc = mysqli_fetch_assoc($gelen)) {
        ?>

          <div class="subCats" style="max-width: 120px; min-width: 120px;max-height: 110px; min-height: 110px;">
            <?php if ($user) { ?>
              <div style="text-align-last:right;">
                <?php if ($user["permission_delete"] == "1") { ?>
                  <a class="duzenleIconlar" data-toggle="modal" data-target="#exampleModal" data-delete-data="kesiciUclar=<?php echo $sonuc["kesici_uclar_no"]; ?>&baslik_ref=<?php echo $baslik_ref; ?>">
                    <img src="./dosyalar/icons/trash-alt-solid.svg" style="color:rgb(25, 45, 161);max-width:14px;max-height:16px;width:35px;">
                  </a>
                <?php } ?>
                <a class="duzenleIconlar" href="index.php?type=kesiciUclar&altbaslik_no=<?php echo  $sonuc["baslik_ref"]; ?>&edit=<?php echo $sonuc["kesici_uclar_no"]; ?>">
                  <img src="./dosyalar/icons/edit-solid.svg" style="color:rgb(25, 45, 161);max-width:14px;max-height:16px;width:35px;">
                </a>
              </div>
            <?php } ?>
            <div id="kesiciUclarImg" style="border:solid;border-color:orange;position:relative;">
              <a style="color:inherit;text-decoration:none;color:blue;" href="urunler.php?altbaslik_no=<?php echo $baslik_ref; ?>&kesici_uclar_no=<?php echo $sonuc["kesici_uclar_no"]; ?>" class="stretched-link"></a>
              <div class="imgOfKesici">
                <a href="urunler.php?altbaslik_no=<?php echo $baslik_ref; ?>&kesici_uclar_no=<?php echo $sonuc["kesici_uclar_no"]; ?>"><img src="<?php echo $sonuc["kesici_uclar_imgPath"]; ?>" alt="" width="100%" style="max-width:80px;min-width:80px;max-height:80px;min-height:80px;">
                </a>
              </div>
              <div class="nameOfKesici">
                <a style="color:inherit;text-decoration:none;color:blue;" href="urunler.php?altbaslik_no=<?php echo $baslik_ref; ?>&kesici_uclar_no=<?php echo $sonuc["kesici_uclar_no"]; ?>"><?php echo $sonuc["kesici_uclar_ad"]; ?>
                </a>
              </div>
            </div>
          </div>
          <?php if ($user) {
            if ($_GET["edit"] == $sonuc["kesici_uclar_no"]) { ?>
              <div style="border-color:#F7941D;">
                <div class="login">
                  <div class="login-triangle"></div>
                  <h2 class="login-header">Düzenle</h2>
                  <form action="Edit/Duzenle.php?edit=kesiciUclar" method="POST" enctype="multipart/form-data" class="login-container">
                    <p><input type="text" name="kesici_uclar_ad" placeholder="Yeni Kesici Uç Adı" value="<?php echo $sonuc["kesici_uclar_ad"]; ?>"></p>
                    <p><input type="file" name="dosya" accept=".jpg,.jpeg,.png,.bmp,.gif"></p>
                    <input type="hidden" name="kesici_uclar_no" value="<?php echo $sonuc["kesici_uclar_no"]; ?>">
                    <input type="hidden" name="baslik_ref" value="<?php echo $_GET["altbaslik_no"]; ?>">
                    <p><input type="submit" value="Kaydet"></p>
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
                <form action="Add/kesiciUclarEkle.php?type=plus" method="POST" enctype="multipart/form-data" class="login-container">
                  <p><input type="text" name="kesici_uclar_ad" placeholder="Kesici Ucun Adı"></p>
                  <p><input type="file" name="dosya" accept=".jpg,.jpeg,.png,.bmp,.gif"></p>
                  <input type="hidden" name="baslik_ref" value="<?php echo $baslik_ref; ?>">
                  <p><input type="submit" name="buton" value="Kesici Uç Ekle"></p>
                </form>
              </div>
            </div>
          <?php } else {
            $ana_ref = $_GET["ana_ref"]; ?>
            <div style="padding-top:50px;">
              <a href="/eCatalog/index.php?ekle=yeni&type=kesiciUclar&altbaslik_no=<?php echo $baslik_ref; ?>" style="align-self:center;">
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