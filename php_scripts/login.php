<?php
session_start();
require_once 'connection.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username already exists in the database
    $checkUsernameQuery = "SELECT * FROM users WHERE username = ?";
    $checkUsernameStmt = $conn->prepare($checkUsernameQuery);
    $checkUsernameStmt->bind_param("s", $username);
    $checkUsernameStmt->execute();
    $result = $checkUsernameStmt->get_result();

    //If username exists:
    if($result-> num_rows > 0) {
        //Check password
        $row = $result -> fetch_assoc();

        if (password_verify($password, $row['password'])) {
            //Password is correct, Login Successful
            $_SESSION['message'] = "Login Successful";
            $_SESSION['messageType'] = "success";
            $_SESSION['role'] = $row['role'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['email'] = $row['email'];

            //Redirect to Login page
            header("Location: ../html_pages/login.html");
            exit();
        }
        else {
            //Password incorrect
            $_SESSION['message'] = "Incorrect password";
            $_SESSION['messageType'] = "error";

            //Redirect to Login page
            header("Location: ../html_pages/login.html");
            exit();
        }
    }
    //If username not found
    else {
        $_SESSION['message'] = "Username not found";
        $_SESSION['messageType'] = "error";

        //Redirect to Login page
        header("Location: ../html_pages/login.html");
        exit();
    }
}
?>