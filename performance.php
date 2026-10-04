<?php

session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

/* Total students */

$student_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM students"
);

$student_data = mysqli_fetch_assoc($student_query);

$total_students = $student_data['total'];


/* Eligible students */

$eligible_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM marks
     WHERE eligibility_status='Eligible'"
);

$eligible_data = mysqli_fetch_assoc($eligible_query);

$total_eligible = $eligible_data['total'];


/* Not eligible students */

$not_eligible_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM marks
     WHERE eligibility_status='Not Eligible'"
);

$not_eligible_data = mysqli_fetch_assoc($not_eligible_query);

$total_not_eligible = $not_eligible_data['total'];


/* Topper */

$topper_query = mysqli_query(
    $conn,
    "SELECT
        students.student_name,
        students.usn,
        subjects.subject_name,
        marks.cie
     FROM marks
     JOIN students
        ON marks.student_id = students.student_id
     JOIN subjects
        ON marks.subject_id = subjects.subject_id
     ORDER BY marks.cie DESC
     LIMIT 1"
);

$topper = mysqli_fetch_assoc($topper_query);


/* Failed students */

$failed_query = mysqli_query(
    $conn,
    "SELECT
        students.student_name,
        students.usn,
        subjects.subject_name,
        marks.cie
     FROM marks
     JOIN students
        ON marks.student_id = students.student_id
     JOIN subjects
        ON marks.subject_id = subjects.subject_id
     WHERE marks.eligibility_status='Not Eligible'
     ORDER BY marks.cie ASC"
);


/* Subject-wise average CIE */

$subject_query = mysqli_query(
    $conn,
    "SELECT
        subjects.subject_name,
        AVG(marks.cie) AS average_cie
     FROM marks
     JOIN subjects
        ON marks.subject_id = subjects.subject_id
     GROUP BY subjects.subject_id
     ORDER BY subjects.subject_name"
);

$subject_names = [];
$subject_averages = [];

while ($subject = mysqli_fetch_assoc($subject_query)) {

    $subject_names[] = $subject['subject_name'];

    $subject_averages[] = round(
        $subject['average_cie'],
        2
    );
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Performance Analysis</title>

    <link rel="stylesheet" href="student_style.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>

        .performance-card-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .performance-card {
            flex: 1;
            min-width: 200px;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .performance-card h2 {
            margin-bottom: 10px;
        }

        .performance-card p {
            font-size: 30px;
            font-weight: bold;
        }

        .chart-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .topper-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .failed-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        @media (max-width: 700px) {

            .performance-card-container {
                flex-direction: column;
            }

        }

    </style>

</head>

<body>

<div class="container" style="max-width:1200px;">

    <h1>Performance Analysis</h1>

    <!-- Summary -->

    <div class="performance-card-container">

        <div class="performance-card">

            <h2>Total Students</h2>

            <p>
                <?php echo $total_students; ?>
            </p>

        </div>


        <div class="performance-card">

            <h2>Eligible</h2>

            <p>
                <?php echo $total_eligible; ?>
            </p>

        </div>


        <div class="performance-card">

            <h2>Not Eligible</h2>

            <p>
                <?php echo $total_not_eligible; ?>
            </p>

        </div>

    </div>


    <!-- Topper -->

    <div class="topper-box">

        <h2>Topper Details</h2>

        <br>

        <?php if ($topper) { ?>

            <p>
                <strong>Name:</strong>
                <?php
                echo htmlspecialchars(
                    $topper['student_name']
                );
                ?>
            </p>

            <p>
                <strong>USN:</strong>
                <?php
                echo htmlspecialchars(
                    $topper['usn']
                );
                ?>
            </p>

            <p>
                <strong>Subject:</strong>
                <?php
                echo htmlspecialchars(
                    $topper['subject_name']
                );
                ?>
            </p>

            <p>
                <strong>CIE:</strong>
                <?php echo $topper['cie']; ?>
            </p>

        <?php } else { ?>

            <p>
                No marks available for topper analysis.
            </p>

        <?php } ?>

    </div>


    <!-- Chart -->

    <div class="chart-container">

        <h2>Eligible vs Not Eligible</h2>

        <canvas id="eligibilityChart"></canvas>

    </div>


    <!-- Subject Average Chart -->

    <div class="chart-container">

        <h2>Subject-wise Average CIE</h2>

        <canvas id="subjectChart"></canvas>

    </div>


    <!-- Failed Students -->

    <div class="failed-box">

        <h2>Failed Students</h2>

        <br>

        <table>

            <tr>

                <th>Student Name</th>
                <th>USN</th>
                <th>Subject</th>
                <th>CIE</th>

            </tr>

            <?php

            if (mysqli_num_rows($failed_query) > 0) {

                while ($failed = mysqli_fetch_assoc($failed_query)) {

            ?>

            <tr>

                <td>
                    <?php
                    echo htmlspecialchars(
                        $failed['student_name']
                    );
                    ?>
                </td>

                <td>
                    <?php
                    echo htmlspecialchars(
                        $failed['usn']
                    );
                    ?>
                </td>

                <td>
                    <?php
                    echo htmlspecialchars(
                        $failed['subject_name']
                    );
                    ?>
                </td>

                <td>
                    <?php echo $failed['cie']; ?>
                </td>

            </tr>

            <?php

                }

            } else {

            ?>

            <tr>

                <td colspan="4">
                    No failed students found.
                </td>

            </tr>

            <?php } ?>

        </table>

    </div>


    <a href="dashboard.php">
        Back to Dashboard
    </a>

</div>


<script>

const eligible = <?php echo $total_eligible; ?>;

const notEligible = <?php echo $total_not_eligible; ?>;

new Chart(
    document.getElementById('eligibilityChart'),
    {
        type: 'bar',

        data: {

            labels: [
                'Eligible',
                'Not Eligible'
            ],

            datasets: [
                {
                    label: 'Students',

                    data: [
                        eligible,
                        notEligible
                    ]
                }
            ]

        },

        options: {
            responsive: true
        }

    }
);


const subjectNames =
    <?php echo json_encode($subject_names); ?>;

const subjectAverages =
    <?php echo json_encode($subject_averages); ?>;


new Chart(
    document.getElementById('subjectChart'),
    {
        type: 'bar',

        data: {

            labels: subjectNames,

            datasets: [
                {
                    label: 'Average CIE',

                    data: subjectAverages
                }
            ]

        },

        options: {
            responsive: true,

            scales: {

                y: {
                    beginAtZero: true
                }

            }

        }

    }
);

</script>

</body>

</html>