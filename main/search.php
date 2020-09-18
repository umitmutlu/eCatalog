<?php
include_once("./config.php");
$db = new Db();
$connect = $db->connect();
if ($_SERVER['SCRIPT_NAME'] == "/eCatalog/search.php") {
    include("header.php");
}
if (isset($_GET['page'])) {
    $pageno = $_GET['page'];
} else {
    $pageno = 1;
}
$NUMPERPAGE = 20;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>

<body>
    <div class="contentOfSearch">
        <div class="arama" style="width:75%; margin-top:5%;align-items:center;justify-content:center;">
            <form action="search.php" method="GET" class="searchForm">
                <div class="aramaContent">
                    <input name="aramaIsim" class="searchInput" type="search" placeholder="Search" aria-label="Search" style="background-color: beige;">
                    <select class="searchFilter" name="kategori" style="margin-left:10px;background-color: beige;">
                        <option value="urun" <?php if ($_GET["kategori"] == "urun") echo 'selected'; ?>>Ürüne Göre</option>
                        <option value="baslik" <?php if ($_GET["kategori"] == "baslik") echo 'selected'; ?>>Başlığa Göre</option>
                    </select>
                    <button class="aramaButton" type="submit" href="#" style="background-color: beige;"><i class="fas fa-search"></i></button>
                </div>
            </form>

            <?php
            if ($_GET) {
                @$aramaIsim = $_GET["aramaIsim"];
                @$aramaIsim = $connect->real_escape_string($aramaIsim);
                @$kategori = $_GET["kategori"];
                if ($kategori == "urun" && $aramaIsim != "") {
            ?>
                    <html>
                    <div class="searchResults" style="width:75%;margin:0 auto; margin-top:5%;align-items:center;justify-content:center;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" style="background-color:#C4674F">
                                        <h3><span style="color:white;">Ürün Adı</span></h3>
                                    </th>
                                </tr>
                            </thead>
                            <?php
                            $offset = ($pageno - 1) * $NUMPERPAGE;

                            $satir = 'SELECT COUNT(*) FROM urun WHERE urun_ad LIKE \'%' . $aramaIsim . '%\' AND urun_ad NOT LIKE \'%hurda_%\'';
                            $gelenSatirSayisi = $db->select($satir);
                            $kacSatir = mysqli_fetch_array($gelenSatirSayisi)[0];
                            $total_pages = ceil($kacSatir / $NUMPERPAGE);
                            $sorgu = 'SELECT * FROM urun  WHERE urun_ad LIKE \'%' . $aramaIsim . '%\' AND urun_ad NOT LIKE \'%hurda_%\'  LIMIT ' . $offset . ', ' . $NUMPERPAGE . '';
                            $gelen = $db->select($sorgu);
                            while ($sonuc = mysqli_fetch_assoc($gelen)) { ?>
                                <tbody>
                                    <tr>
                                        <td><a style="text-decoration:none;" href="urun.php?urun_no=<?php echo $sonuc["urun_no"]; ?>"><?php echo $sonuc["urun_ad"]; ?></a></td>
                                    </tr>
                                </tbody>
                            <?php } ?>
                        </table>
                    <?php
                } else if ($kategori == "baslik") {
                    ?>

                        <h3 style="margin-top:5%;"><span style="color:orange;">Arama Sonuçları</span></h3>
                        </td>
                        </tr>
                        <hr>
                        <div class="resultsOfFamily" style="margin-top:5%;">
                            <div style="display:flex;flex-flow:wrap;">
                                <?php
                                $offset = ($pageno - 1) * $NUMPERPAGE;
                                $satir = 'SELECT COUNT(*) FROM altkatalog WHERE alt_ad LIKE \'%' . $aramaIsim . '%\'';
                                $gelenSatirSayisi = $db->select($satir);
                                $kacSatir = mysqli_fetch_array($gelenSatirSayisi)[0];
                                $total_pages = ceil($kacSatir / $NUMPERPAGE);
                                $sorgu = 'SELECT * FROM altkatalog WHERE alt_ad LIKE \'%' . $aramaIsim . '%\' LIMIT ' . $offset . ', ' . $NUMPERPAGE . '';
                                $gelen = $db->select($sorgu);

                                while ($sonuc = mysqli_fetch_assoc($gelen)) {
                                ?>

                                    <div class="col-2 col-m-3 menu">
                                        <?php
                                        echo '<a href="index.php?type=altbaslikKategori&alt_ref=' . $sonuc["alt_no"] . '">';
                                        ?>
                                        <div id="content_dlSubApplications_subAppsDiv_0" style="border-color:#F7941D;">
                                            <table border="0" width="100%" height="100%">
                                                <tbody>
                                                    <tr>
                                                        <td align="center" height="54px" valign="center">
                                                            <img src="<?php echo $sonuc["altkatalog_img"]; ?>" width="80" height="80" border="0" alt=""> </td>
                                                    </tr>
                                                    <tr valign="center">
                                                        <td align="center">
                                                            <?php echo $sonuc["alt_ad"]; ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        </a>
                                        <br><br>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>


                <?php
                }
            }
                ?>

                <?php
                $this_page = "search.php";
                $data = range(1, $NUMPERPAGE * $total_pages); // data array to be paginated
                $num_results = $NUMPERPAGE * $total_pages;

                if (!isset($_GET['page']) || !$page = intval($_GET['page'])) {
                    $page = 1;
                }

                // extra variables to append to navigation links (optional)
                $linkextra = '&aramaIsim=' . $aramaIsim . '&kategori=' . $kategori;
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

                // // thin out the links (optional)
                // for ($i = count($tmp) - 3; $i > 1; $i--) {
                //     if (abs($page - $i - 1) > 2) {
                //         unset($tmp[$i]);
                //     }
                // }

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

                    </div>
        </div>


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