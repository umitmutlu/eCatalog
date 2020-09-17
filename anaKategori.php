<?php
$cihaz = $_SERVER['HTTP_USER_AGENT'];
$iphone = strpos($cihaz, "iPhone");
$android = strpos($cihaz, "Android");
$ipod = strpos($cihaz, "iPod");
/*if ($iphone == true || $android == true || $ipod == true) { 

echo 'MOBİLDE GÖRÜNTÜLENECEK KODLAR';

 }else{ 

echo 'BİLGİSAYARDA GÖRÜNTÜLENECEK KODLAR';

 }*/ ?>

<?php
include_once("config.php");
$db = new Db();
$user = $_SESSION["login_userAKKO"];
if ($user) {
  $username = $user["username"];
  $sorgu = "SELECT permission_delete FROM user WHERE username='$username'";
  $gelen = $db->select($sorgu);
  $sonuc = mysqli_fetch_assoc($gelen);
  $_SESSION["login_userAKKO"]["permission_delete"] = $sonuc["permission_delete"];
}
$sorgu = 'SELECT * FROM anakatalog';
$gelen = $db->select($sorgu); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- <link rel="stylesheet" type="text/css" href="/eCatalog/allcss.css"> -->
  <!-- <link rel="stylesheet" type="text/css" href="/eCatalog/layout.css"> -->
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

    function isDelete(deleteVariable) {
      var splitVariable = deleteVariable.split("=");
      console.log(splitVariable[0]);
      console.log(splitVariable[1]);
      console.log(splitVariable[2]);
      console.log("anaref = " + splitVariable[3]);
      if (splitVariable[0] == "anaKategori") {
        window.location = "/eCatalog/Edit/Duzenle.php?delete=anaKategori&value=" + splitVariable[1];
      } else if (splitVariable[0] == "altKategori") {
        window.location = "/eCatalog/Edit/Duzenle.php?delete=altKategori&value=" + splitVariable[1] + "=" + splitVariable[2]; // = "/eCatalog/Edit/Duzenle.php?delete=altKategori&value=X&ana_ref=X"
      } else if (splitVariable[0] == "altBaslik") {
        window.location = "/eCatalog/Edit/Duzenle.php?delete=altBaslik&value=" + splitVariable[1] + "=" + splitVariable[2]; // = "/eCatalog/Edit/Duzenle.php?delete=altBaslik&value=X&alt_ref=X"
      } else if (splitVariable[0] == "kesiciUclar") {
        window.location = "/eCatalog/Edit/Duzenle.php?delete=kesiciUclar&value=" + splitVariable[1] + "=" + splitVariable[2]; // = "/eCatalog/Edit/Duzenle.php?delete=kesiciUclar&value=X&baslik_ref=X"
      }
    }
  </script>
  <?php
  if ($user["permission_delete"] == "1") {
    // BEGIN - Silme işlemlerinde, şifre kontrolü
    if (@$_POST["password_check"] == "delete") {
      $username = $user["username"];
      $password = $_POST["password"];
      if ($_POST["type"]) {
        $type = $_POST["type"];
        $refs = $_POST["refs"];
      }
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
        if ($type == "altKategori") {
          header("location: /eCatalog/index.php?error=sifreYanlis&type=$type&ana_ref=$refs");
          exit;
        } else if ($type == "altbaslikKategori") {
          header("location: /eCatalog/index.php?error=sifreYanlis&type=$type&alt_ref=$refs");
          exit;
        } else if ($type == "kesiciUclar") {
          header("location: /eCatalog/index.php?error=sifreYanlis&type=$type&altbaslik_no=$refs");
          exit;
        } else {
          header('location: /eCatalog/index.php?error=sifreYanlis');
          exit;
        }
      }
    }
    // END - Silme işlemlerinde, şifre kontrolü 
  }
  ?>
</head>

<body>
  <div class="container" style="padding:0;padding-bottom:30px;">
    <?php include("errors.php"); ?>
    <div class="mainCatImgs row" style="width:100%;margin-left:0;">
      <?php while ($sonuc = mysqli_fetch_assoc($gelen)) { ?>
        <!-- <a href="index.php?type=altKategori&ana_ref=<?php /* echo  $sonuc["ana_no"]; */ ?>"> -->
        <div <?php if ($iphone == true || $android == true || $ipod == true) { ?> class="mobileProducts" <?php } else { ?> class="col-2" style="text-align:center;" <?php } ?>>
          <?php if ($user) { ?>
            <div class="col">
              <?php if ($user["permission_delete"] == "1") { ?>
                <a class="duzenleIconlar" data-toggle="modal" data-target="#exampleModal" data-delete-data="anaKategori=<?php echo $sonuc["ana_no"]; ?>">
                  <img src="./dosyalar/icons/trash-alt-solid.svg" style="max-width:14px;max-height:16px;width:35px;">
                </a>
              <?php } ?>
              <a class="duzenleIconlar" href="/eCatalog?edit=<?php echo $sonuc["ana_ad"]; ?>">
                <img src="./dosyalar/icons/edit-solid.svg" style="max-width:14px;max-height:16px;width:35px;">
              </a>
            </div>
          <?php } ?>
          <a class="duzenleIconlar" href="index.php?type=altKategori&ana_ref=<?php echo  $sonuc["ana_no"]; ?>">
            <img width="125px" height="125px" src="<?php echo $sonuc["ana_imgPath"]; ?>">
          </a>
          <div class="nameOfProducts" style="text-align:center;">
            <a style="text-decoration: none;" href="index.php?type=altKategori&ana_ref=<?php echo  $sonuc["ana_no"]; ?>">
              <?php echo trim($sonuc["ana_ad"]); ?></a>

          </div>
        </div>
        <?php if ($user) {
          if ($_GET["edit"] == $sonuc["ana_ad"]) { ?>
            <div style="border-color:#F7941D;">
              <div class="login">
                <div class="login-triangle"></div>

                <h2 class="login-header">Düzenle</h2>

                <form action="Edit/Duzenle.php?edit=anaKategori" method="POST" enctype="multipart/form-data" class="login-container">
                  <p><input type="text" name="ana_ad" placeholder="Yeni Ana Kategori Adı" value="<?php echo $sonuc["ana_ad"]; ?>"></p>
                  <p><input type="file" name="dosya" accept=".jpg,.jpeg,.png,.bmp,.gif"></p>
                  <input type="hidden" name="ana_no" value="<?php echo $sonuc["ana_no"]; ?>">
                  <p><input type="submit" value="Kaydet"></p>
                </form>
              </div>
            </div>
          <?php }
        }
      }
      if ($user) {
        if ($_GET["type"] == 'anaYeni') { ?>
          <div style="border-color:#F7941D;">
            <!-- <i class="fas fa-info-circle"></i><a class="infos">Bu tabloda sadece fotoğraf ekleyebilirsiniz. -->
            <div class="login">
              <div class="login-triangle"></div>
              <h2 class="login-header">Yeni Kayıt</h2>
              <form action="Add/urunEkle.php?type=plus" method="POST" enctype="multipart/form-data" class="login-container">
                <p><input type="text" name="ana_ad" placeholder="Ana Kategori Adı"></p>
                <p><input type="file" name="dosya" accept=".jpg,.jpeg,.png,.bmp,.gif"></p>
                <input type="hidden" name="hangisi" value="1">
                <p><input type="submit" value="Ekle"></p>
              </form>
            </div>
          </div>
        <?php } else if ($_GET["type"] != 'altKategori' && $_GET["type"] != 'altbaslikKategori' && $_GET["type"] != 'kesiciUclar') { ?>
          <div style="padding-top:50px;">
            <a href="/eCatalog?type=anaYeni" style="padding:5%;">
              <image class="ekleArti" xlink:href="./dosyalar/plus.svg" width="100" height="100" src="./dosyalar/plus.svg" />
            </a>
          </div>
      <?php }
      } ?>

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
            <div class="modal-body">
              Silmek istediğinize emin misiniz ? Buna bağlı olan bütün ürünler silinecektir !
              <form class="form-inline" action="anaKategori.php" method="POST">
                <input type="hidden" name="password_check" value="delete">
                <?php if ($_GET["type"]) {
                  $type = $_GET["type"];
                  if ($type == "altKategori") { ?>
                    <input type="hidden" name="type" value="altKategori">
                    <input type="hidden" name="refs" value="<?php echo $_GET["ana_ref"]; ?>">
                  <?php } else if ($type == "altbaslikKategori") { ?>
                    <input type="hidden" name="type" value="altbaslikKategori">
                    <input type="hidden" name="refs" value="<?php echo $_GET["alt_ref"]; ?>">
                  <?php } else if ($type == "kesiciUclar") { ?>
                    <input type="hidden" name="type" value="kesiciUclar">
                    <input type="hidden" name="refs" value="<?php echo $_GET["altbaslik_no"]; ?>">
                <?php }
                } ?>
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
  @media screen and (max-width: 450px) {
    .zahid {
      max-width: 70px;
    }
  }

  a:focus img {
    -webkit-filter: grayscale(100%);
    filter: grayscale(100%);
  }
</style>

</html>