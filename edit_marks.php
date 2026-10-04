<?php

session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (
    $_SESSION['role'] != 'admin' &&
    $_SESSION['role'] != 'faculty'
) {
    header("Location: dashboard.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: view_marks.php");
    exit();
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM marks WHERE mark_id='$id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 1) {
    header("Location: view_marks.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

$message = "";

if (isset($_POST['update_marks'])) {

    $ia1 = intval($_POST['ia1']);
    $ia2 = intval($_POST['ia2']);
    $ia3 = intval($_POST['ia3']);
    $cta = intval($_POST['cta']);

    if (
        $ia1 < 0 || $ia1 > 20 ||
        $ia2 < 0 || $ia2 > 20 ||
        $ia3 < 0 || $ia3 > 20 ||
        $cta < 0 || $cta > 10
    ) {

        $message = "Invalid Marks! IA maximum is 20 and CTA maximum is 10.";

    } else {

        $ia_array = [$ia1, $ia2, $ia3];

        rsort($ia_array);

        $best_two_ia = $ia_array[0] + $ia_array[1];

        $cie = $best_two_ia + $cta;

        if ($cie >= 20) {
            $status = "Eligible";
        } else {
            $status = "Not Eligible";
        }

        $update = "UPDATE marks
                   SET ia1='$ia1',
                       ia2='$ia2',
                       ia3='$ia3',
                       cta='$cta',
                       cie='$cie',
                       eligibility_status='$status'
                   WHERE mark_id='$id'";

        if (mysqli_query($conn, $update)) {

            $message = "Marks Updated Successfully. CIE = $cie. Status = $status";

            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);

        } else {

            $message = "Marks Update Failed";
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

    <title>Edit Marks</title>

    <link rel="stylesheet" href="student_style.css">

</head>

<body>

<div class="container">

    <h1>Edit Marks</h1>

    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php } ?>

    <p>
        <strong>Student ID:</strong>
        <?php echo $row['student_id']; ?>
    </p>

    <br>

    <p>
        <strong>Subject ID:</strong>
        <?php echo $row['subject_id']; ?>
    </p>

    <br>

    <form method="POST">

        <input
            type="number"
            name="ia1"
            min="0"
            max="20"
            value="<?php echo $row['ia1']; ?>"
            placeholder="IA1 Marks"
            required
        >

        <input
            type="number"
            name="ia2"
            min="0"
            max="20"
            value="<?php echo $row['ia2']; ?>"
            placeholder="IA2 Marks"
            required
        >

        <input
            type="number"
            name="ia3"
            min="0"
            max="20"
            value="<?php echo $row['ia3']; ?>"
            placeholder="IA3 Marks"
            required
        >

        <input
            type="number"
            name="cta"
            min="0"
            max="10"
            value="<?php echo $row['cta']; ?>"
            placeholder="CTA Marks"
            required
        >

        <button type="submit" name="update_marks">
            Update Marks
        </button>

    </form>

    <br>

    <a href="view_marks.php">
        Back to Marks
    </a>

</div>

</body>

</html>