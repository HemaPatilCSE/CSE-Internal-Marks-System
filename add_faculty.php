<?php

session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

$message = "";

if (isset($_POST['add_faculty'])) {

    $faculty_name = trim($_POST['faculty_name']);
    $subject = trim($_POST['subject']);

    $faculty_name = mysqli_real_escape_string($conn, $faculty_name);
    $subject = mysqli_real_escape_string($conn, $subject);

    $sql = "INSERT INTO faculty (faculty_name, subject)
            VALUES ('$faculty_name', '$subject')";

    if (mysqli_query($conn, $sql)) {
        $message = "Faculty Added Successfully";
    } else {
        $message = "Faculty Could Not Be Added";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Faculty</title>

    <link rel="stylesheet" href="student_style.css">

</head>

<body>

<div class="container">

    <h1>Add Faculty</h1>

    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php } ?>

    <form method="POST">

        <input
            type="text"
            name="faculty_name"
            placeholder="Enter Faculty Name"
            required
        >

        <input
            type="text"
            name="subject"
            placeholder="Enter Subject"
            required
        >

        <button type="submit" name="add_faculty">
            Add Faculty
        </button>

    </form>

    <br>

    <a href="view_faculty.php">
        View Faculty
    </a>

    <br><br>

    <a href="dashboard.php">
        Back to Dashboard
    </a>

</div>

</body>

</html>