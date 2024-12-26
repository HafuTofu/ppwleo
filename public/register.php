<?php
//udahan
    include 'functions.php';
    include '../connect.php';

    if($_SESSION['login'] === 'trueguess'){
        header('Location: dashboard.php');
    }else if($_SESSION['login'] === 'trueadmin'){
        header('Location: admin.php');
    }

    $error = '';
    if(!empty($_POST)){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordcheck = $_POST['passwordcheck'];
        $gender = $_POST['gender'];
        $filename = '';
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $row = mysqli_fetch_assoc(qsearch('userdata', 'email',$email,$conn));
        try{
            if($row!=null){
                throw new Exception('Username atau Email telah digunakan.');
            }else if($passwordcheck != $password){
                throw new Exception('Password Konfirmasi Berbeda Dengan Password Yang Dimasukkan.');
            }else if($passwordcheck == $password){
                if (isset($_FILES['inputfoto']) && $_FILES['inputfoto']['error'] == 0) {
                    if ($_FILES['inputfoto']['size'] <= 0) {
                        throw new Exception('The uploaded file is empty.');
                    }
                    
                    $tipe = $finfo->file($_FILES['inputfoto']['tmp_name']);
                    if (!in_array($tipe, ['image/png', 'image/jpeg', 'image/jpg'])) {
                        throw new Exception('Unsupported file format. Please upload a PNG or JPEG image.');
                    }
                    
                    $filename = md5(random_bytes(1)) . '.' . pathinfo($_FILES['inputfoto']['name'], PATHINFO_EXTENSION);
                    $filepath = './photouser/' . $filename;
                    move_uploaded_file($_FILES['inputfoto']['tmp_name'], $filepath);
                }
                
                if($filename != ''){

                    $query = "INSERT INTO userdata (Username, Email, Password, gender, fotouser) 
                VALUES ('$username', '$email', '$password', '$gender', '$filename')";
                }else  {
                    $query = "INSERT INTO userdata (Username, Email, Password, gender) 
                VALUES ('$username', '$email', '$password', '$gender')";
                }
                mysqli_query($conn, $query);
                header("Location: login.php");
            }exit;
        }catch(Exception $e){
            $error = $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./css/register.css">
    <link rel="icon" href="./photo/ciG.png">
</head>
<body>
    <div class="container">
        <div class="register-box">
            <h2>Register</h2>
            <p>Sudah mempunyai akun? <a href="./login.php">Login</a></p>
            <?php
            if ($error){
                echo '<p class="alert">'.$error.'</p>';
            }
            ?>
            <form method="POST" action="" enctype="multipart/form-data">
                <label style="padding-bottom: 0.5rem;"for="inputfoto">MasuGan Foto:</label>
                <input style="margin-bottom: 1rem;" type="file" name="inputfoto" id="inputfoto"
                accept="image/png,image/jpeg">
                
                <label style="padding-bottom: 0.5rem;"for="username">Username:</label>
                <input style="margin-bottom: 1rem;"type="text" id="username" name="username" required
                value="<?php echo $_POST['username'] ?? '';?>">
                
                <label style="padding-bottom: 0.5rem;" for="email">Email:</label>
                <input style="margin-bottom: 1rem;" type="email" id="email" name="email" required
                value="<?php echo $_POST['email'] ?? '';?>">
                
                <label for = "gender" style="padding-bottom: 0.5rem;">Gender:</label>
                <select style="margin-bottom:1rem;" id="gender" name="gender" required>
                    <option value="Rather not say">Rather not say</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>

                <label style="padding-bottom: 0.5rem;" for="password">Password:</label>
                <input style="margin-bottom: 0.3rem;" type="password" id="password" name="password" required
                value="<?php echo $_POST['Password'] ?? '';?>" style="margin-bottom: 0;">  
                <div class="checkbox-container" style="margin-bottom: 1rem;">
                    <input type="checkbox" id="togglePassword" style="margin-right: 0.3rem;" onclick="showpassword('password')"> 
                    <label for="togglePassword" >Show Password</label>
                </div>

                <label for="passwordcheck" style="padding-bottom: 0.5rem;">Confirm Password:</label>
                <input style="margin-bottom: 0.3rem;" type="password" id="passwordcheck" name="passwordcheck">
                <div class="checkbox-container" style = "margin-bottom: 1rem ;">
                    <input type="checkbox" id="togglePassword" style="margin-right: 0.3rem;" onclick="showpassword('passwordcheck')"> 
                    <label for="togglePassword">Show Password</label>
                </div>
                <button type="submit">Register</button>
            </form>
        </div>
    </div>
</body>
</html>