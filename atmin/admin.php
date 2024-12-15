<?php
  require "./sess.php";

  if(!isset($_SESSION['login'])){
    session_destroy();
    header('Location: login.php');
  }
  
  if($_SESSION['login'] === 'trueguess'){
    session_destroy();
    header('Location: dashboard.php');
  }

  if($_SESSION['login'] === 'false'){
    session_destroy();
    header('Location: login.php');
  }

  $query = "SELECT * FROM produk";
  $result = $conn->query($query);

  if (isset($_POST['hapus']) && !empty($_POST['hapus'])) {
    $prodid = $_POST['hapus'];
    $queryhapus = "DELETE FROM produk WHERE ID_produk = $prodid";
    mysqli_query($conn, $queryhapus);
    echo "<meta http-equiv='refresh' content='2; url=admin.php'>";
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Produk</title>
  <link rel="stylesheet" href="./css/admin.css">
  <link rel="icon" href="./photo/ciG.png">
</head>

<body>
<header>
    <div class="topnav">
        <a href="./admin.php"><img src="./photo/ciG.png" title="ciGCentral" class="logo"></a>
            
<!-- <! --belom ada form action nya di searchBar -->
        <div class="searchBar">
            <form action="">
                <input type="text" placeholder="Search...">
                <button type="submit"><img src="./photo/search.png" width="15px" height="15px"></button>
            </form>
        </div>
        
        <div class="user">
            <div class="dropdown">
                <img src="./photo/pfp.png" class="user-pic">
                    <div class="dropdown-content">
                        <a href="./pfpadmin.php">Profile</a>
                        <a href="#">Settings</a>
                        <a href="#">Help & Support</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
          </div>
    </div>
</header>

<div class="container">
  <div class="add-product-table">
    <a href="./tambahproduk.php"><button class="add-product-btn">+ Tambah Produk</button></a>
  </div>

  <div class="table">
    <div class="table-header">
      <div class="table-column">Foto Barang</div>
      <div class="table-column">Kategori</div>
      <div class="table-column">Stok</div>
      <div class="table-column">Harga</div>
      <div class="table-column">Fitur</div>
      <div class="table-column">Nama</div>
      <div class="table-column">Deskripsi</div>
    </div>
    
    <?php while ($row = $result->fetch_assoc()) { ?>
      <div class="table-row">
        <div class="table-column">
          <img src="./products/<?php echo $row['foto']; ?>" alt="Product Image" class="product-img">
        </div>
        <?php $rowid = $row['ID_kategori']; $querykat = "SELECT nama_kategori FROM kategori WHERE ID_kategori = $rowid"; $reskat = mysqli_query($conn, $querykat); $rowkat = mysqli_fetch_assoc($reskat)?>
        <div class="table-column"><?php echo $rowkat['nama_kategori']; ?></div>
        <div class="table-column"><?php echo $row['stok']; ?></div>
        <div class="table-column">Rp. <?php echo number_format($row['harga'], 2, ',', '.'); ?></div>
        <form action="" method="POST" class="table-column">  
            <input type="hidden" name="hapus" value="<?php echo $row['ID_produk']; ?>">
            <button type="submit" class="action-btn delete">🗑️</button> 
        </form>
        <div class="table-column" style=" justify-content:left; "><?php echo $row['nama']; ?></div>
        <div class="table-column" style="justify-content:left ;"><?php echo $row['deskripsi']; ?></div>
      </div>
    <?php } ?>
  </div>
</div>

</body>
</html>
