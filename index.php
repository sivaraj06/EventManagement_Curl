<?php
    session_start();
    include('curlfunc.php');
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
    if (!isset($_COOKIE['last_admin']) || $_COOKIE['last_admin'] !== $_SESSION['username']) {
        header("Location: login.php");
        exit();
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['username'];?></title>
    <!-- <link rel="stylesheet" href="assets/css/events.css"> -->
</head>
<style>
    *{
        font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }
    .header{
        margin:-105px -8px 0px -8px;
        background-color:#A31D1D;
        padding:7px 0px;
        position:fixed;
        width:100%;
        display:flex;
    }
    .header h1{
        color:white;
        padding-left:10px;
        margin-right:851px;
    }
    .header a {
        display: inline-block;
        margin-top: 15px;
        text-decoration: none;
        color: white;
        margin-right: 30px;
        font-size: 18px;
        padding: 19px 15px;
        border-radius: 8px;
    }
    .header a:hover {
        background-color: #F6C794;
        color: black;
        font-weight: bold;
        box-shadow: 2px 2px 8px black;
        padding-bottom:0px;
    }
    .header input {
        display: inline-block;
        margin-top: 13px;
        text-decoration: none;
        color:white;
        margin-right: 30px;
        font-size: 18px;
        padding: 19px 15px;
        border-radius: 8px;
        background-color: #A31D1D;
        border:none;
        width:100%;
    }
    .header input:hover {
        background-color: #F6C794;
        color: black;
        font-weight: bold;
        box-shadow: 2px 2px 8px black;
        padding-bottom:19px 0px;
    }
    th{
        background-color:#E5D0AC;
    }
    td{
        text-align: center;
    }
    input{
        width:24px;
    }
    .content{
        margin-top:35px;
    }
    .img_container{
        margin-top:103px;
    }
    .img_container img{
        position: absolute;
        top: 106px;
        z-index: -1;
    }
    .img_container input{
        margin: 3px 20px 4px 9px;    
        padding: 5px 10px;          
        border-radius: 10px;         
        width: 250px;
        font-size: 18px;
    }
    .img_container label{
        font-weight:bold;
        font-size: 18px;
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
        <h1>Event Details</h1>
        <a href="add.php">Add event</a>
        <a href="bookedEvents.php">Booked events</a>  
        <form action="index.php" method="POST">
            <input type="submit" name="logout" value="Log out">
        </form>
    </div>
    <div class="img_container">

        <h1 style="display:inline;">Filter Events</h1>
        <img src="assets/images/filter.png" width="30px" height="30px" >
       
        <form action="index.php" method="POST" style="display:inline;margin-left:50px;">
            
            <label>Event Type</label>
            <input type="text" name="type">
        
            <label>Location</label>
            <input type="text" name="location">
        
            <label>Date</label>
            <input type="date" name="date">

            <input type="submit" value="Search" name = 'filter' style="width: 70px;background-color: #F6C794;">
        </form>
    </div>
    <div class="footer">
        <center><p>Copyrights &copy; <?php echo date('Y',strtotime('now')); ?> </p></center>
    </div>
</body>
</html>
<?php
    
    error_reporting(E_ALL & ~E_WARNING);

    if(isset($_POST['logout'])){
        session_unset();
        session_destroy();
        header("Location:login.php");
    }

    if(isset($_POST['filter'])){
        $postData = [
            'action' => 'filter',
            'type' => $_POST['type'],
            'location' => $_POST['location'],
            'date' => $_POST['date']
        ];
        //call the curl function 
        $res_rows = curlHit("http://localhost/phppractice/eventManagement_v5_curl/server.php",$postData);

        if($res_rows!=null && count($res_rows) > 0){
            echo "<div class='container'>
                    <div class='content'>
                        <table cellspacing='0' cellpadding='20' border='1' width='100%' align='center' style='margin-bottom:65px;'>
                            <tr>
                                <th>EVENT NAME</th>
                                <th>EVENT TYPE</th>
                                <th>START DATE</th>
                                <th>END DATE</th>
                                <th>NUMBER OF DAYS</th>
                                <th>EVENT LOCATION</th>
                                <th>EVENT ADDRESS</th>
                            </tr>";
                    foreach ($res_rows as $rows) {
                        if ($rows['start_date'] && $rows['end_date']) {
                            $days = date_diff(date_create($rows['start_date']), date_create($rows['end_date']));
                            $no_of_days = $days->format("%a") + 1;
                        } else {
                            $no_of_days = 0;
                        }
                        echo "<tr>
                                <td>{$rows['event_name']}</td>
                                <td>{$rows['event_type']}</td>
                                <td>{$rows['start_date']}</td>
                                <td>{$rows['end_date']}</td>
                                <td>{$no_of_days}</td>
                                <td>{$rows['event_location']}</td>
                                <td>{$rows['event_address']}</td>
                            </tr>";
                    }
                    echo "  </table>
                        </div>
                        </div>";
        }else{
            echo "<h2>No results found!</h2>";
        }
    }else{
        echo "<h2>Enter Atmost one data to filter!</h2>";
    }

    $postData = ['action' => 'all'];

    //call the curl fucntion 
    $res_rows = curlHit("http://localhost/phppractice/eventManagement_v5_curl/server.php",$postData);

    if ($res_rows != null && count($res_rows) > 0) {
        echo "<div class='container'>
                <div class='content'>
                    <h1>All Events List</h1>
                    <table cellspacing='0' cellpadding='20' border='1' width='100%' align='center' style='margin-bottom:65px;'>
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

        foreach ($res_rows as $rows) {
            if ($rows['start_date'] && $rows['end_date']) {
                $days = date_diff(date_create($rows['start_date']), date_create($rows['end_date']));
                $no_of_days = $days->format("%a") + 1;
            } else {
                $no_of_days = 0;
            }

            echo "<tr>
                    <td>{$rows['event_name']}</td>
                    <td>{$rows['event_type']}</td>
                    <td>{$rows['start_date']}</td>
                    <td>{$rows['end_date']}</td>
                    <td>{$no_of_days}</td>
                    <td>{$rows['event_location']}</td>
                    <td>{$rows['event_address']}</td>
                    <td>
                        <form action='update.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='action' value='update'>
                            <input type='hidden' name='update_id' value='{$rows['event_id']}'>
                            <input type='hidden' name='event_name' value='{$rows['event_name']}'>
                            <input type='hidden' name='event_type' value='{$rows['event_type']}'>
                            <input type='hidden' name='start_date' value='{$rows['start_date']}'>
                            <input type='hidden' name='end_date' value='{$rows['end_date']}'>
                            <input type='hidden' name='no_of_days' value='{$no_of_days}'>
                            <input type='hidden' name='event_location' value='{$rows['event_location']}'>
                            <input type='hidden' name='event_address' value='{$rows['event_address']}'>
                            <input type='image' src='assets/images/edit.png' width='44px' height='38px' name='update'>
                        </form>
                        <form action='delete.php' method='POST' style='display:inline;margin-left:8px;'>
                            <input type='hidden' name='action' value='delete'>
                            <input type='hidden' name='delete_id' value='{$rows['event_id']}'>
                            <input type='image' src='assets/images/delete.png' width='35px' height='38px' name='delete'>
                        </form>
                    </td>
                </tr>";
        }

        echo "  </table>
            </div>
            </div>";
    } else {
        echo "<h2>No Event List Found!</h2>";
    }
   
?>

</body>
</html>
