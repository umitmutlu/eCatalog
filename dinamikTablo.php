<?php
include_once("config.php");
class cls_dinamikTablo
{
    public function tablo($satir, $hangiIslem, $ana_ref, $alt_ref, $urun_no)
    {
        $db = new Db();
        $sorgu = 'SELECT * FROM urun_ozellikleri WHERE ana_ref ="' . $ana_ref . '"';
        $gelen = $db->select($sorgu);
        $sonuc = mysqli_fetch_assoc($gelen);

        if ($hangiIslem == "Add") {
            // Urun tablosunda ilgili özellikleri sergiliyor sadece
            if ($satir == 0) {
                $sorgu = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="urun_ozellikleri";';
                $gelenSutun = $db->select($sorgu);
                while ($sutun = mysqli_fetch_assoc($gelenSutun)) {
                    if ($sutun["COLUMN_NAME"] == "ana_ref" || $sutun["COLUMN_NAME"] == "urunOzellik_ad") {
                        continue;
                    }
                    if ($sonuc[$sutun["COLUMN_NAME"]] == '1') {
                        echo '<td>' . substr($sutun["COLUMN_NAME"], 5) . '</td>';
                    }
                }
                /*foreach ($dizi as $isim => $deger) {
                    if ($deger == '1') {
                        echo '<td>' . $isim . '</td>';
                    }
                }*/
            } else {
                $sorgu = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="urun_ozellikleri";';
                $gelenSutun = $db->select($sorgu);
                while ($sutun = mysqli_fetch_assoc($gelenSutun)) {
                    if ($sutun["COLUMN_NAME"] == "ana_ref" || $sutun["COLUMN_NAME"] == "urunOzellik_ad") {
                        continue;
                    }
                    if ($sonuc[$sutun["COLUMN_NAME"]] == '1') {
                        if (substr($sutun["COLUMN_NAME"], 5) == 'RIGHT') {
                            echo '<td><input type="checkbox" value="1" name="urun_' . substr($sutun["COLUMN_NAME"], 5) . '"/></td>';
                            continue;
                        } else if (substr($sutun["COLUMN_NAME"], 5) == 'LEFT') {
                            echo '<td><input type="checkbox" value="1" name="urun_' . substr($sutun["COLUMN_NAME"], 5) . '"/></td>';
                            continue;
                        }
                        echo '<td><input type="text" name="urun_' . substr($sutun["COLUMN_NAME"], 5) . '"></td>';
                    }
                }
                /*foreach ($dizi as $isim => $deger) {
                    if ($deger == '1') {
                        if ($isim == 'RIGHT') {
                            echo '<td><input type="checkbox" value="1" name="urun_' . $isim . '"/></td>';
                            continue;
                        } else if ($isim == 'LEFT') {
                            echo '<td><input type="checkbox" value="1" name="urun_' . $isim . '"/></td>';
                            continue;
                        }
                        echo '<td><input type="text" name="urun_' . $isim . '"></td>';
                    }
                }*/
            }
        } else if ($hangiIslem == "Edit") {
            if ($satir == 0) {
                $sorgu = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="urun_ozellikleri";';
                $gelenSutun = $db->select($sorgu);
                while ($sutun = mysqli_fetch_assoc($gelenSutun)) {
                    if ($sutun["COLUMN_NAME"] == "ana_ref" || $sutun["COLUMN_NAME"] == "urunOzellik_ad") {
                        continue;
                    }
                    if ($sonuc[$sutun["COLUMN_NAME"]] == '1') {
                        echo '<td>Yeni ' . substr($sutun["COLUMN_NAME"], 5) . ' Değeri</td>';
                    }
                }
                /*foreach ($dizi as $isim => $deger) {
                    if ($deger == '1') {
                        echo '<td>Yeni ' . $isim . ' Değeri</td>';
                    }
                }*/
            } else {
                $sorgu = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="urun_ozellikleri";';
                $gelenSutun = $db->select($sorgu);
                $sorgu = 'SELECT * FROM urun WHERE urun_no = "' . $urun_no . '"';
                $gelenUrun = $db->select($sorgu);
                $sonucUrun = mysqli_fetch_assoc($gelenUrun);
                while ($sutun = mysqli_fetch_assoc($gelenSutun)) {
                    if ($sutun["COLUMN_NAME"] == "ana_ref" || $sutun["COLUMN_NAME"] == "urunOzellik_ad") {
                        continue;
                    }
                    if ($sonuc[$sutun["COLUMN_NAME"]] == '1') {
                        if (substr($sutun["COLUMN_NAME"], 5) == 'RIGHT') {
                            if ($sonucUrun[$sutun["COLUMN_NAME"]] == '1') {
                                echo '<td><input type="checkbox" checked value="1" name="' . $sutun["COLUMN_NAME"] . '"/></td>';
                            } else {
                                echo '<td><input type="checkbox" value="1" name="' . $sutun["COLUMN_NAME"] . '"/></td>';
                            }
                            continue;
                        } else if (substr($sutun["COLUMN_NAME"], 5) == 'LEFT') {
                            if ($sonucUrun[$sutun["COLUMN_NAME"]] == '1') {
                                echo '<td><input type="checkbox" checked value="1" name="' . $sutun["COLUMN_NAME"] . '"/></td>';
                            } else {
                                echo '<td><input type="checkbox" value="1" name="' . $sutun["COLUMN_NAME"] . '"/></td>';
                            }
                            continue;
                        }
                        echo '<td><input type="text" name="' . $sutun["COLUMN_NAME"] . '" value = "' . $sonucUrun[$sutun["COLUMN_NAME"]] . '"></td>';
                    }
                }
                /*foreach ($dizi as $isim => $deger) {
                    if ($deger == '1') {
                        $sorgu = 'SELECT * FROM urun WHERE urun_no = "' . $urun_no . '"';
                        $gelen = $db->select($sorgu);
                        $sonuc = mysqli_fetch_assoc($gelen);
                        if ($isim == 'RIGHT') {
                            if ($sonuc['urun_' . $isim] == '1') {
                                echo '<td><input type="checkbox" checked value="1" name="urun_' . $isim . '"/></td>';
                            } else {
                                echo '<td><input type="checkbox" value="1" name="urun_' . $isim . '"/></td>';
                            }
                            continue;
                        } else if ($isim == 'LEFT') {
                            if ($sonuc['urun_' . $isim] == '1') {
                                echo '<td><input type="checkbox" checked value="1" name="urun_' . $isim . '"/></td>';
                            } else {
                                echo '<td><input type="checkbox" value="1" name="urun_' . $isim . '"/></td>';
                            }
                            continue;
                        }
                        echo '<td><input type="text" name="urun_' . $isim . '" value = "' . $sonuc['urun_' . $isim] . '"></td>';
                    }
                }*/
            }
        }
    }
}
