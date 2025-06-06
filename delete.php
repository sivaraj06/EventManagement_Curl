<?php
    error_reporting(E_ALL & ~ E_WARNING);
    include_once 'config/config.php';
    include('curlfunc.php');

    if(!empty($_POST['delete_id'])){
        $event_id = $_POST['delete_id'];
        $action = $_POST['action'];
    
        $postData = [
            'event_id'=> $event_id,
            'action'=> $action
        ];

        $response = curlHitresponse("http://localhost/phppractice/eventManagement_v5_curl/server.php",$postData);

        header("Location:index.php");
    }
?>