<?php 
if ($_GET["error"] == "istenmeyenKarakter") {
      echo '<div class="alert alert-danger" role="alert">Şu karakterleri içermemelidir : & "</div>';
    } else if ($_GET["error"] == "veritabanındaVar") {
      echo '<div class="alert alert-danger" role="alert">Bu isimde bir kayıt zaten mevcut.</div>';
    } else if ($_GET["error"] == "dosyaSecilmedi") {
      echo '<div class="alert alert-danger" role="alert">Lütfen yüklemek istediğiniz dosyayı seçin.</div>';
    } else if ($_GET["error"] == "dosyaBoyutu") {
      echo '<div class="alert alert-danger" role="alert">Yüklemekte olduğunun dosya boyutu 3 MB\'den fazladır.</div>';
    } else if ($_GET["error"] == "dosyaTipi") {
      echo '<div class="alert alert-danger" role="alert">Yanlızca JPG/JPEG/PNG/BMP/GIF dosyaları gönderebilirsiniz.</div>';
    } else if ($_GET["error"] == "eklendi") {
      echo '<div class="alert alert-success alert-dismissible fade show">
      <strong>Başarılı!</strong>&nbsp;Ürün başarıyla eklendi.
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>';
    } else if ($_GET["error"] == "silindi") {
      echo '<div class="alert alert-success alert-dismissible fade show">
      <strong>Başarılı!</strong>&nbsp;Özellik başarıyla silindi.
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>';
    }
    else if ($_GET["error"] == "sifreYanlis") {
      echo '<div class="alert alert-danger alert-dismissible fade show">
      <strong>Uyarı!</strong>&nbsp;Parola yanlış.&nbsp;Lütfen yeniden deneyin.
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>';
    }
    else if ($_GET["error"] == "degistirildi") {
      echo '<div class="alert alert-success alert-dismissible fade show">
      <strong>Başarılı!</strong>&nbsp;Değişiklikler başarıyla kaydedildi.
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>';
    }
    else if ($_GET["error"] == "istenmeyenKarakterSutun") {
      echo '<div class="alert alert-danger alert-dismissible fade show">
      <strong>Uyarı!</strong>&nbsp;Lütfen özel karakter kullanmayınız
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>';
    }
?>