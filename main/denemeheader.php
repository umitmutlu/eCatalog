<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="/eCatalog/layout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <script src="https://kit.fontawesome.com/1e0e0aaef1.js" crossorigin="anonymous"></script>
</head>

<body>

    <div class="back-header">
        <div class="header">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="/eCatalog/index.php" style="font-family: Monoton; color: white;">AKKO</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                    <!-- <div class="container-fluid navs"> -->
                    <ul class="navbar-nav">
                        <li>
                            <a href="<?php $path ?>/eCatalog/index.php">
                                <i class="fas fa-home"></i> Ana Sayfa</a>
                        </li>
                        <li>
                            <a href="<?php $path ?>/eCatalog/search.php"><i class="fas fa-search"></i> Yeni Arama</a>
                        </li>

                        <li>
                            <?php
                            if ($user["username"] == "admin") { ?>
                                <a href="<?php $path ?>/eCatalog/login/register.php">
                                    <i class="fas fa-user-plus"></i> Yeni Üye</a>
                            <?php } ?>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="<?php $path ?>/eCatalog/Add/urunEkle.php">

                                <?php
                                if ($user) { ?>
                                    <i class="fas fa-plus-square"></i>
                                <?php echo 'Ürün Ekle';
                                } else { ?>
                                    <i class="fas fa-sign-in-alt"></i>
                                <?php echo 'Giriş Yap';
                                } ?></a>
                        </li>

                        <li>
                            <?php
                            if ($user) { ?>
                                <a href="<?php $path ?>/eCatalog/Edit/Duzenle.php"><i class="far fa-edit"></i> Düzenle / Sil</a><?php } ?>
                        </li>
                        <li>
                            <?php
                            if ($user) { ?>
                                <a href="<?php $path ?>/eCatalog/login/logout.php"><i class="fas fa-sign-out-alt"></i> Çıkış Yap<?php echo ' [' . $user["username"] . ']' ?> </a><?php } ?>
                        </li>

                        <li>
                            <a href="#"><i class="icon-tr-flag"></i> TR</a>
                        </li>
                        <li>
                            <a href="#"><i class="icon-eng-flag"></i> ENG</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</body>

</html>