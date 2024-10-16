<?php
// Initialize the session
session_start();

// Check if the user is already logged in that will redirect to home page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.php");
    exit;
}
?>

<!-- Sign in that will GET from database -->
<?php
$success = 0;
$deny = 0;

// If server connects properly
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'config.php';
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use a prepared statement to prevent SQL injection
    $sql = "SELECT * FROM `users` WHERE username = :username AND password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':password' => $password
    ]);

    // Check if the user exists in the database
    if ($stmt->rowCount() > 0) {
        $success = 1;
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header('location:home.php');
    } else {
        $deny = 1;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Link to styles.css -->
    <link rel="stylesheet" href="index-styles.css">
    <title>Sign in</title>
</head>
<body>
    <form action="" method="POST">
        <div class="container">
            <h1>Sign in</h1>
            <p>Please fill out this form to login.</P>

            <label><b>Username</b></label>
            <input type="text" name="username" required>

            <label><b>Password</b></label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>

            <!-- If Login Successful -->
            <?php 
            if ($success) {
                echo '
                <div class="alert success">
                    <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span> 
                    <strong>Yay!</strong> You may now proceed to hell :)
                </div>';
            } ?>

            <!-- If Login not Successful -->
            <?php
            if ($deny) {
                echo '
                <div class="alert danger">
                    <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span> 
                    <strong>Oops!</strong> Wrong Username or Password
                </div>';
            } ?>

            <label>
                <input type="checkbox" name="remember"> Remember me
            </label>
        </div>

        <div class="container signup">
            <p>Don't have an account? <a href="signup.php">Sign Up</a>.</p>
        </div>
    </form>
</body>
</html>