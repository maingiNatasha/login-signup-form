<?php
session_start();

//Include database connection
require_once 'connection.php';

//Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Retrieve form data
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['user'];

    // Check if the username already exists in the database
    $checkUsernameQuery = "SELECT * FROM users WHERE username = ?";
    $checkUsernameStmt = $conn->prepare($checkUsernameQuery);
    $checkUsernameStmt->bind_param("s", $username);
    $checkUsernameStmt->execute();
    $result = $checkUsernameStmt->get_result();

    if ($result->num_rows > 0) {
        // Username already exists, handle the error
        $_SESSION['message'] = "Username already exists. Try creating another one";
        $_SESSION['messageType'] = "error";

        // Redirect back to the signup page
        header("Location: ../html_pages/signup.html");
        exit();
    }

    //Hash the password
    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);

    //Insert data into the database
    $sql = "INSERT INTO users (fullname, email, username, password, role) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $fullname, $email, $username, $hashedpassword, $role);

    if ($stmt->execute()) {
        //Display message then redirect to login page
        $_SESSION['message'] = "Sign up successful";
        $_SESSION['messageType'] = "success";

        // Redirect back to the signup page
        header("Location: ../html_pages/signup.html");
        exit();
    }
    else {
        // Handle database errors
        $_SESSION['message'] = "An Error occurred while signing up. Please try again";
        $_SESSION['messageType'] = "error";

        // Redirect back to the signup page
        header("Location: ../html_pages/signup.html");
        exit();
    }

}

?>