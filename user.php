<?php
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);

    if(!isset($_SESSION['username'])){
        header("Location:login.php");
        exit();
    }
    if(!isset($_COOKIE['last_user']) || $_COOKIE['last_user']!=$_SESSION['username']){
        header("Location:login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Booking</title>
    <!-- <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap/style.css"> -->

</head>
<style>
    *{
        font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }
    .header{
        margin:-130px -8px 0px -8px;
        background-color:#A31D1D;
        padding:7px 0px;
        position:fixed;
        width:100%;
        display:flex;
    }
    .header h1{
        color:white;
        padding-left:10px;
        margin-right:640px;
    }
    form{
        margin-left: 26px;
    }
    input:hover{
        background-color:#E5D0AC;
        box-shadow:2px 2px 2px 2px #A31D1D;
    }
    input{
        padding:15px;
        margin-top:15px;
        border:none;
        border-radius:10px;
        font-size: 16px;
        font-weight:bold;
    }
    .content{
        margin-top:130px;
    }
    
    th{
        background-color:#E5D0AC;
    }
    td{
        text-align: center;
    }
    table{
        margin-bottom: 65px;
        width:100%;
    }
    .footer{
        position: fixed;
        bottom: 12px;
        left:0px;
        width:100%;
        padding-bottom:10px;
        margin-bottom: -22px;
        background-color:rgb(254, 196, 95);
    }
</style>
<body>
    <div class="header">
        <h1>Welcome <?php echo $_SESSION['username']; ?>!</h1>
        <form action="userView.php" method="POST">
            <input type="submit" name = "view" value = "View Events">
        </form>
        <form action="userBooked.php" method="POST">
            <input type="submit" name = "booked" value = "Booked Events">
        </form>
        <form action="userSaved.php" method="POST">
            <input type="submit" name = "saved" value = "Saved Events">
        </form>
        <form action="user.php" method="POST">
            <input type="submit" name = "logout" value = "Log out">
        </form>
    </div>
    <div class="content">
        
    </div>
    <div class="footer">
        <center><p>Copyrights &copy; <?php echo date('Y',strtotime('now')); ?> </p></center>
    </div>
</body>
</html>


<?php
    
    if(isset($_POST['logout'])){
        session_unset();
        session_destroy();
        header("Location:index.php");
    }

    include_once 'config/config.php';

    $user_name= $_SESSION['username'];

    echo "<center><img src='assets/images/event.png' width=600px height=400px style='border-radius:20px;'></center>"; 
    
    $conn = null;
?>