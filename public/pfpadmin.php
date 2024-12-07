<?php
require "./sess.php";
$ID_user = $_SESSION['id'];
$querry = "SELECT * FROM userdata WHERE ID_user = '$ID_user'";
$result = mysqli_query($conn, $querry);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="./css/pfp.css">
    <link rel="icon" href="./photo/ciG.png">
</head>
<body>

<!-- Header Section -->
<header>
    <div class="topnav">
        <a href="dashboard.php"><img src="./photo/ciG.png" title="ciGCentral" class="logo"></a>
            
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
                        <a href="pfpadmin.php">Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
            </div>
        </div>
    </div>
</header>

<div class="container">
    <!-- Profile Card -->
    <div class="profile-card">
        <img src="./photo/pfp.png" alt="Profile Picture">
        <h2><?php echo $row['Username'];?></h2>
    </div>

    <!-- Profile Information Form -->
    <div class="profile-info">
        <form action="profile.php" method="post">
            <h3>Profile Saya</h3>
            <div class="input-group">
                <label for="username">Username</label>
                <input type="username" id="username" value="<?php echo $row['Username'] ?? '';?>">
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" value="<?php echo $row['Email'] ?? '';?>">
            </div>
            <div class="button">
                <button type="submit">Simpan</button>
            </div>
    </div>
</div>

</body>
</html>