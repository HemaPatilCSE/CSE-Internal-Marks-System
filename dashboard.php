<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['role'];
$name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="dashboard_style.css">
</head>

<body>

<div class="sidebar">

    <h2>Internal Marks System</h2>

    <ul>

        <li>
            <a href="dashboard.php">Dashboard</a>
        </li>

        <?php if ($role == 'admin') { ?>

            <li>
                <a href="add_student.php">Students</a>
            </li>

            <li>
                <a href="add_faculty.php">Faculty</a>
            </li>

            <li>
                <a href="add_subject.php">Subjects</a>
            </li>

        <?php } ?>

        <?php if ($role == 'admin' || $role == 'faculty') { ?>

            <li>
                <a href="add_marks.php">Marks</a>
            </li>

        <?php } ?>

        <li>
            <a href="view_marks.php">View Marks</a>
        </li>

        <li>
            <a href="performance.php">Reports</a>
        </li>

        <li>
            <a href="logout.php">Logout</a>
        </li>

    </ul>

</div>


<div class="main-content">

    <h1>Welcome, <?php echo htmlspecialchars($name); ?></h1>

    <p class="role-text">
        Role:
        <strong><?php echo htmlspecialchars($role); ?></strong>
    </p>


    <div class="card-container">

        <?php if ($role == 'admin') { ?>

            <div class="card">
                <h2>Students</h2>
                <a href="add_student.php">Manage Students</a>
            </div>

            <div class="card">
                <h2>Faculty</h2>
                <a href="add_faculty.php">Manage Faculty</a>
            </div>

            <div class="card">
                <h2>Subjects</h2>
                <a href="add_subject.php">Manage Subjects</a>
            </div>

            <div class="card">
                <h2>Marks</h2>
                <a href="add_marks.php">Enter Marks</a>
            </div>

            <div class="card">
                <h2>Reports</h2>
                <a href="performance.php">Performance Analysis</a>
            </div>

        <?php } ?>


        <?php if ($role == 'faculty') { ?>

            <div class="card">
                <h2>Students</h2>
                <a href="view_student.php">View Students</a>
            </div>

            <div class="card">
                <h2>Subjects</h2>
                <a href="view_subject.php">View Subjects</a>
            </div>

            <div class="card">
                <h2>Marks</h2>
                <a href="add_marks.php">Enter Marks</a>
            </div>

            <div class="card">
                <h2>View Marks</h2>
                <a href="view_marks.php">View Marks</a>
            </div>

            <div class="card">
                <h2>Reports</h2>
                <a href="performance.php">Performance Analysis</a>
            </div>

        <?php } ?>


        <?php if ($role == 'student') { ?>

            <div class="card">
                <h2>My Marks</h2>
                <a href="view_marks.php">View My Marks</a>
            </div>

            <div class="card">
                <h2>Performance</h2>
                <a href="performance.php">Performance Analysis</a>
            </div>

        <?php } ?>

    </div>
<br><br>

<a href="logout.php" class="logout">Logout</a>
</div>

</body>
</html>