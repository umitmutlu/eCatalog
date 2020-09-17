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
 * Kullanıcı zaten üye olmuş ve
 * Oturum açmış ise, index.php ye yönlendiriyoruz.
 */
if ($user["username"] != "admin") {
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
    $username = $_POST["username"];
    $password1 = $_POST["password1"];
    $password2 = $_POST["password2"];
    $permission_delete = $_POST["permission_delete"];

    /**
     * Varsa değişkenlerde gereksiz boşluklar.
     * Bu boşlukları siliyoruz.
     */
    $username = trim($username);
    $password1 = trim($password1);
    $password2 = trim($password2);


    // ======== Basit Kontrolleri Gerçekleştir. ========
    /**
     * İsim girmiş mi ?
     */
    if (empty($username)) {
        /**
         * Hata yakaladık.
         * Vatandaş adını girmemiş.
         */
        $error = true;
        $errors[] = 'Lütfen kullanıcı adınızı girin. Bu alan boş bırakılamaz.';
    } else if (!empty($username)) {
        $sql = "SELECT username FROM user WHERE username='$username'";

        $result = $db->query($sql);

        if ($result->num_rows > 0) {
            /*while($row = $result->fetch_assoc()) {
                echo "email: " .$row["email"]. "";
            }*/
            $error = true;
            $errors[] = 'Bu kullanıcı adı kullanılmaktadır.';
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
        $errors[] = 'Şifreler Eşleşmiyor.';
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
        $username = $db->quote($username);
        $password = md5($password1);
        $sorgu = "INSERT INTO user (username,password,created_at,updated_at,permission_delete) VALUES ($username,'$password',NOW(), NOW(), $permission_delete);";

        $db->query($sorgu);

        header("location: register.php?type=success");
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
    <title>Yeni Üye Oluştur</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <hr>
            </div>
            <div class="col-md-12">
                <h3>Yeni Üye Bilgileri</h3>
                <form method="POST" action="register.php">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Kuallnıcı Adı</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Kullanıcı adınız">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Şifre</label>
                        <input type="password" class="form-control" id="password1" name="password1" placeholder="Şifre">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Şifre - Tekrar</label>
                        <input type="password" class="form-control" id="password2" name="password2" placeholder="Şifre(Tekrar)">
                    </div>
                    <label for="exampleInputEmail1">Silme Yetkisi</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="permission_delete" value="1">
                        <label class="form-check-label" for="exampleRadios1">
                            Var
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="permission_delete" value="0" checked>
                        <label class="form-check-label" for="exampleRadios2">
                            Yok
                        </label>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Kayıt İşlemini Tamamla</button>
                </form>
                <hr>
                <?php
                if ($_GET["type"] == 'success') {
                    echo '<div class="alert alert-success" role="alert"> Yeni Üye Başarıyla Oluşturuldu.</div>';
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