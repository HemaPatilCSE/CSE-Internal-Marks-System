<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Internal Marks System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="login-container">

    <h2>Internal Marks System</h2>

    <form action="login_process.php" method="POST">

        <input
            type="email"
            name="email"
            placeholder="Enter Email"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Enter Password"
            required
        >

        <button type="submit">
            Login
        </button>

    </form>

    <p class="bottom-text">
        Don't have an account?
        <a href="signup.php">Signup</a>
    </p>

</div>

</body>

</html>