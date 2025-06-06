<?php
    session_start();
    include_once 'config/config.php';
    
    // error_reporting(E_ALL & ~E_NOTICE);
    // error_reporting(E_ALL & ~E_WARNING);

    $action = $_POST['action'];

    switch ($action) {
        case 'login':
            $select_query ="SELECT 
                                *
                            FROM 
                                registration_details
                            WHERE 
                                user_name = :username AND
                                password = :password ;
                            ";
            $stmt = $conn->prepare($select_query);
            $stmt->bindParam(':username',$_POST['username']);
            $stmt->bindParam(':password',$_POST['password']);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $res_json = json_encode($row);
            echo $res_json;
            break;
        case 'all':
            $flag = 0;
            $select_query = "SELECT 
                                * 
                             FROM 
                                event_details
                             WHERE 
                                flag = ?;
                            ";
            $stmt = $conn->prepare($select_query);

            $stmt->bindParam(1, $flag);
            $stmt->execute();

            $res_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $res_json = json_encode($res_rows);
            print_r($res_json);
            break;
            case 'register':             
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (
                        !empty($_POST['name']) &&
                        !empty($_POST['email']) &&
                        !empty($_POST['password']) &&
                        !empty($_POST['confirm_password'])
                    ) {
                        $user_name = trim($_POST['name']);
                        $email_id = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                        $password = $_POST['password'];
                        $cpassword = $_POST['confirm_password'];
                        $img_path = 'user.png'; // Default image
            
                        if ($password !== $cpassword) {
                            echo "<h3>Password mismatch</h3>";
                            exit;
                        }
            
                        // Hash the password before storing
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
                        // Accept image path as string from POST
                        if (!empty($_POST['profile']) && $_POST['profile'] !== 'user.png') {
                            $img_path = $_POST['profile'];
                        }
            
                        // Insert into database
                        if ($img_path !== 'user.png') {
                            $insert_query = "INSERT INTO registration_details (user_name, email_id, password, profile_img) VALUES (?, ?, ?, ?)";
                            $stmt = $conn->prepare($insert_query);
                            $stmt->bindParam(1, $user_name);
                            $stmt->bindParam(2, $email_id);
                            $stmt->bindParam(3, $hashed_password);
                            $stmt->bindParam(4, $img_path);
                        } else {
                            $insert_query = "INSERT INTO registration_details (user_name, email_id, password) VALUES (?, ?, ?)";
                            $stmt = $conn->prepare($insert_query);
                            $stmt->bindParam(1, $user_name);
                            $stmt->bindParam(2, $email_id);
                            $stmt->bindParam(3, $hashed_password);
                        }
            
                        if ($stmt->execute()) {
                            header('Location: login.php');
                            exit;
                        } else {
                            echo "<h3>Failed to register. Please try again.</h3>";
                        }
                    } else {
                        echo "<h3>All fields are required.</h3>";
                    }
                }
                break;
            
        case 'filter':
            $flag = 0;
            $type = $_POST['type'];
            $location = $_POST['location'];
            $date = $_POST['date'];
    
            if (!empty($type)) {
                $filter_query = "SELECT 
                                    * 
                                 FROM 
                                    event_details 
                                 WHERE 
                                    flag = ? AND 
                                    event_type LIKE ?
                                ;";
                $stmt = $conn->prepare($filter_query);
                $stmt->bindParam(1, $flag);
                $stmt->bindParam(2, $type);

                $stmt->execute();
            } elseif (!empty($location)) {
                $filter_query = "SELECT 
                                    * 
                                 FROM 
                                    event_details 
                                 WHERE 
                                    flag = ? AND 
                                    event_location LIKE ?;
                                ";
                $stmt = $conn->prepare($filter_query);
                $stmt->bindParam(1, $flag);
                $stmt->bindParam(2, $location);
                $stmt->execute();
            } elseif (!empty($date)) {
                $filter_query = "SELECT 
                                    * 
                                 FROM 
                                    event_details 
                                 WHERE 
                                    flag = ? AND 
                                    start_date LIKE ?;
                                ";
                $stmt = $conn->prepare($filter_query);
                $stmt->bindParam(1, $flag);
                $stmt->bindParam(2, $date);
                $stmt->execute();
            } else {
                echo "<h2>Enter at most one filter data!</h2>";
                exit();
            }
    
            $res_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $res_json = json_encode($res_rows);

            print_r($res_json);
            break;
        case 'update':
            $event_id = $_POST['event_id'];
            $event_name = $_POST['event_name'];
            $event_type = $_POST['event_type'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $event_location = $_POST['event_location'];
            $event_address = $_POST['event_address'];

            $update_query = "UPDATE 
                                event_details 
                             SET
                                event_name = :event_name, 
                                event_type = :event_type,
                                start_date = :start_date,
                                end_date = :end_date,
                                event_location = :event_location,
                                event_address = :event_address
                             WHERE 
                                event_id = :event_id;";
        
            $update_list = $conn->prepare($update_query);
    
            $update_list->bindParam(':event_id',$event_id);
            $update_list->bindParam(':event_name',$event_name);
            $update_list->bindParam(':event_type',$event_type);
            $update_list->bindParam(':start_date',$start_date);
            $update_list->bindParam(':end_date',$end_date);
            $update_list->bindParam(':event_location',$event_location);
            $update_list->bindParam(':event_address',$event_address);
    
            $update_list->execute();
            $result = $update_list->rowCount();
            if($result){
                echo "<h2>Event Updated Successfully!</h2>";
            }else{
                echo "<h2>Event Already Updated!</h2>";
            }
            break;
        case 'add':
            $event_name = $_POST['event_name'];
            $event_type = $_POST['event_type'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $event_location = $_POST['event_location'];
            $event_address = $_POST['event_address'];

            $select_query = "SELECT 
                                * 
                            FROM
                                event_details
                            WHERE
                                event_name = :event_name AND 
                                event_type = :event_type AND
                                start_date = :start_date AND
                                end_date = :end_date AND
                                event_location = :event_location AND
                                event_address = :event_address;";

            $stmt = $conn->prepare($select_query);

            $stmt->bindParam(':event_name',$event_name);
            $stmt->bindParam(':event_type',$event_type);
            $stmt->bindParam(':start_date',$start_date);
            $stmt->bindParam(':end_date',$end_date);
            $stmt->bindParam(':event_location',$event_location);
            $stmt->bindParam(':event_address',$event_address);

            $stmt->execute();

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($rows)==0){

                $insert_query = " INSERT INTO
                                    event_details(
                                        event_name,
                                        event_type,
                                        start_date,
                                        end_date,
                                        event_location,
                                        event_address)
                                  VALUES
                                        (
                                            ?,
                                            ?,
                                            ?,
                                            ?,
                                            ?,
                                            ?
                                        );
                                    ";

                $stmt = $conn->prepare($insert_query);

                $stmt->bindParam(1,$event_name);
                $stmt->bindParam(2,$event_type);
                $stmt->bindParam(3,$start_date);
                $stmt->bindParam(4,$end_date);
                $stmt->bindParam(5,$event_location);
                $stmt->bindParam(6,$event_address);

                $stmt->execute();

                $result = $stmt->rowCount();

                if($result==1){
                    echo "<h2>Event Added Successfully!</h2>";
                }
            }else{
                echo "<h2>Event Already Added !</h2>";
            }
            break;
        case 'delete':
            $flag = 1;
            $query = "UPDATE 
                        event_details
                      SET 
                        flag = ?
                      WHERE 
                        event_id = ?; 
                    ";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(1,$flag);
            $stmt->bindParam(2,$_POST['event_id']);

            $stmt->execute();

            $result = $stmt->rowCount();
            break;
        case 'bookedEvents':
            $select_query = "SELECT 
                                rd.user_name,
                                rd.email_id,
                                ed.*
                            FROM
                                booked_details AS bd
                            INNER JOIN 
                                registration_details AS rd
                            ON
                                rd.register_id = bd.r_register_id
                            INNER JOIN
                                event_details AS ed
                            ON
                                ed.event_id = bd.r_event_id
                            ;";
            $select_result = $conn->query($select_query);

            $select_result->execute();

            $result = $select_result->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($result);

            break;
        default:
            echo "Invalid action.";
            break;
    }

?>
