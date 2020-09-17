<?php include_once("../config.php");

$db = new Db();

$user = $_SESSION["login_userAKKO"];
if ($user) {
    if ($_POST) {
        $parca_Tisim = $_POST["parca_Tisim"];
        $urun_no = $_POST["urun_no"];
        $parca_ref = $_POST["parca_ref"];
        $image_ref = $_POST["image_ref"];
        $buton = $_POST["buton"];
        $error = 0;
        if ($_GET["type"] == "yedekDuzenle") {
            if ($buton == "Kaydet") {
                if ($parca_Tisim == "") {
                    $error = 1;
                } else {
                    $sorgu = 'UPDATE urun_yedek SET parca_Tisim = "' . $parca_Tisim . '", image_ref = "' . $image_ref . '"  WHERE urun_ref = "' . $urun_no . '" AND parca_ref = "' . $parca_ref . '"';
                    $db->query($sorgu);
                }
            }
            header('location: /eCatalog/urun.php?urun_no=' . $urun_no . '&error=' . $error . '');
            exit;
        } else if ($_GET["type"] == "yedekDelete") {
            $sorgu = 'DELETE FROM urun_yedek WHERE parca_ref=' . $_GET["parca_ref"] . ' AND urun_ref = ' . $urun_no . '';
            $db->select($sorgu);
            header('location: /eCatalog/urun.php?urun_no=' . $urun_no . '&error=' . $error . '');
            exit;
        }
    }
}
