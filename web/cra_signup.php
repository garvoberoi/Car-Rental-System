<?php
    require_once 'connection.php';
    $passErr=$nameErr=$rErr=false;
    $name = $email = $pass = $cpass ="";
    if($_SERVER['REQUEST_METHOD']=='POST'){

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        if (empty($_POST['cra_name'])) {
            $rErr = true;
        } else {
            $name = test_input($_POST["cra_name"]);
        }
        if (empty($_POST['cra_email'])) {
            $rErr = true;
        } else {
            $email = test_input($_POST["cra_email"]);
        }
        if (empty($_POST['cra_password'])) {
            $rErr = true;
        } else {
            $pass = test_input($_POST["cra_password"]);
        }
        if (empty($_POST['cpassword'])) {
            $rErr = true;
        } else {
            $cpass = test_input($_POST["cpassword"]);
        }

        $sql = "SELECT * from cragency where name='$name'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        if($num == 1){
            $nameErr=true;
        }
        else if($pass!=$cpass){
            $passErr=true;
        }
        else{
            if(!$rErr){
                $sql = "INSERT INTO cragency (name, email, password, date) VALUES ('$name', '$email', '$pass', current_timestamp())";
                $result = mysqli_query($conn, $sql);
                if($result){
                    session_start();
                    header('location:login.php');
                }else{
                    echo "the record was not submited due to..".mysqli_error($conn);
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="carrent.css">
    <link href="http://fonts.googleapis.com/css?family=Josefin+Sans:300,400,500,600,700,800,900" rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/cb1e47f6f5.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="login-body">
        <?php
            if($passErr){
                echo 'Both password does not match !!!';
            }
            if($nameErr){
                echo 'Username not available !!!';
            }
            if($rErr){
                echo '* All details required *';
            }
        ?>
    <div>
        <div class="log-cont">
            <h2 class=login-text>CUSTOMER SIGN UP</h2>
            <form method="POST" class=form-login>
                <input type="text" placeholder="Username" name="cra_name" class="input-login">
                <input type="text" placeholder="Email" name="cra_email" class="input-login">
                <input type="password" placeholder="Password" name="cra_password" class="input-login">
                <input type="password" placeholder="Confirm Password" name="cpassword" class="input-login">
                <button type="submit" class="login-btn">SIGN UP</button>
            </form>
        </div>
        <div>
            <h4 class="login-text">Already a Customer?</h4>
            <a href="login.php" ><div class="login">LOG IN</div></a>
        </div>
        <div>
            <h4 class="login-text">Return to Home</h4>
            <a href="index.php" ><div class="login">HOME</div></a>
        </div>
    </div>
    </div>
</body>
</html>