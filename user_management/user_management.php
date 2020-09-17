<?php
include_once("../config.php");
$db = new Db();
$user = $_SESSION["login_userAKKO"];
if (!$user) {
    header("location: /eCatalog/index.php");
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
    <?php include("../header.php"); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.min.css">
    <title>Kullanıcı Yönetimi</title>
</head>

<body>
    <div class="container" style="text-align-last: center; width:65%;">
        <div class="row" style="padding-top: 50px; padding-left: 50px; padding-bottom: 50px;">
            <div class="col" style="text-align-last: left;">
                <h4><i class="fas fa-lock"></i>&nbsp;Şifre Değiştir : </h4>
            </div>
            <div class="col">
                <button type="button" class="btn btn-primary btn-circle" onclick="window.location.href='/eCatalog/user_management/password_change.php'">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
        <?php if ($user["username"] == "admin") { ?>
            <hr>
            <div class="row" style="padding-top: 50px; padding-left: 50px; padding-bottom: 50px;">
                <div class="col" style="text-align-last: left;">
                    <h4><i class="fas fa-user-edit"></i>&nbsp;Kullanıcı Yetkilerini Düzenle : </h4>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-primary btn-circle" onclick="window.location.href='/eCatalog/user_management/user_edit.php'">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
            <hr>
            <div class="row" style="padding-top: 50px; padding-left: 50px; padding-bottom: 50px;">
                <div class="col" style="text-align-last: left;">
                    <h4><i class="fas fa-user-plus"></i>&nbsp;Yeni Üye Ekle : </h4>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-primary btn-circle" onclick="window.location.href='/eCatalog/login/register.php'">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
            <hr>
            <div class="row" style="padding-top: 50px; padding-left: 50px; padding-bottom: 50px;">
                <div class="col" style="text-align-last: left;">
                    <h4><i class="fas fa-scroll"></i>&nbsp;Loglar : </h4>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-primary btn-circle" onclick="window.location.href='/eCatalog/loglar.php'">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        <?php } ?>
    </div>
    <style>
        .btn-circle {
            width: 30px;
            height: 30px;
            padding: 6px 0px;
            border-radius: 15px;
            text-align: center;
            font-size: 12px;
            line-height: 1.42857;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>