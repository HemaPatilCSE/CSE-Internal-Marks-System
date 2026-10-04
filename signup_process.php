<?php

include("db.php");

$message = "";
$message_color = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    $fullname = mysqli_real_escape_string($conn, $fullname);
    $email = mysqli_real_escape_string($conn, $email);
    $role = mysqli_real_escape_string($conn, $role);

    $check_email = "SELECT id FROM users WHERE email = '$email'";

    $result = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($result) > 0) {

        $message = "Email already exists";
        $message_color = "#f8d7da";

    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users(fullname, email, password, role)
                VALUES('$fullname', '$email', '$hashed_password', '$role')";

        if (mysqli_query($conn, $sql)) {

            $message = "Signup Successful";
            $message_color = "#d4edda";

        } else {

            $message = "Signup Failed";
            $message_color = "#f8d7da";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="2;url=index.php">
    <title>Signup Status</title>
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