<!DOCTYPE html>
<html lang="en">
<!-- transition: transform .1s ease; -->

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://kit.fontawesome.com/1e0e0aaef1.js" crossorigin="anonymous"></script>
  <title>Alt Kategoriler</title> 
  <link rel="stylesheet" type="text/css" href="/eCatalog/layout.css">
  <link rel="stylesheet" type="text/css" href="/eCatalog/allcss.css">-->
</head>

<body style="background-color: #d9d9d9">
  <section class=" maincontent clearer">
    <div class="centralcontent flexwrapper">
      <div id="topDivTitle" style="margin-top: 2%;">
        <?php $ana_ref = $_GET["ana_ref"];
        $sorgu = 'SELECT * FROM anakatalog WHERE ana_no = "' . $ana_ref . '"';
        $gelen = $db->select($sorgu);
        $sonuc = mysqli_fetch_assoc($gelen); ?>
        <h3><span id="lblTitle" style="color:#F7941D;">Alt Kategori / <?php echo $sonuc["ana_ad"]; ?></span></h3>
        <hr>
      </div>
      <div class="altProducts" style="width:100%;display:flex;flex-wrap: wrap;">
        <!-- *//*/*/*/*/*/**/*/* BURYA BAKILACAK ROW OLUYOR COL DEGHIL */*/*/*/*/*/-->
        <?php $sorgu = 'SELECT * FROM altkatalog WHERE ana_ref = "' . $ana_ref . '"';
        $gelen = $db->select($sorgu);
        while ($sonuc = mysqli_fetch_assoc($gelen)) { ?>
          <div class="subCats" style="max-width: 120px; min-width: 120px;max-height: 110px; min-height: 110px;">
            <?php if ($user) { ?>
              <div style="text-align-last:right;">
                <?php if ($user["permission_delete"] == "1") { ?>
                  <a class="duzenleIconlar" data-toggle="modal" data-target="#exampleModal" data-delete-data="altKategori=<?php echo $sonuc["alt_no"]; ?>&ana_ref=<?php echo $ana_ref; ?>">
                    <img src="./dosyalar/icons/trash-alt-solid.svg" style="color:rgb(25, 45, 161);max-width:14px;max-height:16px;width:35px;">
                  </a>
                <?php } ?>
                <a class="duzenleIconlar" href="index.php?type=altKategori&ana_ref=<?php echo  $sonuc["ana_ref"]; ?>&edit=<?php echo $sonuc["alt_no"]; ?>">
                  <img src="./dosyalar/icons/edit-solid.svg" style="color:rgb(25, 45, 161);max-width:14px;max-height:16px;width:35px;">
                </a>
              </div>
            <?php } ?>

            <div id="resimProducts" style="border:solid;border-color:orange;position:relative;">
              <a href="index.php?type=altbaslikKategori&alt_ref=<?php echo $sonuc["alt_no"]; ?>" class="stretched-link"></a>
              <a href="index.php?type=altbaslikKategori&alt_ref=<?php echo $sonuc["alt_no"]; ?>">
                <p>
                  <p>
                    <div class="resimOfSub">
                      <a href="index.php?type=altbaslikKategori&alt_ref=<?php echo $sonuc["alt_no"]; ?>">
                        <img src="<?php echo $sonuc["altkatalog_img"]; ?>" alt="" width="100%" style="max-width:80px;min-width:80px;max-height:80px;min-height:80px;"></a>
                    </div>
                    <div class="nameOfSub">
                      <a style="color:inherit;text-decoration:none;color:blue;" href="index.php?type=altbaslikKategori&alt_ref=<?php echo $sonuc["alt_no"]; ?>">
                        <?php echo $sonuc["alt_ad"]; ?>
                      </a>
                    </div>
            </div>
          </div>

          <?php if ($user) {
            if ($_GET["edit"] == $sonuc["alt_no"]) { ?>
              <div style="border-color:#F7941D;">
                <div class="login">
                  <div class="login-triangle"></div>
                  <h2 class="login-header">Düzenle</h2>
                  <form action="Edit/Duzenle.php?edit=altKategori" method="POST" enctype="multipart/form-data" class="login-container">
                    <p><input type="text" name="alt_ad" placeholder="Yeni Alt Kategori Adı" value="<?php echo $sonuc["alt_ad"]; ?>"></p>
                    <p><input type="file" name="dosya" accept=".jpg,.jpeg,.png,.bmp,.gif"></p>
                    <input type="hidden" name="alt_no" value="<?php echo $sonuc["alt_no"]; ?>">
                    <input type="hidden" name="ana_ref" value="<?php echo $_GET["ana_ref"]; ?>">
                    <p><input type="submit" value="Kaydet"></p>
                  </form>
                </div>
              </div>
            <?php }
          }
        }
        if ($user) {
          if ($_GET["ekle"] == 'yeni') { ?>
            <div id="content_dlSubApplications_subAppsDiv_0" style="border-color:#F7941D;">
              <div class="login">
                <div class="login-triangle"></div>
                <h2 class="login-header">Yeni Kayıt</h2>
                <form action="Add/urunEkle.php?type=plus" method="POST" enctype="multipart/form-data" class="login-container">
                  <p><input type="text" name="alt_ad" placeholder="Sistem Adı"></p>
                  <p><input type="file" name="dosya" accept=".jpg,.jpeg,.png,.bmp,.gif"></p>
                  <input type="hidden" name="ana_ref" value="<?php echo $ana_ref; ?>">
                  <input type="hidden" name="hangisi" value="2">
                  <p><input type="submit" value="Ekle"></p>
                </form>
              </div>
            </div>
          <?php } else {
            $ana_ref = $_GET["ana_ref"]; ?>
            <div style="padding-top:50px;">
              <a href="/eCatalog/index.php?type=altKategori&ekle=yeni&ana_ref=<?php echo $ana_ref; ?>">
                <img width="226px" height="115px" class="imgcat" src="./dosyalar/plus.svg">
              </a>
            </div>
        <?php }
        } ?>
        <br><br><br><br>
      </div>
  </section>
</body>

</html>