<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['trtkm24a_8'];
    $password = $_POST['bT6EXxWX'];

    // Check credentials (these could be stored in a database or hardcoded for simplicity)
    if ($username == "username" && password_verify($password, 'password')) {
        $_SESSION['loggedin'] = true;
        header("Location: admin_dashboard.php");
    } else {
        $error = "Invalid login credentials.";
    }
}
?>

<!-- HTML login form -->
<form method="post">
    <label for="username">Username:</label>
    <input type="text" name="username" required><br>
    <label for="password">Password:</label>
    <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>

<?php if (isset($error)) echo $error; ?>
