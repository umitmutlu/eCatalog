<?php
if ($_POST) {
    $response = $_POST["g-recaptcha-response"];
    $secret = "6Ld6_uQUAAAAAN6Y98qI_bx7qmiTiDAfQe5_jmP3";
    $remoteip = $_SERVER["REMOTE_ADDR"];
    $captcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip");

    $result = json_decode($captcha);
    if ($result->success == 1) {
        echo "Güvenlik işlemini başarıyla tamamladınız";
    } else {
        echo "Lütfen güvenlik işlemini tamamlayınız.";
    }
}
?>

<?php

/**
 * Veritabanı bağlantı bilgilerimizin olduğu sayfayı dahil ediyoruz.
 */
include_once("inc/config.php");

$db = new DbLogin();

/**
 * Veritabanımıza bağlanmaya çalışıyoruz.
 * Bağlanamazsak, hata mesajını ekrana yazdırıyoruz
 */
if (!$db->connect()) {
    die("Hata: Veritabanına bağlanırken bir hata oluştu." . $db->error());
}

/**
 * login_user oturum değişkeninden bilgileri alıyoruz ve
 * $user değişkenine kaydediyoruz.
 *
 * Eğer kullanıcımız oturum açmış ise, $user dolu olacak.
 * Eğer kullanıcımız daha önce oturum açmamış ise, $user boş olacak.
 *
 */
$user = $_SESSION["login_userAKKO"];

/**
 * Kullanıcı zaten oturum açmış ise index.php ye yönlendiriyoruz.
 * $user dolu ise, yada içinde her hangi bir değer var ise, bunun anlamı kullanıcımız oturum açmış.
 * Eğer $user boş ise, bunun anlamı kullanıcımız henüz oturum açmamış
 */
if ($user) {
    header("location: /eCatalog");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("../header.php"); ?>
    <title>Giriş Yap</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/animsition/css/animsition.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="assets/css/util.css">
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
</head>

<body>

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100 p-l-85 p-r-85 p-t-55 p-b-55">
                <?php
                if ($_GET["type"] == 'success') {
                    echo '<div class="alert alert-success" role="alert"> Tebrikler! Başarıyla kayıt oldunuz. Artık oturum açabilirsiniz.</div>';
                }

                if ($_GET["type"] == 'error') {
                    echo '<div class="alert alert-danger" role="alert"> Geçersiz Kullanıcı Adı/Şifre</div>';
                }
                if ($_GET["type"] == 'guvenlik') {
                    echo '<div class="alert alert-danger" role="alert"> Lütfen Güvenlik İşlemlerini Tamamlayınız.</div>';
                }
                ?>
                <form class="login100-form validate-form flex-sb flex-w" method="post" action="login_check.php">
                    <span class="login100-form-title p-b-32">AKKO</span>
                    <span class="txt1 p-b-11">Kullanıcı Adı</span>
                    <div class="wrap-input100 validate-input m-b-36" data-validate="Kullanıcı adı zorunludur.">
                        <input class="input100" type="text" name="username" id="username" placeholder="E-posta adresiniz.">
                        <span class="focus-input100"></span>
                    </div>
                    <span class="txt1 p-b-11">Şifre</span>
                    <div class="wrap-input100 validate-input m-b-12" data-validate="Şifre zorunludur. Boş bırakılamaz.">
                        <span class="btn-show-pass"><i class="fa fa-eye"></i></span>
                        <input class="input100" type="password" name="password" id="password" placeholder="Şifreniz.">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="flex-sb-m w-full p-b-48">
                        <div class="contact100-form-checkbox">
                            <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
                            <label class="label-checkbox100" for="ckb1">Beni Hatırla</label>
                        </div>
                        <div>
                            <a href="#" class="txt3">Şifremi Unuttum</i></a>
                        </div>
                    </div>
                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn">Giriş</button>
                        <div class="g-recaptcha" data-sitekey="6Ld6_uQUAAAAAOBGAcU-jprp6FUQzFytazUlEwLq" style="padding-top: 25px;"></div>
                    </div>                    
                </form>
            </div>
        </div>
    </div>
    <div id="dropDownSelect1"></div>
    <script src="assets/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="assets/vendor/animsition/js/animsition.min.js"></script>
    <script src="assets/vendor/bootstrap/js/popper.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/vendor/select2/select2.min.js"></script>
    <script src="assets/vendor/daterangepicker/moment.min.js"></script>
    <script src="assets/vendor/daterangepicker/daterangepicker.js"></script>
    <script src="assets/vendor/countdowntime/countdowntime.js"></script>
    <script src="assets/js/main.js"></script>
    <script src='https://www.google.com/recaptcha/api.js?hl=tr'></script>
</body>

</html>