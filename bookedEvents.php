<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
    <link rel="stylesheet" href="assets/css/add.css">
</head>
<style>
    th{
        background-color:#FDB7EA;
    }
    td{
        background-color: white;
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
    .content{
        margin-bottom:65px;
    }
</style>
<body>
    <div class="header">
        <h1>Total Bookings</h1>
    </div>
</body>
</html>

<?php

    include_once 'config/config.php';
    include('curlfunc.php');

    $action ='bookedEvents';
    echo "<div class='add'>
            <center>";
                $postData=[
                    'action'=> $action
                ];
                //calling curl function
                $result = curlHit("http://localhost/phppractice/eventManagement_v5_curl/server.php",$postData);

                // print_r($result);

                if($result !=null && count($result)>0){
                    echo"<div class='container'>
                            <div class='content'>
                                <h1>Booked Events</h1>
                                <table cellspacing='0' cellpadding='20' border='1' width='97%' align='center'>
                                    <tr>
                                        <th>NAME</th>
                                        <th>EMAIL ID</th>
                                        <th>EVENT NAME</th>
                                        <th>EVENT TYPE</th>
                                        <th>START DATE</th>
                                        <th>END DATE</th>
                                        <th>NUMBER OF DAYS</th>
                                        <th>EVENT LOCATION</th>
                                        <th>EVENT ADDRESS</th>
                                    </tr>";
                    foreach ($result as $rows) {
                        $days = date_diff(date_create($rows['start_date']),date_create($rows['end_date']));
                        $no_of_days = $days->format("%a")+1;
                        echo "<tr>
                                <td>".$rows['user_name']."</td>
                                <td>".$rows['email_id']."</td>
                                <td>".$rows['event_name']."</td>
                                <td>".$rows['event_type']."</td>
                                <td>".$rows['start_date']."</td>
                                <td>".$rows['end_date']."</td>
                                <td>".$no_of_days."</td>
                                <td>".$rows['event_location']."</td>
                                <td>".$rows['event_address']."</td>
                                </tr>";
                    }
                    echo " </table>
                        </div>
                    </div>";
                }else{
                    echo "<h1>No Bookings found!</h1>";
                }
        echo "
            <center>
         </div>
         <form action='index.php' method='POST'>
            <div class = 'back-button'><input type = 'submit' name = 'back' value = 'BACK'><br></div> 
         </form>
         <div class='footer'>
            <center><p>Copyrights &copy; "; echo date('Y',strtotime('now'));"</p></center>
         </div>
         ";
?>