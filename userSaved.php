<?php
    session_start();
    include_once 'config/config.php';
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
    .back-button{
        position: absolute;
        top:92px;
        /* bottom:0px; */
        right:0px;
        width:10%;
    }
    .back-button input{
    background-color:#A31D1D;
    margin-top: 5px;
    width:61%;
    color:white;
    font-weight:bold;
    border-radius: 10px;
    padding:10px;
    padding: 10px;
    }
    .back-button input:hover{
        color:black;
        background-color:rgb(224, 129, 26);
    }
    .message h1{
        position:absolute;
        top:119px;
        right:41px;
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
    </div>
    <div class="content">
        <form action="user.php" method="POST">
                <div class = "back-button"><input type = "submit" name = "back" value = "BACK"><br></div> 
        </form>
    </div>
    <div class="footer">
        <center><p>Copyrights &copy; <?php echo date('Y',strtotime('now')); ?> </p></center>
    </div>
</html>

<?php
    if(isset($_POST['saved'])){
        // echo "<h1>Saved events</h1>";
        $user_id = $_SESSION['register_id'];
        $select_query = "SELECT 
                            * 
                        FROM 
                            event_details AS ed
                        INNER JOIN 
                            saved_events AS se
                        ON
                            ed.event_id = se.r_event_id
                        WHERE
                            se.r_user_id = :user_id; ";
        $select_result = $conn->prepare($select_query);

        $select_result->bindParam(':user_id',$user_id);

        $select_result->execute();

        $result = $select_result->fetchAll(PDO::FETCH_ASSOC);

        if(count($result)>0){
            echo"<div class='container custom_table'>
                    <div class='content'>
                        <h1>Saved Events</h1>
                        <table cellspacing='0' cellpadding='20' border='1' width='100%' align='center' class ='custom_table'>
                            <tr>
                                <th>EVENT NAME</th>
                                <th>EVENT TYPE</th>
                                <th>START DATE</th>
                                <th>END DATE</th>
                                <th>NUMBER OF DAYS</th>
                                <th>EVENT LOCATION</th>
                                <th>EVENT ADDRESS</th>
                                <th>
                                    ACTIONS
                                </th>
                            </tr>";
            foreach ($result as $rows) {
                $days = date_diff(date_create($rows['start_date']),date_create($rows['end_date']));
                $no_of_days = $days->format("%a")+1;
                echo "<tr>
                        <td>".$rows['event_name']."</td>
                        <td>".$rows['event_type']."</td>
                        <td>".$rows['start_date']."</td>
                        <td>".$rows['end_date']."</td>
                        <td>".$no_of_days."</td>
                        <td>".$rows['event_location']."</td>
                        <td>".$rows['event_address']."</td>
                        <td>
                            <iframe name='hiddenFrame' style='display:none;'></iframe>
                            <form action = 'userSaved.php' method = 'POST'>
                                <input type='hidden' name='cancel_id' value='".$rows['saved_id']."'>
                                <input type = submit name = 'deleteSaved' value = '🗑' style='padding:5px;font-size:30px;margin-left:-20px;'>
                            </form>
                        </td>
                        </tr>";
            }
            echo " </table>
                </div>
            </div>";
        }else{
            echo "<h1>No Events Saved Yet!</h1>";
        }
    }
    if(isset($_POST['deleteSaved'])&& isset($_POST['cancel_id'])){
        $cancel_id = $_POST['cancel_id'];
        $cancel_query = "DELETE FROM 
                            saved_events
                         WHERE
                            saved_id = :cancel_id;";
        $cancel_result = $conn->prepare($cancel_query);

        $cancel_result->bindParam(':cancel_id',$cancel_id);

        if($cancel_result->execute()){
            echo "<div class='message'>
            <h1>Deleted!</h1>
            </div>";
        }

        $user_id = $_SESSION['register_id'];
        $select_query = "SELECT 
                            * 
                        FROM 
                            event_details AS ed
                        INNER JOIN 
                            saved_events AS se
                        ON
                            ed.event_id = se.r_event_id
                        WHERE
                            se.r_user_id = :user_id; ";
        $select_result = $conn->prepare($select_query);

        $select_result->bindParam(':user_id',$user_id);

        $select_result->execute();

        $result = $select_result->fetchAll(PDO::FETCH_ASSOC);

        if(count($result)>0){
            echo"<div class='container custom_table'>
                    <div class='content'>
                        <h1>Saved Events</h1>
                        <table cellspacing='0' cellpadding='20' border='1' width='100%' align='center' class ='custom_table'>
                            <tr>
                                <th>EVENT NAME</th>
                                <th>EVENT TYPE</th>
                                <th>START DATE</th>
                                <th>END DATE</th>
                                <th>NUMBER OF DAYS</th>
                                <th>EVENT LOCATION</th>
                                <th>EVENT ADDRESS</th>
                                <th>
                                    ACTIONS
                                </th>
                            </tr>";
            foreach ($result as $rows) {
                $days = date_diff(date_create($rows['start_date']),date_create($rows['end_date']));
                $no_of_days = $days->format("%a")+1;
                echo "<tr>
                        <td>".$rows['event_name']."</td>
                        <td>".$rows['event_type']."</td>
                        <td>".$rows['start_date']."</td>
                        <td>".$rows['end_date']."</td>
                        <td>".$no_of_days."</td>
                        <td>".$rows['event_location']."</td>
                        <td>".$rows['event_address']."</td>
                        <td>
                            <iframe name='hiddenFrame' style='display:none;'></iframe>
                            <form action = 'userSaved.php' method = 'POST'>
                                <input type='hidden' name='cancel_id' value='".$rows['saved_id']."'>
                                <input type = submit name = 'deleteSaved' value = '🗑' style='padding:5px;font-size:30px;margin-left:-20px;'>
                            </form>
                        </td>
                        </tr>";
            }
            echo " </table>
                </div>
            </div>";
        }else{
            echo "<h1>No Events Saved Yet!</h1>";
        }

    }
?>