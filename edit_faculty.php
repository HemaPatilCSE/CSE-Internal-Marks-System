<?php

session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: view_faculty.php");
    exit();
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM faculty WHERE faculty_id='$id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 1) {
    header("Location: view_faculty.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

$message = "";

if (isset($_POST['update_faculty'])) {

    $faculty_name = trim($_POST['faculty_name']);
    $subject = trim($_POST['subject']);

    $faculty_name = mysqli_real_escape_string($conn, $faculty_name);
    $subject = mysqli_real_escape_string($conn, $subject);

    $update = "UPDATE faculty
               SET faculty_name='$faculty_name',
                   subject='$subject'
               WHERE faculty_id='$id'";

    if (mysqli_query($conn, $update)) {

        $message = "Faculty Updated Successfully";

        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

    } else {

        $message = "Faculty Update Failed";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Faculty</title>

    <link rel="stylesheet" href="student_style.css">

</head>

<body>

<div class="container">

    <h1>Edit Faculty</h1>

    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php } ?>

    <form method="POST">

        <input
            type="text"
            name="faculty_name"
            value="<?php echo htmlspecialchars($row['faculty_name']); ?>"
            required
        >

        <input
            type="text"
            name="subject"
            value="<?php echo htmlspecialchars($row['subject']); ?>"
            required
        >

        <button type="submit" name="update_faculty">
            Update Faculty
        </button>

    </form>

    <br>

    <a href="view_faculty.php">
        Back to Faculty
    </a>

</div>

</body>

</html>