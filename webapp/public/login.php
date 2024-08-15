<?php

session_start();
include './components/loggly-logger.php';

$hostname = 'backend-mysql-database';
$username = 'user';
$password = 'supersecretpw';
$database = 'password_manager';

// Create a new mysqli instance and check the connection
$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Implementing rate limiting for brute force protection
    $maxAttempts = 2; // Maximum number of login attempts allowed
    $lockoutDuration = 20;

    if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= $maxAttempts) {
        $errorMessage = 'Too many login attempts. Please try again later.';
        $logger->warning("Potential brute force attempt blocked for username: $username");
        exit(); // Stop further execution
    }

    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ? AND approved = 1");
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userFromDB = $result->fetch_assoc();

        $_SESSION['authenticated'] = $username; 

        if ($userFromDB['default_role_id'] == 1) {        
            $_SESSION['isSiteAdministrator'] = 1;               
        } else {
            unset($_SESSION['isSiteAdministrator']); 
        }
        $logger->info("Successful login for username: $username");

        // Reset login attempts on successful login
        if (isset($_SESSION['login_attempts'])) {
            unset($_SESSION['login_attempts']);
        }

        header("Location: index.php");
        exit();
    } else {
        $errorMessage = 'Invalid username or password.';
        $logger->warning("Login failed for username: $username");

        // Count login attempts and store in session
        if (isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts']++;
        } else {
            $_SESSION['login_attempts'] = 1;
        }
    }

    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Login Page</title>
</head>
<body>
    <div class="container mt-5">
        <div class="col-md-6 offset-md-3">
            <h2 class="text-center">Login</h2>
            <?php if (isset($errorMessage)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            <div class="mt-3 text-center">
                <a href="./users/request_account.php" class="btn btn-secondary btn-block">Request an Account</a>
            </div>
        </div>
    </div>
</body>
</html>
