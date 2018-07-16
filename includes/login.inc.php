<?php

session_start();
include_once 'user.inc.php';

if (!isset($_POST['submit'])) {
    header("Location: ../index.php");
    exit();
}

$user = new User();
$conn = $user->connect();
$sql = $user->findUser($conn);
