<?php
include "conn.php";

$message = '';

if (isset($_POST['checkLogin'])){
    $userName = $_POST['username'];
    $password = $_POST['password']; 
    $query = "SELECT * FROM account WHERE username = '$userName'";
    $findUserName = mysqli_query($conn, $query);
    // check account in database
    if (mysqli_num_rows($findUserName) > 0){
        $accountInfo = mysqli_fetch_assoc($findUserName);
        if ($password == $accountInfo['password']){
            $message = 'success';
            $_SESSION['username'] = $accountInfo['username']; 
        }
        else{ # wrong password
            $message = 'fail';
        }
    }
    else{    # can't find account
        $message = 'fail';
    }
    echo $message;
}