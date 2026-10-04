<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Internal Marks System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="login-container">

    <h2>Create Account</h2>

    <form action="signup_process.php" method="POST">

        <input
            type="text"
            name="fullname"
            placeholder="Enter Full Name"
            required
        >

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

        <select name="role" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="faculty">Faculty</option>
            <option value="student">Student</option>
        </select>

        <button type="submit">
            Signup
        </button>

    </form>

    <p class="bottom-text">
        Already have an account?
        <a href="index.php">Login</a>
    </p>

</div>

</body>

</html>