<!doctype html>

<body style="align-items:center; text-align:center;">
  <?php
  include $_SERVER['DOCUMENT_ROOT'] . '/eCatalog/footer.html';
  include './anaKategori.html';
  ?>
  <!-- <link rel="stylesheet" type="text/css" href="/eCatalog/allcss.css"> -->

  <head>
    <meta charset="utf-8">
    <title>Ana Sayfa</title>
    <link rel="icon" type="image/png" href="/favicon.ico" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/1e0e0aaef1.js" crossorigin="anonymous"></script>
  </head>

</body>

<hr style="margin: 0 auto;max-width:50%;" />

<div class="search-form">
  <form action="search.php" method="POST" class="form-inline my-2 my-lg-0">
    <input name="aramaIsim" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
    <select name="kategori" class="kategoriler">
      <option selected value="urun">Ürüne Göre</option>
      <option value="baslik">Başlığa Göre</option>
    </select>
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
  </form>
</div>

<style>
  .form-inline {
    justify-content: center;
    text-align: center;
  }

  .form-inline .form-control {
    display: inline-block;
    width: 400px;
    vertical-align: middle;
  }

  .btn-secondary {
    background-color: #2a6fac;
    border-color: transparent;
  }

  .btn-secondary:not(:disabled):not(.disabled).active,
  .btn-secondary:not(:disabled):not(.disabled):active,
  .show>.btn-secondary.dropdown-toggle {
    color: #fff;
    background-color: #2a6fac;
    border-color: transparent;
  }

  .btn-secondary.focus,
  .btn-secondary:focus {
    box-shadow: 0 0 0 0.2rem tomato;
    background-color: tomato;
  }

  .btn-secondary:hover {
    background-color: purple;
  }

  .btn-outline-success {
    color: white;
    background-color: orange;
    border-color: orange;
  }

  .btn-outline-success:hover {
    color: #fff;
    background-color: #f5bd07;
    border-color: #f5bd07;
  }
</style>

</html>