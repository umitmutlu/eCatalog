<?php

/**
 * Created by PhpStorm.
 * User: BenVeAlem
 * Date: 2/25/2018
 * Time: 1:00 AM
 * Site: https://www.benvealem.com/
 */

/**
 * Veritabanı bağlantı bilgilerimizin olduğu sayfayı dahil ediyoruz.
 */
include_once("../config.php");

$db = new Db();

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
 * Kullanıcı zaten üye olmuş ve
 * Oturum açmış ise, index.php ye yönlendiriyoruz.
 */
if (!$user) {
    header("location: /eCatalog");
    exit;
}


/**
 * Yapılan işlemi kontrol et.
 * Eğer işlem POST ise, kullanıcının forma girdiği bilgileri al.
 */
if ($_POST) {
    /**
     * Varsayılan olarak hata durumunu ve
     * Hata mesajını false/null olarak ayarla.
     * Hata bulursak bu değişkenleri dolduracağız.
     */
    $error = false;
    $errors = array();

    /**
     * Form dan gelen bilgileri al
     */
    $passwordEski = $_POST["passwordEski"];
    $password1 = $_POST["password1"];
    $password2 = $_POST["password2"];
    $permission_delete = $_POST["permission_delete"];

    /**
     * Varsa değişkenlerde gereksiz boşluklar.
     * Bu boşlukları siliyoruz.
     */
    $passwordEski = trim($passwordEski);
    $password1 = trim($password1);
    $password2 = trim($password2);


    // ======== Basit Kontrolleri Gerçekleştir. ========
    /**
     * İsim girmiş mi ?
     */
    if (empty($passwordEski)) {
        /**
         * Hata yakaladık.
         * Vatandaş adını girmemiş.
         */
        $error = true;
        $errors[] = 'Lütfen eski şifreyi girin. Bu alan boş bırakılamaz.';
    } else if (!empty($passwordEski)) {
        $userName = $user["username"];
        $sorgu = "SELECT password FROM user WHERE username='$userName'";
        $gelen = $db->select($sorgu);
        $sonuc = mysqli_fetch_assoc($gelen);

        if ($sonuc["password"] != md5($passwordEski)) {
            /*while($row = $result->fetch_assoc()) {
                echo "email: " .$row["email"]. "";
            }*/
            $error = true;
            $errors[] = 'Eski Şifre Yanlış.';
        }/*else{
            echo "0 results";
        }*/
    }

    /**
     * Şifreler eşleşiyor mu kontrol et.
     */
    if ($password1 != $password2) {
        /**
         * Hata bulduk.
         * Şifreler eşleşmiyor.
         */
        $error = true;
        $errors[] = 'Yeni Şifreler Eşleşmiyor.';
    }

    /**
     * Şifre 4 karakterden uzun mu ?
     */
    if (strlen($password1) < 5) {
        /**
         * Hata bulduk.
         * Şifre 4 karakter yada daha küçük.
         */
        $error = true;
        $errors[] = 'Şifre en az 5 karakter olmalıdır.';
    }

    /**
     * Şu ana kadar eğer hiçbir hata ile karşılaşmadıysak
     * Ekleme işlemini yapacağız.
     * Eğer hata varsa ekleme işlemini yapmayacağız.
     *
     * Burada kafanız karışmasın.
     * $error ın ilk değeri false idi. Yani hata yok demekti.
     * Eğer hiç hata bulamadıysak değeri hala false kalacak.
     *  if de başında ! işarati değeri ters çevirecek.
     * Eğer hiç hata bulamazsak $error değeri false 'tur. Ama !$error un değeri TRUE dur.
     * true olunca if in içerisine girecek. ve Register işlemini yapacak.
     *
     * Eğer hata bulursak $error true olacak, !$error ise false olacak. Dolayısıyla if in içine girmeyecek.
     *
     */
    if (!$error) {
        /**
         * Bu bilgileri güvenli hala getir.
         */
        echo 'girdi';
        $password = md5($password1);
        $userName = $user["username"];
        $sorgu = "UPDATE user SET password = '$password', updated_at = NOW() WHERE username = '$userName'";

        $db->query($sorgu);

        header("location: /eCatalog/user_management/password_change.php?type=success");
        exit;
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <?php include("../header.php"); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.min.css">
    <title>Şifre Değiştir</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <hr>
            </div>
            <div class="col-md-12">
                <h3>Şifre Değiştir</h3>
                <form method="POST" action="password_change.php">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Eski Şifre</label>
                        <input type="password" class="form-control" id="passwordEski" name="passwordEski" placeholder="Eski Şifre">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Yeni Şifre</label>
                        <input type="password" class="form-control" id="password1" name="password1" placeholder="Şifre">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Yeni Şifre - Tekrar</label>
                        <input type="password" class="form-control" id="password2" name="password2" placeholder="Şifre(Tekrar)">
                    </div>
                    <button type="submit" class="btn btn-primary">Şifreyi Değiştir</button>
                </form>
                <hr>
                <?php
                if ($_GET["type"] == 'success') {
                    echo '<div class="alert alert-success" role="alert"> Şifre Başarıyla Değiştirildi.</div>';
                }
                /**
                 * Eğer Method Post ise
                 */
                if ($_POST) {
                    /**
                     * Hata durumunu kontrol et.
                     */
                    if ($error) {
                        /**
                         * Eğer hata var ise,
                         * Toplam hata adedini bul.
                         * Ve ekrana yazdır.
                         */
                        $totalError = count($errors);
                        echo '<div class="alert alert-danger" role="alert">' . $totalError . ' Hata bulundu. Lütfen bu hataları giderin ve tekrar deneyin.</div>';

                        /**
                         * Tek tek hataları ekrana yaz.
                         */
                        foreach ($errors as $err) {
                            echo '<div class="alert alert-warning" role="alert">' . $err . '</div>';
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>