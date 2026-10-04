<?php

session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: view_subject.php");
    exit();
}

$id = intval($_GET['id']);

$sql = "DELETE FROM subjects WHERE subject_id='$id'";

mysqli_query($conn, $sql);

header("Location: view_subject.php");

exit();

?>