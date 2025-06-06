<?php
// Include database and curl function
error_reporting(E_ALL & ~ E_WARNING);
include_once 'config/config.php';
include('curlfunc.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    // Get form data
    $user_name = trim($_POST['name']);
    $email_id = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $cpassword = $_POST['confirm_password'];

    $img_path = ""; // Default image path

    // Validate if all required fields are present
    if (!empty($user_name) && !empty($email_id) && !empty($password) && !empty($cpassword)) {
        
        // Check if passwords match
        if ($password !== $cpassword) {
            echo "<h3>Password mismatch</h3>";
            exit;
        }

        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Handle file upload for profile image
        if (!empty($_FILES['profile']['tmp_name'])) {
            // Validate image type (only jpg, png, gif)
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = $_FILES['profile']['type'];

            if (!in_array($file_type, $allowed_types)) {
                die("<h3>Invalid image format. Only JPG, PNG, and GIF allowed.</h3>");
            }

            // Get the file name and set the target path
            $img_name = basename($_FILES['profile']['name']);
            $upload_dir = 'assets/images/';
            $target_path = $upload_dir . $img_name;

            // Move the uploaded image to the server directory
            if (move_uploaded_file($_FILES['profile']['tmp_name'], $target_path)) {
                $img_path = $img_name;
            } else {
                die("<h3>Failed to upload image.</h3>");
            }
        } else {
            // If no profile image, use a default image
            $img_path = 'user.png'; // default image
        }

        // Prepare data for cURL or database insertion
        $postData = [
            'name' => $user_name,
            'email' => $email_id,
            'password' => $hashed_password,
            'confirm_password' => $cpassword,
            'profile' => $img_path
        ];

        // Calling cURL function to send data to the server
        $response = curlHitresponse("http://localhost/phppractice/eventManagement_v5_curl/server.php", $postData);
        
        // Check if the response was successful (optional, depending on your server.php logic)
        if ($response) {
            header('Location: login.php'); // Redirect to login page after successful registration
            exit;
        } else {
            echo "<h3>Failed to register. Please try again.</h3>";
        }
    } else {
        echo "<h3>All fields are required.</h3>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        * {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }
        body {
            background-image: url('assets/images/blur.png');
            background-repeat: no-repeat;
            background-size: 100%;
        }
        .container {
            width: 450px;
            background-color: #E16331;
            padding: 40px 20px;
            border-radius: 10px;
            margin: 100px auto;
            box-sizing: border-box;
            box-shadow: 5px 4px 3px #ccc;
            text-align: center;
        }
        .container input {
            width: 80%;
            border-radius: 20px;
            margin: 10px 0;
            padding: 10px;
            font-weight: bold;
            font-size: 16px;
        }
        .container button {
            border-radius: 20px;
            margin-top: 20px;
            padding: 10px 30px;
            font-size: 16px;
            font-weight: bold;
            background-color: white;
            border:none;
        }
        .container button:hover {
            background-color: rgb(117, 3, 3);
            color: white;
        }
        .container a {
            color: #A01712;
            font-weight: bold;
            font-size: 16px;
            text-decoration: none;
        }
        .container a:hover {
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="assets/images/user.png" width="100px" style="padding-bottom: 10px;"><br>
        <form action="register.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Enter username" required><br>
            <input type="email" name="email" placeholder="Enter email" required><br>
            <input type="password" name="password" placeholder="Enter password" required><br>
            <input type="password" name="confirm_password" placeholder="Confirm password" required><br>
            <input type="file" name="profile"><br>
            <button type="submit" name="action" value="register">REGISTER</button><br><br>
            <a href="login.php">Already a user? Login</a>
        </form>
    </div>
</body>
</html>
