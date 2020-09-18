<?php include_once("./config.php");
$db = new Db();
$user = $_SESSION["login_userAKKO"];
echo 'Permmision Delete =>' . $user["permission_delete"] . '<br>';
?>

<?php
/*include_once("config.php");
$db = new Db();

$sorgu = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME="urun_ozellikleri";';
$gelen = $db->select($sorgu);
while ($sonuc = mysqli_fetch_assoc($gelen)) {
    echo $sonuc["COLUMN_NAME"].'<br>';
}*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" type="text/css" href="/eCatalog/layout.css">
  <script src="https://kit.fontawesome.com/1e0e0aaef1.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

</head>

<body>
  <?php
  /*$sorgu = "INSERT INTO pozisyon (pozisyon_ad,siralama) VALUES ('siralama_56_38','$array')";
  $db->query($sorgu);*/
  $sorgu = "SELECT * FROM pozisyon WHERE pozisyon_ad='siralama_56_38'";
  $gelen = $db->select($sorgu);
  $sonuc = mysqli_fetch_assoc($gelen);
  echo $sonuc["siralama"] . '<br>';
  $dizi = explode("\"", $sonuc["siralama"]);

  echo "<pre>";
  print_r($dizi);
  echo "</pre>";
  for ($i = 0; $i < count($dizi); $i++) {
    if ($i % 2 == 1) {
      echo $dizi[$i] . '<br>';
    }
  }

  $dizi2 = explode("\"", $dizi[0]);

  echo "<pre>";
  print_r($dizi2);
  echo "</pre>";

  /*foreach ($dizi as $key => $value) {
    # code...
  }*/


  for ($i = 0; $i < count($dizi); $i++) {
    if ($i % 2 == 1) {
      if ($dizi[$i] == "BD") {
        unset($dizi[$i]);
        unset($dizi[$i + 1]);
      }
      echo $dizi[$i] . '<br>';
    }
  }
  $dizi = array_values($dizi);
  $length = count($dizi);
  echo 'length -> ' . $length;
  $dizi[$length - 1] = ":99}";
  $new_array = "";
  foreach ($dizi as $key => $value) {
    if ($key % 2 == 1) {
      $new_array = $new_array . '"' . $value . '"';
    } else {
      $new_array = $new_array .  $value;
    }
  }

  echo "<pre>";
  print_r($new_array);
  echo "</pre>";

  $meyve = ["Elma", "Kiraz", "Kayısı", "Şeftali"];

  unset($meyve[2]);

  echo "<pre>";
  print_r($meyve);
  echo "</pre>";

  $dizi = array_values($meyve);
  echo "<pre>";
  print_r($dizi);
  echo "</pre>";

  echo '--------------------------------------------------------------';

  $sorgu = 'SELECT * FROM yazilimlar';
  $gelen = $db->select($sorgu);
  while ($yazilimlar = mysqli_fetch_assoc($gelen)) {
    echo $sonuc;
  }

  ?>






</body>

</html>