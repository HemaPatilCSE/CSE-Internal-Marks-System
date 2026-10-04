<?php

session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

$message = "";

if (isset($_POST['add_subject'])) {

    $subject_name = trim($_POST['subject_name']);
    $semester = trim($_POST['semester']);

    $subject_name = mysqli_real_escape_string($conn, $subject_name);
    $semester = mysqli_real_escape_string($conn, $semester);

    $sql = "INSERT INTO subjects (subject_name, semester)
            VALUES ('$subject_name', '$semester')";

    if (mysqli_query($conn, $sql)) {
        $message = "Subject Added Successfully";
    } else {
        $message = "Subject Could Not Be Added";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Subject</title>

    <link rel="stylesheet" href="student_style.css">

</head>

<body>

<div class="container">

    <h1>Add Subject</h1>

    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php } ?>

    <form method="POST">

        <input
            type="text"
            name="subject_name"
            placeholder="Enter Subject Name"
            required
        >

        <input
            type="text"
            name="semester"
            placeholder="Enter Semester"
            required
        >

        <button type="submit" name="add_subject">
            Add Subject
        </button>

    </form>

    <br>

    <a href="view_subject.php">
        View Subjects
    </a>

    <br><br>

    <a href="dashboard.php">
        Back to Dashboard
    </a>

</div>

</body>

</html>