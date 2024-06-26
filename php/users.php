<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['code'], $_POST['name'])){
    $username = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING);
	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

    if($_POST['id'] != null && $_POST['id'] != ''){
        if ($update_stmt = $db->prepare("UPDATE users SET username=?, name=? WHERE id=?")) {
            $update_stmt->bind_param('sss', $username, $name, $_POST['id']);
            
            // Execute the prepared query.
            if (! $update_stmt->execute()) {
                echo json_encode(
                    array(
                        "status"=> "failed", 
                        "message"=> $update_stmt->error
                    )
                );
            }
            else{
                $update_stmt->close();
                $db->close();
                
                echo json_encode(
                    array(
                        "status"=> "success", 
                        "message"=> "Updated Successfully"
                    )
                );
            }
        }
    }
    else{
        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
        $password = '123456';
        $password = hash('sha512', $password . $random_salt);

        if ($insert_stmt = $db->prepare("INSERT INTO users (username, name, password, salt) VALUES (?, ?, ?, ?)")) {
            $insert_stmt->bind_param('ssss', $username, $name, $password, $random_salt);
            
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                echo json_encode(
                    array(
                        "status"=> "failed", 
                        "message"=> $insert_stmt->error
                    )
                );
            }
            else{
                $insert_stmt->close();
                $db->close();
                
                echo json_encode(
                    array(
                        "status"=> "success", 
                        "message"=> "Added Successfully!!" 
                    )
                );
            }
        }
    }
}
else{
    echo json_encode(
        array(
            "status"=> "failed", 
            "message"=> "Please fill in all the fields"
        )
    );
}
?>