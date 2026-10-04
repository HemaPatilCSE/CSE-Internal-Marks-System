<?php

session_start();

include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['role'];

$search = "";
$result = null;
$message = "";


/* =========================
   STUDENT VIEW
   ========================= */

if ($role == 'student') {

    if (isset($_POST['view_student_marks'])) {

        $search = trim($_POST['usn']);

        if ($search == "") {

            $message = "Please enter your USN.";

        } else {

            $search_safe = mysqli_real_escape_string($conn, $search);

            $sql = "SELECT
                    marks.*,
                    students.student_name,
                    students.usn,
                    subjects.subject_name
                    FROM marks
                    JOIN students
                    ON marks.student_id = students.student_id
                    JOIN subjects
                    ON marks.subject_id = subjects.subject_id
                    WHERE students.usn = '$search_safe'
                    ORDER BY subjects.subject_name";

            $result = mysqli_query($conn, $sql);

            if (!$result) {
                die("Database Error: " . mysqli_error($conn));
            }

            if (mysqli_num_rows($result) == 0) {
                $message = "No marks found for this USN.";
            }
        }
    }
}


/* =========================
   ADMIN / FACULTY VIEW
   ========================= */

else {

    $sql = "SELECT
            marks.*,
            students.student_name,
            students.usn,
            subjects.subject_name
            FROM marks
            JOIN students
            ON marks.student_id = students.student_id
            JOIN subjects
            ON marks.subject_id = subjects.subject_id";

    if (isset($_POST['search_marks'])) {

        $search = trim($_POST['search']);

        if ($search != "") {

            $search_safe = mysqli_real_escape_string($conn, $search);

            $sql .= " WHERE students.student_name LIKE '%$search_safe%'
                      OR students.usn LIKE '%$search_safe%'
                      OR subjects.subject_name LIKE '%$search_safe%'";
        }
    }

    $sql .= " ORDER BY students.student_name";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Database Error: " . mysqli_error($conn));
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>View Marks</title>

    <link rel="stylesheet" href="student_style.css">

    <style>

        .marks-container {
            width: 95%;
            max-width: 1200px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.10);
        }

        .marks-container h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .search-box {
            text-align: center;
            margin-bottom: 25px;
        }

        .search-box input {
            width: 300px;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-box button {
            padding: 12px 20px;
            margin-left: 5px;
            border: none;
            border-radius: 5px;
            background: #007bff;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        .search-box button:hover {
            background: #0056b3;
        }

        .message {
            text-align: center;
            color: red;
            font-weight: bold;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background: #343a40;
            color: white;
            padding: 12px;
            border: 1px solid #ddd;
        }

        td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background: #f8f9fa;
        }

        .eligible {
            color: green;
            font-weight: bold;
        }

        .not-eligible {
            color: red;
            font-weight: bold;
        }

        .edit-btn {
            background: #ffc107;
            color: black;
            padding: 7px 10px;
            border-radius: 5px;
            text-decoration: none;
        }

        .delete-btn {
            background: #dc3545;
            color: white;
            padding: 7px 10px;
            border-radius: 5px;
            text-decoration: none;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 18px;
            background: #343a40;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background: #212529;
        }

    </style>

</head>

<body>

<div class="marks-container">

    <h1>View Marks</h1>


    <?php if ($role == 'student') { ?>

        <!-- STUDENT USN SEARCH -->

        <form method="POST" class="search-box">

            <input
                type="text"
                name="usn"
                placeholder="Enter USN"
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button type="submit" name="view_student_marks">
                View My Marks
            </button>

        </form>

    <?php } ?>


    <?php if ($role == 'admin' || $role == 'faculty') { ?>

        <!-- ADMIN / FACULTY SEARCH -->

        <form method="POST" class="search-box">

            <input
                type="text"
                name="search"
                placeholder="Search Student, USN or Subject"
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button type="submit" name="search_marks">
                Search
            </button>

        </form>

    <?php } ?>


    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php } ?>


    <?php if ($result && mysqli_num_rows($result) > 0) { ?>

        <table>

            <tr>

                <th>ID</th>

                <th>Student</th>

                <th>USN</th>

                <th>Subject</th>

                <th>IA1</th>

                <th>IA2</th>

                <th>IA3</th>

                <th>CTA</th>

                <th>CIE</th>

                <th>Status</th>

                <?php if ($role == 'admin' || $role == 'faculty') { ?>

                    <th>Edit</th>

                <?php } ?>

                <?php if ($role == 'admin') { ?>

                    <th>Delete</th>

                <?php } ?>

            </tr>


            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                <tr>

                    <td>
                        <?php echo $row['mark_id']; ?>
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
                        echo htmlspecialchars($row['subject_name']);
                        ?>
                    </td>

                    <td>
                        <?php echo $row['ia1']; ?>
                    </td>

                    <td>
                        <?php echo $row['ia2']; ?>
                    </td>

                    <td>
                        <?php echo $row['ia3']; ?>
                    </td>

                    <td>
                        <?php echo $row['cta']; ?>
                    </td>

                    <td>
                        <?php echo $row['cie']; ?>
                    </td>

                    <td>

                        <?php if ($row['eligibility_status'] == 'Eligible') { ?>

                            <span class="eligible">
                                Eligible
                            </span>

                        <?php } else { ?>

                            <span class="not-eligible">
                                Not Eligible
                            </span>

                        <?php } ?>

                    </td>


                    <?php if ($role == 'admin' || $role == 'faculty') { ?>

                        <td>

                            <a
                                class="edit-btn"
                                href="edit_marks.php?id=<?php echo $row['mark_id']; ?>"
                            >
                                Edit
                            </a>

                        </td>

                    <?php } ?>


                    <?php if ($role == 'admin') { ?>

                        <td>

                            <a
                                class="delete-btn"
                                href="delete_marks.php?id=<?php echo $row['mark_id']; ?>"
                                onclick="return confirm('Are you sure you want to delete these marks?');"
                            >
                                Delete
                            </a>

                        </td>

                    <?php } ?>

                </tr>

            <?php } ?>

        </table>

    <?php } ?>


    <?php if ($role == 'admin' || $role == 'faculty') { ?>

        <br>

        <a href="add_marks.php">
            Enter New Marks
        </a>

    <?php } ?>


    <br>
    <br>

    <a href="dashboard.php" class="back-btn">
        Back to Dashboard
    </a>

</div>

</body>

</html>