<?php
   error_reporting(0);
   require_once 'connection.php';
   session_start();
   $cra_id = $_SESSION['cra_id'];
   $v_id = $v_model= $v_num= $cus_id= $no_days = $start_date = $t_rent= $cus_name= $sno= "";
   $rErr=false;
    if(!$_SESSION['cragency']){
        $errMsg=True;
        header('location: index.php');
        exit;
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
                            {echo '<div class="hello-text">Hello User</div>';}
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
            <div class="cont1-text">VIEW ALL BOOKED CARS</div>
        </div>
        <div class="main-body-cont3">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Serial No.</th>
                    <th scope="col">Car Model</th>
                    <th scope="col">Car Number</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">No. of Days</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">Total Rent</th>
                </tr>
            <thead>
            <tbody>
            <?php
                $sno=1;
                $sql = "SELECT * from cars where cra_id = '$cra_id'";
                $result = mysqli_query($conn, $sql);
                while($cars = mysqli_fetch_assoc($result)){
                    $v_id = $cars['v_id'];
                    $v_model = $cars['v_model'];
                    $v_num = $cars['v_num'];
                    $sql1 = "SELECT * from booking where v_id = '$v_id'";
                    $result1 = mysqli_query($conn, $sql1);
                    while($booking = mysqli_fetch_assoc($result1)){
                        $cus_id = $booking['cus_id'];
                        $no_days = $booking['no_days'];
                        $start_date = $booking['start_date'];
                        $t_rent = $booking['t_rent'];
                        $sql2 = "SELECT * from customers where cus_id = '$cus_id'";
                        $result2 = mysqli_query($conn, $sql2);
                        while($customer = mysqli_fetch_assoc($result2)){
                            $cus_name = $customer['name'];
                        ?>
                        <tr>
                            <th scope="row"><?= $sno ?></th>
                            <td><?= $v_model ?></td>
                            <td><?= $v_num ?></td>
                            <td><?= $cus_name ?></td>
                            <td><?= $no_days ?></td>
                            <td><?= $start_date ?></td>
                            <td><?= $t_rent ?></td>
                        </tr>

                        <?php
                        $sno= $sno+1;
                        }
                    }
                }
            ?>
            </tbody>
            </table>
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
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>