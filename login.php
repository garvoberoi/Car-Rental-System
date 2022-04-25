<?php
    require_once 'connection.php';
    $passErr=$nameErr=$rpassErr=$rnameErr=false;
    $name = $pass = "";
    $cus_profile=$cra_profile=false;
    $_SESSION['loggedin'] = false;
    if($_SERVER['REQUEST_METHOD']=='POST'){
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        if (empty($_POST['name'])) {
            $rnameErr = "Name is required";
        } else {
            $name = test_input($_POST["name"]);
        }
          
        if (empty($_POST['password'])) {
            $rpassErr = "Email is required";
        } else {
            $pass = test_input($_POST['password']);
        }

        $sql = "SELECT * from customers where name='$name'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        if($num != 1){
            $cus_profile=false;
        }
        else{
            $cus_profile=true;
            $sql = "SELECT * from customers where name='$name' AND password='$pass'";
            $result = mysqli_query($conn, $sql);
            $num = mysqli_num_rows($result);
            $rows = mysqli_fetch_assoc($result);
            if($num == 1){
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $name;
                $_SESSION['customer'] = true;
                $_SESSION['cragency'] = false;
                $_SESSION['cus_id'] = $rows['cus_id'];
                header('location:home.php');
            }else{
                $passErr=true;
            }
        }
        if($cus_profile==false){
            $sql = "SELECT * from cragency where name='$name'";
            $result = mysqli_query($conn, $sql);
            $num = mysqli_num_rows($result);
            if($num != 1){
                $nameErr=true;
            }
            else{
                $cra_profile=true;
                $sql = "SELECT * from cragency where name='$name' AND password='$pass'";
                $result = mysqli_query($conn, $sql);
                $num = mysqli_num_rows($result);
                $rows = mysqli_fetch_assoc($result);
                if($num == 1){
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $name;
                    $_SESSION['cragency'] = true;
                    $_SESSION['customer'] = false;
                    $_SESSION['cra_id'] = $rows['cra_id'];
                    header('location:home.php');
                }else{
                    $passErr=true;
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Log In</title>
    <link rel="stylesheet" href="carrent.css">
    <link href="http://fonts.googleapis.com/css?family=Josefin+Sans:300,400,500,600,700,800,900" rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/cb1e47f6f5.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="login-body">
        <?php
            if($nameErr){
                echo 'Invalid Username !!!!';
            }
            if($passErr){
                echo 'Invalid Password!!!';
            }
            if($rpassErr){
                echo 'Password Required!!!';
            }
            if($rnameErr){
                echo 'Name required!!!';
            }
        ?>
    <div>
        <div class="log-cont">
            <h2 class=login-text>LOG IN</h2>
            <form method="POST" class=form-login>
                <input type="text" placeholder="username" name="name" class=input-login >
                <input type="password" placeholder="Password" name="password" class=input-login >
                <button type="submit" class="login-btn">LOG IN</button>
            </form>
        </div>
        <div>
            <h3 class="login-text">NEW HERE?</h3>
                <div class="signup-dropdown">
                    <button class="footer-signupbtn">SIGN UP
                        <i class="fa fa-angle-down drop" ></i>
                    </button>
                    <div class="signup-dropdown-content">
                        <a href="cus_signup.php">CUSTOMER</a>
                        <a href="cra_signup.php">CAR RENTAL AGENCY</a>
                    </div>
                </div>
        </div>
        <div>
            <h4 class="login-text">Return to Home</h4>
            <a href="home.php" ><div class="login">HOME</div></a>
        </div>
    </div>
    </div>
</body>
</html>