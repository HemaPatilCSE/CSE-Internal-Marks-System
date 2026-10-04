<?php

session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM faculty";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Faculty List</title>

    <link rel="stylesheet" href="student_style.css">

</head>

<body>

<div class="container">

    <h1>Faculty List</h1>

    <table>

        <tr>

            <th>ID</th>
            <th>Faculty Name</th>
            <th>Subject</th>

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
                <?php echo $row['faculty_id']; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['faculty_name']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['subject']); ?>
            </td>

            <?php if ($_SESSION['role'] == 'admin') { ?>

                <td>

                    <a
                        class="edit-btn"
                        href="edit_faculty.php?id=<?php echo $row['faculty_id']; ?>"
                    >
                        Edit
                    </a>

                </td>

                <td>

                    <a
                        class="delete-btn"
                        href="delete_faculty.php?id=<?php echo $row['faculty_id']; ?>"
                        onclick="return confirm('Are you sure you want to delete this faculty?');"
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
                No Faculty Found
            </td>

        </tr>

        <?php } ?>

    </table>

    <br>

    <?php if ($_SESSION['role'] == 'admin') { ?>

        <a href="add_faculty.php">
            Add New Faculty
        </a>

        <br><br>

    <?php } ?>

    <a href="dashboard.php">
        Back to Dashboard
    </a>

</div>

</body>

</html>