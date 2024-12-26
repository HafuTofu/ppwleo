<?php
include "../connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idproduk = $_POST['idproduk'];

    if (isset($_POST['hapus'])) {
        $iddisc = $_POST['hapus'];
        $searchQuery = "SELECT ID_produk,ID_discount FROM produk WHERE ID_discount = $iddisc";
        $stmtSearch = $conn->query($searchQuery);
        while ($row = $stmtSearch->fetch_assoc()) {
            $id = $row["ID_produk"];
            $updateQuery = "UPDATE produk SET ID_discount = 0 WHERE ID_produk = $id";
            mysqli_query($conn, $updateQuery);  
        }
        $deleteQuery = "DELETE FROM discounts WHERE ID_discount = ?";
        $stmtDel = $conn->prepare($deleteQuery);
        $stmtDel->bind_param("i", $iddisc);
        $stmtDel->execute();
        header("Location: ../view/discount.php"); 
        exit();
    }

    $disc = $_POST['disc'];

    if (!empty($idproduk) && is_numeric($disc) && $disc > 0 && $disc < 100) {
        // Calculate discount price
        $query = "SELECT harga FROM produk WHERE ID_produk = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idproduk);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $harga = $row['harga'];
            $discountPrice = $harga - ($harga * $disc / 100);

            // Insert or update the discount
            $checkQuery = "SELECT * FROM (discounts NATURAL JOIN produk) WHERE ID_produk = ?";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param("i", $idproduk);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                $iddisc = $_POST['iddisc'];
                $updateQuery = "UPDATE discounts SET amount = ?, discountprice = ? WHERE ID_discount = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("ddi", $disc, $discountPrice, $iddisc);
                $updateStmt->execute();
            } else {
                $insertQuery = "INSERT INTO discounts (amount,discountprice) VALUES (?,?)";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bind_param("id", $disc, $discountPrice);
                $insertStmt->execute();

                $updateQuery = "UPDATE produk SET ID_discount = (SELECT ID_discount FROM discounts ORDER BY ID_discount DESC LIMIT 1) WHERE ID_produk = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("i", $idproduk);
                $updateStmt->execute();
            } 
        }
        header("Location: ../view/discount.php"); 
        exit();
    } else {
        echo "Invalid input. Please try again.";
    }
}
?>
