<?php
    session_start();
    $dateErr=false;
    $end_date =$start_date=$start_date1=$end_date1="";
    $available = true;
    $submit=$veh_num="";
    require_once 'connection.php';
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $submit=false;
        $v_id = $_POST['v_id'];
        $no_days = $_POST['no_days'];
        $start_date = $_POST['start_date'];
        $cus_id = $_SESSION['cus_id'];
        $end_date = date('Y-m-d', strtotime($start_date. ' + '.$no_days.'days'));

        $rent = "SELECT * from cars where v_id = '$v_id'";
        $result = mysqli_query($conn, $rent);
        $rows = mysqli_fetch_assoc($result);
        $t_rent = $no_days * $rows['rent'];
        
        $sql1 = "SELECT * from booking where v_id = '$v_id'";
        $result1 = mysqli_query($conn, $sql1);
        while($already = mysqli_fetch_assoc($result1)){
            $start_date1 = $already['start_date'];
            $no_days1 = $already['no_days'];
            $end_date1 = date('Y-m-d', strtotime($start_date1. ' + '.$no_days1.'days'));
            if($start_date < $start_date1){
                if($end_date < $start_date1){
                    $available=true;
                }
                else{
                    $available=false;
                    break;
                }
            }
            else{
                if($start_date > $end_date1){
                    $available=true;
                }
                else{
                    $available=false;
                    break;
                }
            }
            
        }

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }else{
            if($available){
                $sql = "INSERT INTO booking (no_days, start_date, t_rent, v_id, cus_id, date) VALUES ('$no_days', '$start_date', '$t_rent', '$v_id', '$cus_id', current_timestamp())";
                $result = mysqli_query($conn, $sql);
                if($result){
                    $submit=true;
                    $sql1 = "UPDATE cars SET avail = 0 WHERE v_id='$v_id'";
                    $result = mysqli_query($conn, $sql1);
                } else{
                    echo "the record was not submited due to..".mysqli_error($conn);
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
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
                            echo '<a href="cra_admin.php"><div class="lower-bar-right">Car Agency Administration</div></a>';
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
    <div class="modal fade" id="bookcar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Enter Booking Details</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                <form method="post">
                                    <input type="hidden" name="v_id" id="v_id">
                                    <input list="days" placeholder="No. of days" name="no_days">
                                    <datalist id="days">
                                        <option value="1">
                                        <option value="2">
                                        <option value="3">
                                        <option value="4">
                                        <option value="5">
                                        <option value="6">
                                        <option value="7">
                                    </datalist>
                                    <input type="date" name="start_date">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Book</button>
                                </div>
                                </form>
                                </div>
        </div>
    </div>
    
    <div class="main-body">
        <div class="main-body-cont1">
            <div class="cont1-text">RENT YOUR CAR</div>
        </div>
        <?php
            if(!$available){
                echo '<div class="alert alert-danger" role="alert">
                            <b>Booking failed !!</b> Choose another date or another car.
                            <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times</span></span>
                        </div>';
            }
            if($submit){
                echo '<div class="alert alert-primary" role="alert">
                            <b>Booking Successful !!</b>
                            <span type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times</span></span>
                        </div>';
            }
        ?>
        <div class="main-body-cont2">
            <?php
                $sql = "SELECT * FROM cars";
                $result = mysqli_query($conn, $sql);
                $num = mysqli_num_rows($result);
                if($num>0){
                    while($row = mysqli_fetch_assoc($result)){
                        $veh_num = strtoupper($row['v_num']);
            ?>
            <div class="card">
                <h3 class="card-text1"><?= $row['v_model'] ?></h3>
                <div class="card-text2">
                    <div class="card-text2-s">Seat capacity: <b><?= $row['seat_cap'] ?></b></div>
                    <div class="card-text2-r">Rent per day: <b><?= $row['rent'] ?></b></div>
                </div>
                <div class="card-text3">Car Number:<b> <?= $veh_num?></b></div>
                <button class='book btn btn-sm btn-primary rnt-btn' id="<?= $row['v_id'] ?>">Rent this car</button>
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
    <?php
        if(isset($_SESSION['loggedin'])){
            if($_SESSION['customer']){
                echo "<script>
                edits = document.getElementsByClassName('book');
                Array.from(edits).forEach((element)=>{
                    element.addEventListener('click',(e)=>{
                    v_id.value = e.target.id; 
                    $('#bookcar').modal('toggle');
                    })
                })
                </script>";
            }
            else if($_SESSION['cragency']){
                echo "<script>
                edits = document.getElementsByClassName('book');
                Array.from(edits).forEach((element)=>{
                    element.addEventListener('click',(e)=>{
                        alert('Log In as a Customer to book car');
                    })
                })
                </script>";
            }
        }
        else{
            echo "<script>
            edits = document.getElementsByClassName('book');
            Array.from(edits).forEach((element)=>{
                element.addEventListener('click',(e)=>{
                    document.location.href = 'login.php';
                    })
                
            })
            </script>";
        }
    ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>