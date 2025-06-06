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
    .message h1{
        position:absolute;
        top:119px;
        right:20px;
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
        <form action="user.php" method="POST">
                <div class = "back-button"><input type = "submit" name = "back" value = "BACK"><br></div> 
        </form>
    </div>
    <div class="content">
        
    </div>
    <div class="footer">
        <center><p>Copyrights &copy; <?php echo date('Y',strtotime('now')); ?> </p></center>
    </div>
</html>
<?php

    $user_name= $_SESSION['username'];

    if(isset($_POST['view'])){
        // echo "<h1>View events</h1>";
        $flag=0;
        $select_query = "SELECT 
                            * 
                         FROM
                            event_details
                         WHERE
                            flag = ?;";
        $event_list = $conn->prepare($select_query);
        
        $event_list->bindParam(1,$flag);

        $event_list->execute();

        $result = $event_list->fetchAll(PDO::FETCH_ASSOC);

        if(count($result)>0){
            echo"<div class='container custom_table'>
                    <div class='content'>
                        <h1>All Events List</h1>
                        <table cellspacing='0' cellpadding='20' border='1' width='100%' align='center' class ='custom_table'>
                            <tr>
                                <th>EVENT NAME</th>
                                <th>EVENT TYPE</th>
                                <th>START DATE</th>
                                <th>END DATE</th>
                                <th>NUMBER OF DAYS</th>
                                <th>EVENT LOCATION</th>
                                <th>EVENT ADDRESS</th>
                                <th>ACTIONS</th>
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
                        <td style='padding:6px;margin-left:0px;' width='17%'>
                            <form action='userView.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='booked_id' value='".$rows['event_id']."'>
                                <input type='hidden' name='event_name' value='".$rows['event_name']."'>
                                <input type='hidden' name='event_type' value='".$rows['event_type']."'>
                                <input type='hidden' name='start_date' value='".$rows['start_date']."'>
                                <input type='hidden' name='end_date' value='".$rows['end_date']."'>
                                <input type='hidden' name='no_of_days' value='".$no_of_days."'>
                                <input type='hidden' name='event_location' value='".$rows['event_location']."'>
                                <input type='hidden' name='event_address' value='".$rows['event_address']."'>
                                <input type = 'submit' name = 'book' value = 'Book Now' style='background-color:#1D1616;color:white;cursor:pointer;'>
                            </form>
                            <form action='userView.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='saved_id' value='".$rows['event_id']."'>
                                <input type='hidden' name='event_name' value='".$rows['event_name']."'>
                                <input type='hidden' name='event_type' value='".$rows['event_type']."'>
                                <input type='hidden' name='start_date' value='".$rows['start_date']."'>
                                <input type='hidden' name='end_date' value='".$rows['end_date']."'>
                                <input type='hidden' name='no_of_days' value='".$no_of_days."'>
                                <input type='hidden' name='event_location' value='".$rows['event_location']."'>
                                <input type='hidden' name='event_address' value='".$rows['event_address']."'>
                                <input type = 'submit' name = 'save' value = 'Save' style='background-color:#8E1616;color:white;cursor:pointer;'>
                            </form>
                        </td>
                     </tr>";
            }
            echo " </table>
                </div>
            </div>";
        }
    }else{
        $flag=0;
        $select_query = "SELECT 
                            * 
                         FROM
                            event_details
                         WHERE
                            flag = ?;";
        $event_list = $conn->prepare($select_query);
        
        $event_list->bindParam(1,$flag);

        $event_list->execute();

        $result = $event_list->fetchAll(PDO::FETCH_ASSOC);

        if(count($result)>0){
            echo"<div class='container custom_table'>
                    <div class='content'>
                        <h1>All Events List</h1>
                        <table cellspacing='0' cellpadding='20' border='1' width='100%' align='center' class ='custom_table'>
                            <tr>
                                <th>EVENT NAME</th>
                                <th>EVENT TYPE</th>
                                <th>START DATE</th>
                                <th>END DATE</th>
                                <th>NUMBER OF DAYS</th>
                                <th>EVENT LOCATION</th>
                                <th>EVENT ADDRESS</th>
                                <th>ACTIONS</th>
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
                        <td style='padding:6px;margin-left:0px;' width='17%'>
                            <form action='userView.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='booked_id' value='".$rows['event_id']."'>
                                <input type='hidden' name='event_name' value='".$rows['event_name']."'>
                                <input type='hidden' name='event_type' value='".$rows['event_type']."'>
                                <input type='hidden' name='start_date' value='".$rows['start_date']."'>
                                <input type='hidden' name='end_date' value='".$rows['end_date']."'>
                                <input type='hidden' name='no_of_days' value='".$no_of_days."'>
                                <input type='hidden' name='event_location' value='".$rows['event_location']."'>
                                <input type='hidden' name='event_address' value='".$rows['event_address']."'>
                                <input type = 'submit' name = 'book' value = 'Book Now' style='background-color:#1D1616;color:white;cursor:pointer;'>
                            </form>
                            <form action='userView.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='saved_id' value='".$rows['event_id']."'>
                                <input type='hidden' name='event_name' value='".$rows['event_name']."'>
                                <input type='hidden' name='event_type' value='".$rows['event_type']."'>
                                <input type='hidden' name='start_date' value='".$rows['start_date']."'>
                                <input type='hidden' name='end_date' value='".$rows['end_date']."'>
                                <input type='hidden' name='no_of_days' value='".$no_of_days."'>
                                <input type='hidden' name='event_location' value='".$rows['event_location']."'>
                                <input type='hidden' name='event_address' value='".$rows['event_address']."'>
                                <input type = 'submit' name = 'save' value = 'Save' style='background-color:#8E1616;color:white;cursor:pointer;'>
                            </form>
                        </td>
                     </tr>";
            }
            echo " </table>
                </div>
            </div>";
        }
    }
    if(isset($_POST['book']) && isset($_SESSION["register_id"])){
        $register_id = $_SESSION["register_id"];
        $booked_id = $_POST["booked_id"];
        $select_query = "SELECT 
                            * 
                         FROM 
                            booked_details 
                         WHERE 
                            r_register_id = :register_id AND
                            r_event_id = :booked_id;
                        ";
        $select_result = $conn->prepare($select_query);

        $select_result->bindParam(':register_id',$register_id);
        $select_result->bindParam(':booked_id',$booked_id);

        $select_result->execute();

        $result = $select_result->fetchAll(PDO::FETCH_ASSOC);

        if(count($result)==0){
            $insert_query = "INSERT INTO 
                            booked_details(
                                r_register_id,
                                r_event_id
                            )
                        VALUES(
                            :register_id,
                            :booked_id
                        );";
        $insert_result = $conn->prepare($insert_query);

        $insert_result->bindParam(':register_id',$register_id);
        $insert_result->bindParam(':booked_id',$booked_id);
        
        if($insert_result->execute()){
            echo "<div class='message'>
            <h1>Booked Successfully!</h1>
            </div>"; 
        }
        }else{
            echo "<div class='message'>
            <h1>You have Already Booked!</h1>
            </div>";
        }
    }elseif(isset($_POST['save']) && isset($_SESSION["register_id"])){
        $register_id = $_SESSION["register_id"];
        $saved_id = $_POST["saved_id"];
        $select_query = "SELECT 
                            * 
                         FROM 
                            saved_events 
                         WHERE 
                            r_user_id = :register_id AND
                            r_event_id = :saved_id;
                        ";
        $select_result = $conn->prepare($select_query);

        $select_result->bindParam(':register_id',$register_id);
        $select_result->bindParam(':saved_id',$saved_id);

        $select_result->execute();

        $result = $select_result->fetchAll(PDO::FETCH_ASSOC);

        if(count($result)==0){
            
            $insert_query = "INSERT INTO 
                                    saved_events(
                                        r_user_id,
                                        r_event_id
                                    )
                                VALUES(
                                    :register_id,
                                    :saved_id
                                );";
            $insert_result = $conn->prepare($insert_query);

            $insert_result->bindParam(':register_id',$register_id);
            $insert_result->bindParam(':saved_id',$saved_id);
            
            if($insert_result->execute()){
                echo "<div class='message'>
                <h1>Event Saved!</h1>
                </div>";
            }
        }else{
            echo "<div class='message'>
            <h1>Already in Saved list!</h1>
            </div>";
        }
    }
?>