<?php

session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM subjects";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Subjects List</title>

    <link rel="stylesheet" href="student_style.css">

</head>

<body>

<div class="container">

    <h1>Subjects List</h1>

    <table>

        <tr>

            <th>ID</th>
            <th>Subject Name</th>
            <th>Semester</th>

            <?php if ($_SESSION['role'] == 'admin') { ?>

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
                <?php echo $row['subject_id']; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['subject_name']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['semester']); ?>
            </td>

            <?php if ($_SESSION['role'] == 'admin') { ?>

                <td>

                    <a
                        class="edit-btn"
                        href="edit_subject.php?id=<?php echo $row['subject_id']; ?>"
                    >
                        Edit
                    </a>

                </td>

                <td>

                    <a
                        class="delete-btn"
                        href="delete_subject.php?id=<?php echo $row['subject_id']; ?>"
                        onclick="return confirm('Are you sure you want to delete this subject?');"
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

            <td colspan="5">
                No Subjects Found
            </td>

        </tr>

        <?php } ?>

    </table>

    <br>

    <?php if ($_SESSION['role'] == 'admin') { ?>

        <a href="add_subject.php">
            Add New Subject
        </a>

        <br><br>

    <?php } ?>

    <a href="dashboard.php">
        Back to Dashboard
    </a>

</div>

</body>

</html>