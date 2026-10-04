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

$sql = "SELECT * FROM subjects WHERE subject_id='$id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 1) {
    header("Location: view_subject.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

$message = "";

if (isset($_POST['update_subject'])) {

    $subject_name = trim($_POST['subject_name']);
    $semester = trim($_POST['semester']);

    $subject_name = mysqli_real_escape_string($conn, $subject_name);
    $semester = mysqli_real_escape_string($conn, $semester);

    $update = "UPDATE subjects
               SET subject_name='$subject_name',
                   semester='$semester'
               WHERE subject_id='$id'";

    if (mysqli_query($conn, $update)) {

        $message = "Subject Updated Successfully";

        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

    } else {

        $message = "Subject Update Failed";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Subject</title>

    <link rel="stylesheet" href="student_style.css">

</head>

<body>

<div class="container">

    <h1>Edit Subject</h1>

    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php } ?>

    <form method="POST">

        <input
            type="text"
            name="subject_name"
            value="<?php echo htmlspecialchars($row['subject_name']); ?>"
            required
        >

        <input
            type="text"
            name="semester"
            value="<?php echo htmlspecialchars($row['semester']); ?>"
            required
        >

        <button type="submit" name="update_subject">
            Update Subject
        </button>

    </form>

    <br>

    <a href="view_subject.php">
        Back to Subjects
    </a>

</div>

</body>

</html>