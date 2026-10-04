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

$select = "SELECT * FROM students WHERE student_id='$id'";

$result = mysqli_query($conn, $select);

if (mysqli_num_rows($result) != 1) {
    header("Location: view_student.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

$success_message = "";

if (isset($_POST['update_student'])) {

    $student_name = trim($_POST['student_name']);
    $usn = trim($_POST['usn']);
    $semester = trim($_POST['semester']);
    $section = trim($_POST['section']);

    $student_name = mysqli_real_escape_string($conn, $student_name);
    $usn = mysqli_real_escape_string($conn, $usn);
    $semester = mysqli_real_escape_string($conn, $semester);
    $section = mysqli_real_escape_string($conn, $section);

    $update = "UPDATE students
               SET student_name='$student_name',
                   usn='$usn',
                   semester='$semester',
                   section='$section'
               WHERE student_id='$id'";

    if (mysqli_query($conn, $update)) {

        $success_message = "Student Updated Successfully";

        $result = mysqli_query($conn, $select);
        $row = mysqli_fetch_assoc($result);

    } else {

        $success_message = "Student Update Failed";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Student</title>

    <link rel="stylesheet" href="student_style.css">

</head>

<body>

<div class="container">

    <h1>Edit Student</h1>

    <?php if ($success_message != "") { ?>

        <div class="message">
            <?php echo htmlspecialchars($success_message); ?>
        </div>

    <?php } ?>

    <form method="POST">

        <input
            type="text"
            name="student_name"
            value="<?php echo htmlspecialchars($row['student_name']); ?>"
            required
        >

        <input
            type="text"
            name="usn"
            value="<?php echo htmlspecialchars($row['usn']); ?>"
            required
        >

        <input
            type="text"
            name="semester"
            value="<?php echo htmlspecialchars($row['semester']); ?>"
            required
        >

        <input
            type="text"
            name="section"
            value="<?php echo htmlspecialchars($row['section']); ?>"
            required
        >

        <button type="submit" name="update_student">
            Update Student
        </button>

    </form>

    <br>

    <a href="view_student.php">
        Back to Students
    </a>

</div>

</body>

</html>