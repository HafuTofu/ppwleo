<?php
    include '../connect.php';
    include "./functions.php";

    if(!isset($_SESSION['login'])){
        $_SESSION['login'] = 'false';
    }else if($_SESSION['login'] === 'trueguess'){
        header('Location: dashboard.php');
    }else if($_SESSION['login'] === 'trueadmin'){
        header('Location: ../atmin/atmindashboard.html');
    }

    $source = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $generator = str_shuffle($source);
    $simplifier = substr($generator,0,6);    
    $captchacheck = "";
    $error = '';
    if(!empty($_POST)){
        $logses = $_POST['logses'];
        $password = $_POST['password'];
        $captchaguess = $_POST['captchaguess'];
        $captchacheck = $_POST['captchacheck'];
        $result1 = $conn->query("SELECT * FROM userdata WHERE Email = '$logses' AND status = 'active'");
        $result2 = $conn->query("SELECT * FROM userdata WHERE Username = '$logses' AND status = 'active'");
        
        if(!$result1 || !$result2){
            die("Connection failed: " . mysqli_connect_error());
        } 
            
        try{
            if($captchacheck != $captchaguess){
                throw new Exception('Kode Captcha Salah.');
            }else if($captchacheck == $captchaguess){ 
                $row1 = mysqli_fetch_assoc($result1);
                $row2 = mysqli_fetch_assoc($result2);
                if($row1!= null){
                    $mail = $row1['Email'];
                    $pass = $row1['Password'];
                    if($logses == $mail && $password == $pass){
                        if($row1['Username'] == "admin"){
                            $_SESSION['login'] = 'trueadmin';
                            $_SESSION['id'] = $row1['ID_user'];
                            $_SESSION['fotouser'] = $row2['fotouser'];
                            header("Location: ../atmin/admindash.php");
                        }else{
                            $_SESSION['login'] = 'trueguess';
                            $_SESSION['id'] = $row1['ID_user'];
                            $_SESSION['fotouser'] = $row2['fotouser'];
                            header("Location: dashboard.php");
                        }
                    } else {
                        throw new Exception('Password Salah.');
                    }
                }else if($row2!=null){
                    $username = $row2['Username'];
                    $pass = $row2['Password'];
                    if($logses == $username && $password == $pass){
                        if($row2['Username'] == "admin"){
                            $_SESSION['login'] = 'trueadmin';
                            $_SESSION['id'] = $row2['ID_user'];
                            $_SESSION['fotouser'] = $row2['fotouser'];
                            header("Location: ../atmin/admindash.php");
                        }else{
                            $_SESSION['login'] = 'trueguess';
                            $_SESSION['id'] = $row2['ID_user'];
                            $_SESSION['fotouser'] = $row2['fotouser'];
                            header("Location: dashboard.php");
                        }
                    } else {
                        throw new Exception('Password Salah.');
                    }
                }else{
                    throw new Exception('Email atau Username Tidak DitemuGan.');
                }
            }
            exit;
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
    <title>Login</title>
    <link rel="icon" href="./photo/ciG.png">
    <link rel="stylesheet" href="./css/style2.css">
</head>

<body class="flex items-center justify-center min-h-screen bg-[#fdf3e4]">
    <div class="relative w-full max-w-sm mx-auto">
        <!-- Background Logo -->
        <div class="absolute inset-0 flex items-center justify-center -z-10">
            <img src="./photo/ciG.png" alt="ciGCentral" class="h-32 w-96">
        </div>

        <!-- Login Card -->
        <div class="relative px-8 py-6 bg-[#ecdab7] bg-opacity-85 rounded-lg shadow-md text-left">
            <h1 class="mb-4 text-2xl font-bold text-center text-black">Login</h1>
            <p class="mb-6 text-sm text-center text-black">
                Belum punya akun? 
                <a href="register.php" class="text-red-600 hover:underline">Register</a>
            </p>

            <form method="POST" action="" class="space-y-6">
                <!-- Email/Username -->
                <div>
                    <label for="logses" class="mb-1 text-sm text-black">Email / Username:</label>
                    <input type="text" id="logses" name="logses" required 
                    value = "<?php echo $_POST['logses'] ?? '';?>"
                           class="w-full px-4 py-3 text-base text-white bg-opacity-50 border border-red-600 rounded-lg bg-red-950 focus:outline-none focus:ring-2 focus:ring-red-400">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="mb-1 text-sm text-black">Password:</label>
                    <input type="password" id="password" name="password" required 
                           class="w-full px-4 py-3 text-base text-white bg-opacity-50 border border-red-600 rounded-lg bg-red-950 focus:outline-none focus:ring-2 focus:ring-red-400">
                    <div class="flex items-center mt-2">
                        <input type="checkbox" id="togglePassword" class="mr-2" onclick="showpassword('password')">
                        <label for="togglePassword" class="text-sm text-black">Show Password</label>
                    </div>
                </div>

                <!-- Captcha -->
                <div>
                    <label for="captcha" class="mb-1 text-sm text-black select-none">Captcha: <strong><?php echo $simplifier;?></strong></label>
                    <input type="hidden" name = "captchacheck" value = <?php echo $simplifier;?>>
                    <input type="text" id="captcha" name="captchaguess" required 
                           class="w-full px-4 py-3 text-base text-white bg-opacity-50 border border-red-600 rounded-lg bg-red-950 focus:outline-none focus:ring-2 focus:ring-red-400">
                    <?php
                        if ($error){
                            echo '<p class="alert">'.$error.'</p>';
                        }
                    ?>
                </div>

                <!-- Login Button -->
                <button type="submit" class="w-full px-4 py-3 text-base font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                    Login
                </button>

                <!-- Google Login -->
                <button class="flex items-center justify-center w-full px-4 py-3 text-base bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                    <img src="./photo/google-logo.png" alt="Google Logo" class="w-5 h-5 mr-2">Login with Google
                </button>
            </form>
        </div>
    </div>

</body>
</html>