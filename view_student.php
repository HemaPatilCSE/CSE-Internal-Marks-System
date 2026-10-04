<?php

session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['role'];

if ($role != 'admin' && $role != 'faculty') {
    header("Location: dashboard.php");
    exit();
}

$search = "";

$sql = "SELECT * FROM students";

if (isset($_POST['search'])) {

    $search = trim($_POST['search']);

    $search = mysqli_real_escape_string($conn, $search);

    $sql = "SELECT * FROM students
            WHERE student_name LIKE '%$search%'
            OR usn LIKE '%$search%'
            OR semester LIKE '%$search%'
            OR section LIKE '%$search%'";
}

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Students List</title>

    <link rel="stylesheet" href="student_style.css">

</head>

<body>

<div class="container">

    <h1>Students List</h1>

    <form method="POST">

        <input
            type="text"
            name="search"
            placeholder="Search Student or USN"
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <button type="submit">
            Search
        </button>

    </form>

    <br>

    <table>

        <tr>

            <th>ID</th>

            <th>Student Name</th>

            <th>USN</th>

            <th>Semester</th>

            <th>Section</th>

            <?php if ($role == 'admin') { ?>

                <th>Edit</th>

                <th>Delete</th>

            <?php } ?>

        </tr>

        <?php

        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {

        ?>

        <tr>

            <td>
                <?php echo $row['student_id']; ?>
            </td>

            <td>
                <?php
                echo htmlspecialchars($row['student_name']);
                ?>
            </td>

            <td>
                <?php
                echo htmlspecialchars($row['usn']);
                ?>
            </td>

            <td>
                <?php
                echo htmlspecialchars($row['semester']);
                ?>
            </td>

            <td>
                <?php
                echo htmlspecialchars($row['section']);
                ?>
            </td>

            <?php if ($role == 'admin') { ?>

                <td>

                    <a
                        class="edit-btn"
                        href="edit_student.php?id=<?php echo $row['student_id']; ?>"
                    >
                        Edit
                    </a>

                </td>

                <td>

                    <a
                        class="delete-btn"
                        href="delete_student.php?id=<?php echo $row['student_id']; ?>"
                        onclick="return confirm('Are you sure you want to delete this student?');"
                    >
                        Delete
                    </a>

                </td>

            <?php } ?>

        </tr>

        <?php

            }

        } else {

        ?>

        <tr>

            <td colspan="7">
                No students found
            </td>

        </tr>

        <?php } ?>

    </table>

    <br>

    <?php if ($role == 'admin') { ?>

        <a href="add_student.php">
            Add New Student
        </a>

        <br><br>

    <?php } ?>

    <a href="dashboard.php">
        Back to Dashboard
    </a>

</div>

</body>

</html>