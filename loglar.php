<?php
include_once("./config.php");
$db = new Db();
$user = $_SESSION["login_userAKKO"];
include("header.php");

if($user["username"] != "admin"){
    header("location: /eCatalog/index.php");
    exit;
}
if (isset($_GET['username'])) {
    $username = $_GET["username"];
} else {
    $username = "";
}
if (isset($_GET['islem'])) {
    $islem = $_GET["islem"];
} else {
    $islem = "";
}
if (isset($_GET['tarih'])) {
    $tarih = $_GET["tarih"];
} else {
    $tarih = "";
}

if (isset($_GET['page'])) {
    $pageno = $_GET['page'];
} else {
    $pageno = 1;
}
$NUMPERPAGE = 15;
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body>




</body>

<style>
    .searchForm {
        display: flex;
        align-items: center;
        text-align: center;
    }

    .arama {
        margin: 0 auto;
        padding: 0;
        align-items: center;
        /*   width: 50% */
    }

    .aramaContent {
        width: 100%;
    }

    .aramaButton {
        margin-left: 20px;
    }

    .aramaContent .searchFilter {
        width: 20%;
        margin-left: 10px;
        padding: 4px;
        box-shadow: 2px 4px 3px 1px #afbac7;
    }

    .aramaContent .searchInput {
        width: 40%;
        border-radius: 3px;
        padding: 4px;
        border-radius: 5px;
        box-shadow: 2px 4px 3px 1px #afbac7;
    }

    .aramaContent .aramaButton {
        width: 5%;
    }

    @media only screen and (max-width:600px) {

        .arama {
            padding: 0;
            width: 100%;
        }

        .aramaContent {
            width: 100%;
        }

        .aramaButton {
            margin-left: 20px;
        }

        .aramaContent .searchFilter {
            width: 25%;
        }

        .aramaContent .searchInput {
            width: 47%;
            border-radius: 3px;
        }

        .aramaContent .aramaButton {
            width: 10%;

        }
    }
</style>

</html>



<html>
<div class="searchResults" style="width:75%;margin:0 auto; margin-top:5%;align-items:center;justify-content:center;">
    <table class="table">
        <thead>
            <tr>
                <td>
                    <form action="loglar.php" method="GET" id="tarihForm" autocomplete="off">
                        <input type="text" id="datepicker" name="tarih" <?php if ($tarih) echo 'value=' . $tarih;
                                                                        else echo 'placeholder="Tarih Seçiniz"'; ?> onchange="this.form.submit()">
                        <input type="hidden" name="islem" value="<?php echo $islem; ?>">
                        <input type="hidden" name="username" value="<?php echo $username; ?>">
                    </form>
                </td>
                <script>
                    $(function() {
                        $("#datepicker").datepicker({
                            dateFormat: "yy-mm-dd"
                        });
                    });
                    $('#datepicker').on('change', function() {
                        var simdiTarih = this.value;

                        console.log(simdiTarih);
                    });
                </script>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php if ($islem) {
                                echo mb_strtoupper($islem, 'UTF-8');
                            } else {
                                echo 'İŞLEME GÖRE';
                            } ?>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="/eCatalog/loglar.php?tarih=<?php echo $tarih; ?>&islem=ekleme&username=<?php echo $username; ?>">EKLEME</a>
                            <a class="dropdown-item" href="/eCatalog/loglar.php?tarih=<?php echo $tarih; ?>&islem=duzenleme&username=<?php echo $username; ?>">DÜZENLEME</a>
                            <a class="dropdown-item" href="/eCatalog/loglar.php?tarih=<?php echo $tarih; ?>&islem=silme&username=<?php echo $username; ?>">SİLME</a>
                        </div>
                    </div>
                </td>

                <td>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php if ($username) {
                                echo $username;
                            } else {
                                echo 'KULLANICIYA GÖRE';
                            } ?>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <?php
                            $sorgu = 'SELECT username FROM user';
                            $gelen = $db->select($sorgu);
                            while ($sonuc = mysqli_fetch_assoc($gelen)) { ?>
                                <a class="dropdown-item" href="/eCatalog/loglar.php?tarih=<?php echo $tarih; ?>&islem=<?php echo $islem; ?>&username=<?php echo $sonuc['username']; ?>"><?php echo $sonuc['username']; ?></a>
                            <?php } ?>
                        </div>
                    </div>
                </td>
                <td>
                    <i class="far fa-window-close"></i>&nbsp;
                    <a href="/eCatalog/loglar.php">Filtreleri Temizle</a>
                </td>
            <tr></tr>
            <th scope="col" style="background-color:#C4674F">
                <span style="color:white;max-width: 10%;">Tarih</span>
            </th>
            <th scope="col" style="background-color:#C4674F">
                <span style="color:white;max-width: 10%;">İşlem</span>
            </th>
            <th scope="col" style="background-color:#C4674F">
                <span style="color:white;max-width: 10%;">Kullanıcı Adı</span>
            </th>
            <th scope="col" style="background-color:#C4674F">
                <span style="color:white;">Yapılan İşlem</span>
            </th>
            </tr>
        </thead>
        <?php
        /*$sorgu = 'INSERT INTO loglar(created_at, log_username, log_islem, log_text) VALUES (NOW(),"' . $user["username"] . '","silme","blabla");';
        $db->query($sorgu);*/



        $offset = ($pageno - 1) * $NUMPERPAGE;

        if ($username || $islem || $tarih) {
            $satir = 'SELECT COUNT(*) FROM loglar WHERE created_at LIKE \'%' . $tarih . '%\' AND log_username LIKE \'%' . $username . '%\' AND log_islem LIKE \'%' . mb_strtoupper($islem, 'UTF-8') . '%\'';
        } else {
            $satir = 'SELECT COUNT(*) FROM loglar';
        }
        $gelenSatirSayisi = $db->select($satir);
        $kacSatir = mysqli_fetch_array($gelenSatirSayisi)[0];
        $total_pages = ceil($kacSatir / $NUMPERPAGE);
        if ($username || $islem || $tarih) {
            $sorgu = 'SELECT * FROM loglar WHERE created_at LIKE \'%' . $tarih . '%\' AND log_username LIKE \'%' . $username . '%\' AND log_islem LIKE \'%' . mb_strtoupper($islem, 'UTF-8') . '%\' ORDER BY created_at DESC  LIMIT ' . $offset . ', ' . $NUMPERPAGE . '';
        } else {
            $sorgu = 'SELECT * FROM loglar ORDER BY created_at DESC  LIMIT ' . $offset . ', ' . $NUMPERPAGE . '';
        }
        $gelen = $db->select($sorgu);
        while ($sonuc = mysqli_fetch_assoc($gelen)) { ?>
            <tbody>
                <tr>
                    <td><?php echo $sonuc["created_at"]; ?></a></td>
                    <td><?php echo $sonuc["log_islem"]; ?></a></td>
                    <td><?php echo $sonuc["log_username"]; ?></a></td>
                    <td><?php echo $sonuc["log_text"]; ?></a></td>
                </tr>
            </tbody>
        <?php } ?>
    </table>

</html>




<?php
$this_page = "loglar.php";
$data = range(1, $NUMPERPAGE * $total_pages); // data array to be paginated
$num_results = $NUMPERPAGE * $total_pages;

if (!isset($_GET['page']) || !$page = intval($_GET['page'])) {
    $page = 1;
}

// extra variables to append to navigation links (optional)
$linkextra = '&tarih=' . $tarih . '&islem=' . $islem . '&username=' . $username;
if (isset($_GET['var1']) && $var1 = $_GET['var1']) { // repeat as needed for each extra variable
    $linkextra[] = "var1=" . urlencode($var1);
}
if ($linkextra) {
    $linkextra .= "&amp;";
}

// build array containing links to all pages
$tmp = [];
for ($p = 1, $i = 0; $i < $num_results; $p++, $i += $NUMPERPAGE) {
    if ($page == $p) {
        // current page shown as bold, no link
        $tmp[] = "<b>{$p}</b>";
    } else {
        $tmp[] = "<a href=\"{$this_page}?{$linkextra}page={$p}\">{$p}</a>";
    }
}

// thin out the links (optional)
for ($i = count($tmp) - 3; $i > 1; $i--) {
    if (abs($page - $i - 1) > 2) {
        unset($tmp[$i]);
    }
}

// display page navigation iff data covers more than one page
if (count($tmp) > 1) {
    echo "<p>";

    if ($page > 1) {
        // display 'Prev' link
        echo "<a href=\"{$this_page}?{$linkextra}page=" . ($page - 1) . "\">&laquo; Prev</a> | ";
    } else {
        echo "Page ";
    }

    $lastlink = 0;
    foreach ($tmp as $i => $link) {
        if ($i > $lastlink + 1) {
            echo " ... "; // where one or more links have been omitted
        } elseif ($i) {
            echo " | ";
        }
        echo $link;
        $lastlink = $i;
    }

    if ($page <= $lastlink) {
        // display 'Next' link
        echo " | <a href=\"{$this_page}?{$linkextra}page=" . ($page + 1) . "\">Next &raquo;</a>";
    }

    echo "</p>\n\n";
} ?>