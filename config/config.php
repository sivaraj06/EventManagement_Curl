<?php
    
    $user = "root";
    
    $password = "12345";

    $dsn = "mysql:host=localhost;dbname=event_management;port=3306";

    $conn = new PDO($dsn,$user,$password);

    // $conn = null;
?>
