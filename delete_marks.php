<?php

session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: view_marks.php");
    exit();
}

$id = intval($_GET['id']);

$sql = "DELETE FROM marks WHERE mark_id='$id'";

mysqli_query($conn, $sql);

header("Location: view_marks.php");

exit();

?>