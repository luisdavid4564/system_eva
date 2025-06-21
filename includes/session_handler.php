<?php
session_start();

function isUserLoggedIn() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

function redirectToLogin() {
    header("Location: login.php");
    exit();
}

function redirectToDashboard() {
    header("Location: index.php"); 
    exit();
}
?>