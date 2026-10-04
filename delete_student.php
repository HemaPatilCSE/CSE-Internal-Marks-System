<?php

session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: view_student.php");
    exit();
}

$id = intval($_GET['id']);

$sql = "DELETE FROM students WHERE student_id='$id'";

mysqli_query($conn, $sql);

header("Location: view_student.php");

exit();

?>