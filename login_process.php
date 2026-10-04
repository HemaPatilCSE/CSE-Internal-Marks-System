<?php

session_start();

include("db.php");

$message = "";
$message_color = "";
$redirect_page = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $email = mysqli_real_escape_string($conn, $email);

    $sql = "SELECT * FROM users WHERE email='$email'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['fullname'];
            $_SESSION['role'] = $row['role'];

            $_SESSION['subject'] = "";

            if ($row['role'] == 'faculty') {

                $faculty_name = mysqli_real_escape_string(
                    $conn,
                    $row['fullname']
                );

                $q = mysqli_query(
                    $conn,
                    "SELECT subject
                     FROM faculty
                     WHERE faculty_name='$faculty_name'
                     LIMIT 1"
                );

                if ($q && mysqli_num_rows($q) > 0) {

                    $f = mysqli_fetch_assoc($q);

                    $_SESSION['subject'] = $f['subject'];
                }
            }

            $message = "Login Successful";
            $message_color = "#d4edda";
            $redirect_page = "dashboard.php";

        } else {

            $message = "Invalid Email or Password";
            $message_color = "#f8d7da";
            $redirect_page = "index.php";
        }

    } else {

        $message = "Invalid Email or Password";
        $message_color = "#f8d7da";
        $redirect_page = "index.php";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta http-equiv="refresh"
          content="2;url=<?php echo $redirect_page; ?>">

    <title>Login Status</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="login-container">

    <div style="
        background:<?php echo $message_color; ?>;
        color:#155724;
        padding:12px;
        margin-top:20px;
        border-radius:5px;
        text-align:center;
        font-weight:bold;
    ">

        <?php echo $message; ?>

    </div>

</div>

</body>

</html>