<?php
    error_reporting(0);
    require_once 'connection.php';
    session_start();
    $veh_model = $veh_num = $seat_cap = $rent = $cra_id="";
    $rErr=false;
    $_SESSION['submit']=false;
    if(!$_SESSION['cragency']){
        header('location: index.php');
        exit;
    }
    if($_SERVER['REQUEST_METHOD']=='POST'){
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        if (empty($_POST['v_model'])) {
            $rErr = true;
        } else {
            $v_model = test_input($_POST["v_model"]);
        }
        if (empty($_POST['v_number'])) {
            $rErr = true;
        } else {
            $v_num = test_input($_POST["v_number"]);
        }
        if (empty($_POST['seat_cap'])) {
            $rErr = true;
        } else {
            $seat_cap = test_input($_POST["seat_cap"]);
        }
        if (empty($_POST['rent'])) {
            $rErr = true;
        } else {
            $rent = test_input($_POST["rent"]);
        }
        $cra_id = $_SESSION['cra_id'];
    }
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }else{
        if(!$rErr){
            $sql = "INSERT INTO cars (v_model, v_num, seat_cap, rent, cra_id, date) VALUES ('$v_model', '$v_num', '$seat_cap', '$rent', '$cra_id', current_timestamp())";
            $result = mysqli_query($conn, $sql);
            if($result){
                $_SESSION['submit']=true;
                header('location:cra_admin.php');
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register Your Car</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="carrent.css">
    <link href="http://fonts.googleapis.com/css?family=Josefin+Sans:300,400,500,600,700,800,900" rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/cb1e47f6f5.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="top">
        <div class="top_text">
            
            <div class="lower-bar">
                <div id="text1">CAR RENTAL AGENCY</div>
                <?php
                    if(isset($_SESSION['loggedin'])){
                        if($_SESSION['cragency']){
                            echo '<a href="cra_admin.php">
                            <div class="lower-bar-right signup-dropdown">
                            <button class="cra-btn">Car Agency Administration
                            <i class="fa fa-angle-down drop" ></i>
                            </button>
                            <div class="signup-dropdown-content">
                                <a href="register_car.php">Add New Car</a>
                                <a href="bookedcar.php">View All Booked Cars</a>
                            </div>
                            </div></a>';
                        }
                    }
                ?>
            </div>
            <div class="top_bar">
                <div class="top_bar_left">
                    <ul class="top_list">
                        <a href="index.php"><li>HOME</li></a>
                        <a href="login.php"><li>LOG IN</li></a>
                        <li class="signup-dropdown">
                        <button class="signupbtn">SIGN UP
                        <i class="fa fa-angle-down drop" ></i>
                        </button>
                        <div class="signup-dropdown-content">
                            <a href="cus_signup.php">CUSTOMER</a>
                            <a href="cra_signup.php">CAR RENTAL AGENCY</a>
                        </div>
                        </li>
                    </ul>
                </div>
                <div class="top_bar_right">
                    <?php
                        if(!isset($_SESSION['loggedin']))
                            {echo '<div class="hello-text">Hello user</div>';}
                        else{
                            echo '<div class="hello-text">Hello '.$_SESSION['username'].'</div>';
                        }
                    ?>
                    <span class="verticle"></span>
                    <a href="logout.php"><span class="logout">LOG OUT</span></a>
                </div>
            </div>
        </div>
    </div>
    <div class="main-body">
        <div class="main-body-cont1">
            <div class="cont1-text">Register Your Car</div>
        </div>
        <?php
            if($rErr){
                echo '<div class="alert alert-danger" role="alert">
                            Could not submit details !! All Fields Required
                            <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times</span></span>
                        </div>';
            }
        ?>
        <div class="main-body-cont3">
            <div class="log-cont">
                <form method="POST" class=form-login>
                    <input type="text" placeholder="vehicle model" name="v_model" class="input-login">
                    <input type="text" placeholder="vehicle number" name="v_number" class="input-login">
                    <input list="seat" placeholder="Seat Capacity" name="seat_cap" class="input-login">
                    <datalist id="seat">
                        <option value="2">
                        <option value="3">
                        <option value="4">
                        <option value="5">
                        <option value="6">
                    </datalist>
                    <input type="text" placeholder="rent" name="rent" class="input-login">
                    <button type="submit" class="login-btn">Add a Car</button>
                </form>
            </div>
        </div>
        <br>
        <br>
    </div>
    <div class="body-footer">
            <div class="body-footer-text">
                <div class="footer-text-left">CAR RENTAL AGENCY</div>
                <div class="footer-list-right">
                    <ul class="footer-top-list">
                        <a href="index.php"><li>HOME</li></a>
                        <a href="login.php"><li>LOG IN</li></a>
                        <li class="signup-dropdown">
                        <button class="footer-signupbtn">SIGN UP
                        <i class="fa fa-angle-down drop" ></i>
                        </button>
                        <div class="signup-dropdown-content">
                            <a href="cus_signup.php">CUSTOMER</a>
                            <a href="cra_signup.php">CAR RENTAL AGENCY</a>
                        </div>
                        </li>
                        <a href="logout.php"><li>LOG OUT</li></a>
                    </ul>
                </div>
            </div>
            <div class="copyright">2022 Copyright (C) Garv Oberoi All rights reserved </div>
    </div>
</body>
</html>