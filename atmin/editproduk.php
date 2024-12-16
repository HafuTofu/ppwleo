<?php
require '../public/sess.php';
$error = '';
$categories = [];
$result = $conn->query("SELECT nama_kategori FROM kategori");
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['nama_kategori'];
    }

$idprod = $_GET['idprod'];
$q = "SELECT * FROM produk WHERE ID_produk = $idprod";
$r = $conn->query($q);
$row = $r->fetch_assoc();

if (!empty($_POST)) {
    try {
        $nama = $_POST['nama'];
        $kategori = $_POST['kategori'];
        $stok = $_POST['stok'];
        $harga = $_POST['harga'];
        $deskripsi = $_POST['deskripsi'];
        $terjual = 0;
        $filename = '';
        $finfo = new finfo(FILEINFO_MIME_TYPE);

        $queryidkat = "SELECT ID_kategori FROM kategori WHERE nama_kategori = '$kategori'";
        $resultidkat = mysqli_query($conn, $queryidkat);
        $rowidkat = mysqli_fetch_assoc($resultidkat);
        $idkat = $rowidkat['ID_kategori'];

        if (isset($_FILES['inputfoto']) && $_FILES['inputfoto']['error'] == 0) {
            if ($_FILES['inputfoto']['size'] <= 0) {
                throw new Exception('The uploaded file is empty.');
            }

            $tipe = $finfo->file($_FILES['inputfoto']['tmp_name']);
            if (!in_array($tipe, ['image/png', 'image/jpeg', 'image/jpg'])) {
                throw new Exception('Unsupported file format. Please upload a PNG or JPEG image.');
            }

            $filename = md5(random_bytes(1)) . '.' . pathinfo($_FILES['inputfoto']['name'], PATHINFO_EXTENSION);
            $filepath = '../public/products/' . $filename;

        }

        $oldphoto = './products/' . $row['foto'];
        unlink($oldphoto);
        $query = "UPDATE produk SET nama = ? , deskripsi = ? , harga = ? , stok = ? , terjual = ? , ID_kategori = ? , foto = ? 
                  WHERE ID_produk = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssiiiisi", $nama,$deskripsi,$harga, $stok,$terjual ,$idkat,$filename,$idprod);
        move_uploaded_file($_FILES['inputfoto']['tmp_name'], $filepath);
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
    <link rel="icon" href="../public/photo/ciG.png">
</head>
<body>
    <div class="container">
        <div class="register-box">
            <h2>Edit Produk</h2>
            <?php
            if ($error){
                echo '<p class="alert">'.$error.'</p>';
            }
            ?>
            <form method="POST" action="" enctype="multipart/form-data">
                <label style="padding-bottom: 0.5rem;"for="inputfoto">MasuGan Foto:</label>
                <input style="margin-bottom: 1rem;" type="file" name="inputfoto"
                accept="image/png,image/jpeg,image/jpg">

                <label style="padding-bottom: 0.5rem;" for="nama">Nama Barang:</label>
                <input type="text" id="nama" name="nama" required
                value="<?php echo $_POST['nama'] ?? $row['nama']?>">
                
                <label for = "kategori" style="padding-bottom: 0.5rem;">Kategori:</label>
                <select id="kategori" name="kategori" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo strtolower($category); ?>"><?php echo $category; ?></option>
                    <?php } ?>
                </select>
                
                <label style="padding-bottom: 0.5rem;" for="stok">Stok Barang:</label>
                <input type="number" id="stok" name="stok" min='1' required
                value="<?php echo $_POST['inputnumber'] ?? $row['stok']?>">

                <label for="harga" style="padding-bottom: 0.5rem;">Harga:</label>
                <input   type="number" id="harga" name="harga" min='1000' required
                value = "<?php echo $_POST['harga'] ?? $row['harga'] ?>">

                <label for="deskripsi" style="padding-bottom: 0.5rem;">Deskripsi:</label>
                <textarea  style="max-width:100% !important; min-width:100%;"  id="deskripsi" name="deskripsi" required
                value = "<?php echo $_POST['deskripsi'] ?? $row['deskripsi'] ?>"><?php echo $_POST['deskripsi'] ?? $row['deskripsi'] ?></textarea>

                <button type="submit">Update</button>
            </form>
        </div>
    </div>
</body>
</html>