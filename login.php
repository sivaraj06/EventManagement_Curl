<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- <link rel="stylesheet" href="assets/css/login.css"> -->
</head>
<style>
    *{
    font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }
    body{
        background-image: url('assets/images/blur.png');
        background-repeat: no-repeat;
        background-size: 100%;
    }
    .container{
        height: 390px;
        width: 421px;
        background-color:#E16331;
        padding-top:40px;
        border-radius: 10px;
        margin:139px auto 200px auto;
        box-sizing: border-box;
        box-shadow: 5px 4px 3px #ccc;
    }
    .container input{
        border-radius: 20px;
        margin:10px;
        padding: 10px 50px 10px 10px;
        font-weight: bold;
        font-size: 18px;
    }
    .container button{
        border-radius: 20px;
        margin-top:20px;
        padding: 10px 20px;
        font-size: 15px;
        font-weight: bold;
        margin-bottom: 10px;
        border:none;
    }
    .container button:hover{
        background-color: rgb(117, 3, 3);
        color:white;
        font-weight: bold;
    }
    .container a{
        color: #A01712;
        font-weight: bold;
        font-size: 16px;
        text-decoration: none;
    }
    .container a:hover{
        color:white;
    }
    .message{
        display:inline;
        position:absolute;
        top: 23px;
        right: 13px;
        color:red;
        background-color:white;
        padding:7px;
        border-radius:7px;
    }
</style>
<body>
    <center>
    <div class="container">
        <img src="assets/images/user.png" width="100px" style="padding: 10px 0px;"><br>
        <form action="login.php" method="POST">
            <input type="text" placeholder="username" name="name" required>
            <input type="password" placeholder="password" name="password" required><br>
            <button type="submit" name="login" value ='LOGIN'>LOGIN</button><br>
            <a href="register.php">New User?</a>
        </form>
    </div>
    </center>
</body>
</html>

<?php

    session_start();
    include('curlfunc.php');
    if(isset($_POST['login'])){
        $_SESSION['username'] = $_POST['name'];

        $username = $_POST['name'];
        $password = $_POST['password'];
        $action  = strtolower($_POST['login']);
        $postData = [
            'username' => $username,
            'password' => $password,
            'action' => $action
        ];

        //call the curl fucntion 
        $row = curlHit("http://localhost/phppractice/eventManagement_v5_curl/server.php",$postData);

        if($row["role"]=="admin"){
            $_SESSION['register_id'] = $row['register_id'];
            setcookie('last_admin',$username,time()+(8600*30),'/phppractice/eventManagement_v5_curl/');
            header("Location:index.php");
        }
        elseif($row['role']=='user'){
            $_SESSION['register_id'] = $row['register_id'];
            setcookie('last_user',$username,time()+(8600*30),'/phppractice/eventManagement_v5_curl/');
            header("Location:user.php");
        }else{
            header("Location:register.php");
        }
    }
?>