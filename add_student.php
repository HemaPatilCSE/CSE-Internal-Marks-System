<?php

session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

$success_message = "";

if (isset($_POST['add_student'])) {

    $student_name = trim($_POST['student_name']);
    $usn = trim($_POST['usn']);
    $semester = trim($_POST['semester']);
    $section = trim($_POST['section']);

    $student_name = mysqli_real_escape_string($conn, $student_name);
    $usn = mysqli_real_escape_string($conn, $usn);
    $semester = mysqli_real_escape_string($conn, $semester);
    $section = mysqli_real_escape_string($conn, $section);

    $check = mysqli_query(
        $conn,
        "SELECT student_id FROM students WHERE usn='$usn'"
    );

    if (mysqli_num_rows($check) > 0) {

        $success_message = "USN already exists";

    } else {

        $sql = "INSERT INTO students
                (student_name, usn, semester, section)
                VALUES
                ('$student_name', '$usn', '$semester', '$section')";

        if (mysqli_query($conn, $sql)) {
            $success_message = "Student Added Successfully";
        } else {
            $success_message = "Student Could Not Be Added";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Student</title>

    <link rel="stylesheet" href="student_style.css">

</head>

<body>

<div class="container">

    <h1>Add Student</h1>

    <?php if ($success_message != "") { ?>

        <div class="message">
            <?php echo htmlspecialchars($success_message); ?>
        </div>

    <?php } ?>

    <form method="POST">

        <input
            type="text"
            name="student_name"
            placeholder="Enter Student Name"
            required
        >

        <input
            type="text"
            name="usn"
            placeholder="Enter USN"
            required
        >

        <input
            type="text"
            name="semester"
            placeholder="Enter Semester"
            required
        >

        <input
            type="text"
            name="section"
            placeholder="Enter Section"
            required
        >

        <button type="submit" name="add_student">
            Add Student
        </button>

    </form>

    <br>

    <a href="view_student.php">
        View Students
    </a>

    <br><br>

    <a href="dashboard.php">
        Back to Dashboard
    </a>

</div>

</body>

</html>