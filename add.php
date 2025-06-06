<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add details</title>
    <!-- <link rel="stylesheet" href="assets/css/add.css"> -->
</head>
<style>
    *{
    font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }
    .header{
        margin:-140px -8px 0px -8px;
        background-color:#A31D1D;
        padding:7px 0px;
        position:fixed;
        width:100%;
        display:flex;
        z-index:4;
    }
    .header h1{
        color:rgb(255, 255, 255);
        padding-left:10px;
        margin-right:890px;
    }
    .add{
        /* margin-bottom:130px; */
        margin-top:140px;
        background-color: #F6C794;
        padding:50px 0px;
        border-radius:30px;
    }
    h2{
        position:absolute;
        top: 147px;
        left: 1192px;
    }
    .input-feild label{
        font-weight:bold;
        position: absolute;
        left: 235px;
        margin-top: 16px;
        z-index:2;
        font-size:18px;
    }
    .input-feild input{
        width:50%;
        margin:7px 0px 5px 0px;
        padding:10px;
        box-sizing:border-box;
        border-radius: 10px;
        font-size:18px;
    }
    .input-feild select{
        width:50%;
        margin:7px 0px 5px 0px;
        padding:10px;
        box-sizing:border-box;
        border-radius: 10px;
        font-size:18px;
    }
    .add-button input{
        background-color:#A31D1D;
        margin-left: 604px;
        margin-top: 25px;
        width:10%;
        color:white;
        font-weight:bold;
        border-radius: 10px;
        padding:10px;
    }
    .add-button input:hover{
        color:black;
        background-color:rgb(224, 129, 26);
    }
    .back-button input{
        background-color:#A31D1D;
        margin-left: 288px;
        margin-top: 5px;
        width:10%;
        color:white;
        font-weight:bold;
        border-radius: 10px;
        padding:10px;
        padding: 10px;
        position: absolute;
        top: 550px;
        left: 100px;
    }
    .back-button input:hover{
        color:black;
        background-color:rgb(224, 129, 26);
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
        <h1>Add Details</h1>
    </div>
    <div class="add">
        <center>
            <form method ="POST" action ="add.php">
                <div class = "input-feild">
                    <label>Event Name</label>
                    <input type = "text" name = "event_name" required><br>
                </div>  
                <div class = "input-feild">
                    <label>Event type</label>
                    <!-- <input type = "text" name = "event_type"><br> -->
                        <select name = "event_type" required>
                            <option value="" name="">--select event type--</option>
                            <option name="Public event">Public event</option>
                            <option name="Corporate event">Corporate event</option>
                            <option name="Cultural event">Cultural event</option>
                            <option name="Educational event">Educational event</option>
                            <option name="Sports event">Sports event</option>
                        </select>
                </div>  
                <div class = "input-feild">
                    <label>Start Date</label>
                    <input type = "date" name = "start_date" required><br>
                </div>  
                <div class = "input-feild">
                    <label>End Date</label>
                    <input type = "date" name = "end_date" required><br>
                </div>   
                <div class = "input-feild">
                    <label>Event Location</label>
                    <input type = "text" name = "event_location" required><br>
                </div>
                <div class = "input-feild">
                    <label>Event Address</label>
                    <input type = "text" name = "event_address" required><br>
                </div>  
                <div class = "add-button"><input type = "submit" value = "ADD" name = "add"><br></div> 
            </form>
            <form action="index.php" method="POST">
                <div class = "back-button"><input type = "submit" name = "back" value = "BACK"><br></div> 
            </form>
        <center>
    </div>
    <div class="footer">
        <center><p>Copyrights &copy; <?php echo date('Y',strtotime('now')); ?> </p></center>
    </div>
</body>
</html>
<?php
    
    error_reporting(E_ALL & ~ E_WARNING);
    include_once 'config/config.php';
    include('curlfunc.php');

    if($conn){
            // echo "Database Connected!";
        if(isset($_POST['add'])){

            $postData = [
                'action' => strtolower($_POST['add']),
                'event_name' => $_POST['event_name'],
                'event_type' => $_POST['event_type'],
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'event_location' => $_POST['event_location'],
                'event_address' => $_POST['event_address']
            ];
            //calling curl function 
            $response = curlHitresponse("http://localhost/phppractice/eventManagement_v5_curl/server.php",$postData);
            echo $response;
        }

    }else{
        echo "Database Not Connected!"; 
    }
    $conn = null;
?>