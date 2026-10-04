<?php

session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'faculty') {
    header("Location: dashboard.php");
    exit();
}

$message = "";

$students = mysqli_query(
    $conn,
    "SELECT * FROM students ORDER BY student_name"
);

if ($_SESSION['role'] == 'faculty') {

    $faculty_name = mysqli_real_escape_string(
        $conn,
        $_SESSION['user_name']
    );

    $faculty_query = mysqli_query(
        $conn,
        "SELECT subject FROM faculty
         WHERE faculty_name='$faculty_name'
         LIMIT 1"
    );

    $faculty_data = mysqli_fetch_assoc($faculty_query);

    if ($faculty_data) {

        $faculty_subject = mysqli_real_escape_string(
            $conn,
            $faculty_data['subject']
        );

        $subjects = mysqli_query(
            $conn,
            "SELECT * FROM subjects
             WHERE subject_name='$faculty_subject'"
        );

    } else {

        $subjects = mysqli_query(
            $conn,
            "SELECT * FROM subjects WHERE subject_id=0"
        );
    }

} else {

    $subjects = mysqli_query(
        $conn,
        "SELECT * FROM subjects ORDER BY subject_name"
    );
}

if (isset($_POST['add_marks'])) {

    $student_id = intval($_POST['student_id']);
    $subject_id = intval($_POST['subject_id']);

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

        $check = mysqli_query(
            $conn,
            "SELECT mark_id
             FROM marks
             WHERE student_id='$student_id'
             AND subject_id='$subject_id'"
        );

        if (mysqli_num_rows($check) > 0) {

            $message = "Marks already exist for this student and subject.";

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

            $sql = "INSERT INTO marks
                    (student_id, subject_id, ia1, ia2, ia3, cta, cie, eligibility_status)
                    VALUES
                    ('$student_id',
                     '$subject_id',
                     '$ia1',
                     '$ia2',
                     '$ia3',
                     '$cta',
                     '$cie',
                     '$status')";

            if (mysqli_query($conn, $sql)) {

                $message = "Marks Added Successfully. CIE = $cie. Status = $status";

            } else {

                $message = "Marks Could Not Be Added.";
            }
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

    <title>Add Marks</title>

    <link rel="stylesheet" href="student_style.css">

</head>

<body>

<div class="container">

    <h1>Enter Internal Marks</h1>

    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php } ?>

    <form method="POST">

        <label>Student</label>

        <select name="student_id" required>

            <option value="">
                Select Student
            </option>

            <?php while ($student = mysqli_fetch_assoc($students)) { ?>

                <option value="<?php echo $student['student_id']; ?>">

                    <?php
                    echo htmlspecialchars(
                        $student['student_name']
                        . " - "
                        . $student['usn']
                    );
                    ?>

                </option>

            <?php } ?>

        </select>

        <label>Subject</label>

        <select name="subject_id" required>

            <option value="">
                Select Subject
            </option>

            <?php while ($subject = mysqli_fetch_assoc($subjects)) { ?>

                <option value="<?php echo $subject['subject_id']; ?>">

                    <?php
                    echo htmlspecialchars(
                        $subject['subject_name']
                        . " - Semester "
                        . $subject['semester']
                    );
                    ?>

                </option>

            <?php } ?>

        </select>

        <input
            type="number"
            name="ia1"
            min="0"
            max="20"
            placeholder="IA1 Marks (0-20)"
            required
        >

        <input
            type="number"
            name="ia2"
            min="0"
            max="20"
            placeholder="IA2 Marks (0-20)"
            required
        >

        <input
            type="number"
            name="ia3"
            min="0"
            max="20"
            placeholder="IA3 Marks (0-20)"
            required
        >

        <input
            type="number"
            name="cta"
            min="0"
            max="10"
            placeholder="CTA Marks (0-10)"
            required
        >

        <button type="submit" name="add_marks">
            Add Marks
        </button>

    </form>

    <br>

    <a href="view_marks.php">
        View Marks
    </a>

    <br><br>

    <a href="dashboard.php">
        Back to Dashboard
    </a>

</div>

</body>

</html>