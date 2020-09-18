<?php
$path = $_SERVER['DOCUMENT_ROOT'];
@$user = $_SESSION["login_userAKKO"];
include_once($path . "/eCatalog/config.php");
$db = new Db();
if ($user["username"]) {
  $username = $user["username"];
  $sorgu = "SELECT permission_delete FROM user WHERE username='$username'";
  $gelen = $db->select($sorgu);
  $sonuc = mysqli_fetch_assoc($gelen);
  $_SESSION["login_userAKKO"]["permission_delete"] = $sonuc["permission_delete"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <meta charset="utf-8" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="/eCatalog/layout.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <script src="https://kit.fontawesome.com/1e0e0aaef1.js" crossorigin="anonymous"></script>
</head>

<body>

  <div class="back-header">
    <div class="header">
      <nav class="navbar navbar-expand-lg navbar-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#iconsToggler" aria-controls="iconsToggler" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse " id="iconsToggler">
          <a class="navbar-brand" href="/eCatalog/index.php" style="font-family: Monoton; color: white;">AKKO</a>
          <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li>
              <a href="<?php $path ?>/eCatalog/index.php">
                <i class="fas fa-home"></i>&nbsp;Ana Sayfa</a>
            </li>
            <li>
              <a href="<?php $path ?>/eCatalog/search.php">
                <i class="fas fa-search"></i>&nbsp;Yeni Arama</a>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <?php if ($user) { ?>
              <li>
                <a href="<?php $path ?>/eCatalog/Add/urunEkle.php">
                  <i class="fas fa-plus-square"></i>&nbsp;Özellik Ekle
                </a>
              </li>
            <?php } ?>
            <?php if (!$user) { ?>
              <li>
                <a href="<?php $path ?>/eCatalog/login/login.php">
                  <i class="fas fa-sign-in-alt"></i>&nbsp;Giriş Yap
                </a>
              </li>
            <?php } ?>
            <?php if ($user["username"] == "admin") { ?>
              <li>
                <a href="/eCatalog/user_management/user_management.php"><i class="fas fa-users-cog" aria-hidden="true"></i>&nbsp;Admin Paneli</a>
              </li>
            <?php } else if ($user["username"] != "admin" && $user) { ?>
              <li>
                <a href="/eCatalog/user_management/user_management.php"><i class="fas fa-user-cog" aria-hidden="true"></i>&nbsp;Kullanıcı Ayarları</a>
              </li>
            <?php } ?>
            <?php
            if ($user) { ?>
              <li>
                <a href="<?php $path ?>/eCatalog/login/logout.php"><i class="fas fa-sign-out-alt"></i>&nbsp;Çıkış Yap<?php echo ' [' . $user["username"] . ']' ?> </a>
              </li>
            <?php } ?>

            <!--  <li>
              <a href="#"><i class="icon-tr-flag"></i>&nbsp;TR</a>
            </li>
            <li>
              <a href="#"><i class="icon-eng-flag"></i>&nbsp;ENG</a>
            </li> -->
          </ul>
        </div>
      </nav>
    </div>
  </div>
</body>

</html>