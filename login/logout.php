<?php
/**
 * Created by PhpStorm.
 * User: BenVeAlem
 * Date: 2/25/2018
 * Time: 1:00 AM
 * Site: https://www.benvealem.com/
 */

// Session u başlatıyoruz.
session_start();

// TÜM Session ları boşaltıyoruz.
$_SESSION = array();

// Session için tarayıcıya gönderilen Cookie leri expire ediyoruz.Bir nevi kullanılmaz hale getiriyoruz..
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

/**
 * Bu adımda ise tüm sessionları yok ediyoruz.
 * Kullanıcı çıkış yaptı.
 */
session_destroy();

/**
 * Son adımda ise  kullanıcıyı index.php ye gönderiyoruz.
 * Artık tekrar oturum mu açar, yoksa çıkar gider mi, kullanıcımızın kendisinin bileceği iş.
 */
header("location: /eCatalog");
exit;