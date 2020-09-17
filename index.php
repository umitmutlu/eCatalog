<?php
session_start();
error_reporting(~E_DEPRECATED & ~E_NOTICE);
$path = $_SERVER['DOCUMENT_ROOT'];
$user = $_SESSION["login_userAKKO"];
include("config.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <script src="https://kit.fontawesome.com/1e0e0aaef1.js" crossorigin="anonymous"></script>
  <title>Ana Sayfa</title>

</head>
<?php include("header.php"); ?>

<body style="background-color: #d9d9d9">
  <div class="abd">
    <?php if ($user) { ?>
      <div class="containersu">
        <form class="table" action="excel.php" method="post" name="excel.php" style="display:flex;justify-content:flex-end;float:right;">
          <a><button style="text-align:right;" type="submit" name="excel_cikti" class="btn btn-success" value="Excel Çıktı Al">Excel Çıktısı İndir</button></a>
        </form>
      </div>
    <?php } ?>
    <div class="container">
      <!--    <div class="content"> -->

      <?php include './anaKategori.php'; ?>


      <?php
      if ($_GET["type"]  == "altKategori") { ?>
        <div class="sub-categories">
          <?php include './altKategori.php'; ?>
        </div>
      <?php } else if ($_GET["type"]  == "altbaslikKategori") { ?>

        <div class="sub-categories">
          <?php include './altbaslikKategori.php'; ?>
        </div>
      <?php } else if ($_GET["type"]  == "kesiciUclar") { ?>

        <div class="sub-categories">
          <?php include './kesiciUclar.php'; ?>
        </div>
      <?php } ?>


      <?php if ($_GET["type"] != "altKategori" && $_GET["type"] != "altbaslikKategori" && $_GET["type"] != "kesiciUclar") { ?>
        <div class="divider">
          <hr />
        </div>
        <div class="search-form">
          <?php include './search.php';  ?>
        </div>
      <?php } ?>
      <!--  </div>-->
    </div>
  </div>
  <div>
    <p class="echteFooter-right" style="text-align:center;margin-bottom:100px;">Copyright &#169; AKKO Metal Workings 2020</p>
  </div>
</body>

</html>