<?php

include_once 'user.inc.php';

if (!isset($_POST['submit'])) {
    header("Location: ../signup.php");
    exit();
}

$user = new User();
$conn = $user->connect();
$sql = $user->makeUser($conn);

$result = mysqli_query($conn, $sql);
header("Location: ../signup.php?signup=success");
exit();
