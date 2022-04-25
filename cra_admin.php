<?php
   error_reporting(0);
   require_once 'connection.php';
   session_start();
   $cra_id = $_SESSION['cra_id'];
   $v_id = $v_model = $v_num = $seat_cap = $rent = $avail ="";
   $rErr=false;
    if(!$_SESSION['cragency']){
        $errMsg=True;
        header('location: home.php');
        exit;
    }
    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['v_id'])){
            function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            $v_id = $_POST['v_id'];
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
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }else{
                if(!$rErr){
                    $sql = "UPDATE `cars` SET `v_model` = '$v_model',`v_num` = '$v_num',`seat_cap` = '$seat_cap',`rent` = '$rent' WHERE `cars`.`v_id` = '$v_id'";
                    $result = mysqli_query($conn, $sql);
                    if($result){
                        $_SESSION['submit']=true;
                    }
                }
            }
        }
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>CRA Administration</title>
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
                        <a href="home.php"><li>HOME</li></a>
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
    <div class="modal fade" id="updatemodal" tabindex="-1" aria-labelledby="updatemodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editmodalLabel">Update details of car</h5>
                </div>
                <div class="modal-body log-cont">
                <form method="post">
                    <input type="hidden" name="v_id" id="v_id">
                    <input type="text" placeholder="vehicle model" name="v_model" class="modal-input"><br>
                    <input type="text" placeholder="vehicle number" name="v_number" class="modal-input"><br>
                    <input list="seat" placeholder="Seat Capacity" name="seat_cap" class="modal-input"><br>
                    <datalist id="seat">
                        <option value="2">
                        <option value="3">
                        <option value="4">
                        <option value="5">
                        <option value="6">
                    </datalist>
                    <input type="text" placeholder="rent" name="rent" class="modal-input"><br>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="login-btn">Update Car Details</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="main-body">
        <div class="main-body-cont1">
            <div class="cont1-text">VIEW ALL YOUR CARS</div>
        </div>
        <?php
            if($rErr){
                echo '<div class="alert alert-danger" role="alert">
                            Could not submit details !! All Fields Required
                            <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times</span></span>
                        </div>';
            }
            if($_SESSION['submit']==true){
                echo '<div class="alert alert-primary" role="alert">
                            Details Submitted Successfully !!
                            <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times</span></span>
                        </div>';
            }
        ?>
        <div class="main-body-cont2">
            <?php
                $sql = "SELECT * FROM cars WHERE cra_id='$cra_id'";
                $result = mysqli_query($conn, $sql);
                $num = mysqli_num_rows($result);
                if($num>0){
                    while($row = mysqli_fetch_assoc($result)){
            ?>
            <div class="card">
                <h3 class="card-text1"><?= $row['v_model'] ?></h3>
                <div class="card-text2">
                    <div class="card-text2-s">Seat capacity: <b><?= $row['seat_cap'] ?></b></div>
                    <div class="card-text2-r">Rent per day: <b><?= $row['rent'] ?></b></div>
                </div>
                <button class='update btn btn-sm btn-primary rnt-btn' id="<?= $row['v_id'] ?>">Edit Details</button>
            </div>
            <?php
                }
                }
            ?>
        </div>
        <br>
        <br>
    </div>
    <div class="body-footer">
            <div class="body-footer-text">
                <div class="footer-text-left">CAR RENTAL AGENCY</div>
                <div class="footer-list-right">
                    <ul class="footer-top-list">
                        <a href="home.php"><li>HOME</li></a>
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
    <script>
        update = document.getElementsByClassName('update');
        Array.from(update).forEach((element)=>{
            element.addEventListener("click",(e)=>{
            v_id.value = e.target.id; 
            $('#updatemodal').modal('toggle');
            })
        })
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>