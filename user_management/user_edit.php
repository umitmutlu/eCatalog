<?php
include_once("../config.php");
include  '../header.php';
$user = $_SESSION["login_userAKKO"];
if ($user["username"] != "admin") {
    header("location: /eCatalog");
    exit;
} else {
    include  '../errors.php';
    $db = new Db();
    if ($_POST["type"] == "permission") {
        $username = $_POST["username"];
        $permission_delete = $_POST["permission_delete"];
        $sorgu = "UPDATE user SET permission_delete = '$permission_delete', updated_at = NOW() WHERE username = '$username'";
        $db->query($sorgu);
        header("location:/eCatalog/user_management/user_edit.php?error=degistirildi");
        exit;
    } else if ($_GET["delete"] == "user") {
        $username = $_GET["username"];
        $sorgu = "DELETE FROM user WHERE username= '$username'";
        $db->select($sorgu);
        header('location: /eCatalog/user_management/user_edit.php');
        exit;
    } ?>
    <!DOCTYPE html>
    <html lang="tr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kullanıcı Yönetimi</title>
        <script>
            function isDelete(deleteVariable) {
                var splitVariable = deleteVariable.split("=");
                if (splitVariable[0] == "user") {
                    window.location = "/eCatalog/user_management/user_edit.php?delete=user&username=" + splitVariable[1];
                }
            }
        </script>
        <?php
        // BEGIN - Silme işlemlerinde, şifre kontrolü
        if (@$_POST["password_check"] == "delete") {
            $username = $user["username"];
            $password = $_POST["password"];
            $altbaslik_no = $_POST["altbaslik_no"];
            $kesici_uclar_no = $_POST["kesici_uclar_no"];
            $deleteVariable = $_POST["deleteVariable"];
            $password = trim($password);
            $password = md5($password);
            $sorgu = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
            $gelen = $db->select($sorgu);
            if ($sonuc = mysqli_fetch_assoc($gelen)) { ?>
                <script>
                    isDelete('<?php echo $deleteVariable; ?>');
                </script>
        <?php } else {
                header('location: /eCatalog/user_management/user_edit.php?error=sifreYanlis');
                exit;
            }
        } // END - Silme işlemlerinde, şifre kontrolü 
        ?>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    </head>

    <body>

    </body>

    </html>
    <?php if ($_GET["edit"]) {
        $username = $_GET["edit"];
        $sorgu = "SELECT * FROM user WHERE username='$username'";
        $gelen = $db->select($sorgu);
        $sonuc = mysqli_fetch_assoc($gelen); ?>
        <div class="urunlerTablo" style="padding-top:25px;width:50%;margin:0 auto;">
            <form action="/eCatalog/user_management/user_edit.php" method="POST" enctype="multipart/form-data">
                <table class="table" rules="all" style="border-style:None;text-align:center;border-collapse:collapse;">
                    <tr style="background-color: #B8274C;color:white;font-weight:bold;">
                        <td>Kullanıcı Adı</td>
                        <?php if ($_GET["edit"]) { ?>
                            <td colspan="2">Silme Yetkisi</td>
                            <td>*</td>
                        <?php } else { ?>
                            <td>Silme Yetkisi</td>
                        <?php } ?>
                    </tr>
                    <tr style="background-color:#FFE6CB;color:black;">
                        <td scope="col" rowspan="2" style="max-width:100px;min-width:75px;font-weight:bold;">
                            <?php echo $username; ?>
                            <input type="hidden" name="username" value="<?php echo $username; ?>">
                            <input type="hidden" name="type" value="permission">
                        </td>
                        <td style="margin:0;padding:0;">Var</td>
                        <td style="margin:0;padding:0;">Yok</td>
                        <td rowspan="2">
                            <button type="submit" class="btn btn-primary btn-sm">Kaydet</button>
                        </td>
                    </tr>
                    <tr style="background-color:#FFE6CB;color:black;">
                        <td style="margin:0;padding:0;"><input type="radio" name="permission_delete" value="1" <?php if ($sonuc["permission_delete"] == "1") echo 'checked'; ?>></td>
                        <td style="margin:0;padding:0;"><input type="radio" name="permission_delete" value="0" <?php if ($sonuc["permission_delete"] == "0") echo 'checked'; ?>></td>
                    </tr>
                </table>
            </form>
        </div>
    <?php } else { ?>
        <div class="urunlerTablo" style="padding-top:25px;width:50%;margin:0 auto;">
            <form action="/eCatalog/user_management/user_edit.php" method="POST" enctype="multipart/form-data">
                <table class="table" rules="all" style="border-style:None;text-align:center;border-collapse:collapse;">
                    <tr style="background-color: #B8274C;color:white;font-weight:bold;">
                        <td>Düzenle</td>
                        <td>Kullanıcı Adı</td>
                        <td>Silme Yetkisi</td>
                    </tr>
                    <?php $sorgu = "SELECT * FROM user WHERE username!='admin'";
                    $gelen = $db->select($sorgu);
                    while ($sonuc = mysqli_fetch_assoc($gelen)) { ?>
                        <tr style="background-color:#FFE6CB;color:black;">
                            <td style="color:black;" <?php if ($_GET["edit"]) { ?> rowspan="2" <?php } ?>>
                                <a data-toggle="modal" data-target="#exampleModal" data-delete-data="user=<?php echo $sonuc["username"]; ?>">
                                    <i class="fas fa-trash-alt" style="color: #007bff; cursor: pointer;"></i>
                                </a>
                                <a href="/eCatalog/user_management/user_edit.php?edit=<?php echo $sonuc["username"]; ?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                            <td scope="col" style="max-width:100px;min-width:75px;font-weight:bold;">
                                <?php echo $sonuc["username"]; ?>
                            </td>
                            <td scope="col" style="max-width:100px;min-width:75px;">
                                <?php if ($sonuc["permission_delete"] == 0) {
                                    echo "Yok";
                                } else {
                                    echo "Var";
                                } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </form>
        </div>
    <?php } ?>
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
                <div class="modal-body" action="">
                    Silmek istediğinize emin misiniz ?
                    <form class="form-inline" action="user_edit.php" method="POST">
                        <input type="hidden" name="password_check" value="delete">
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
                            console.log("burdayız");
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