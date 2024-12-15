<?php
require 'sess.php';
$error = '';

if (!empty($_POST)) {
    try {
        $nama = ucfirst(strtolower($_POST['nama']));
        $query = "INSERT INTO kategori (nama_kategori) 
                  VALUES ('$nama')";
        mysqli_query($conn, $query);
        header("Location: adminew.php");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="../public/css/tambahproduk.css">
    <link rel="icon" href="./photo/ciG.png">
</head>

<body>
    <div class="container">
        <div class="register-box">
            <h2>Tambah Kategori</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <label style="padding-bottom: 0.5rem;" for="nama">Nama Kategori:</label>
                <input type="text" id="nama" name="nama" required value="<?php echo $_POST['nama'] ?? '' ?>">
                <?php
                if ($error) {
                    echo '<p class="alert">' . $error . '</p>';
                }
                ?>
                <button type="submit">TambahGan</button>
            </form>
        </div>
    </div>
</body>

</html>