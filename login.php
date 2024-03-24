<?php
error_reporting(E_ALL);
session_start();

// Include the database connection file
include 'dbconnect.php';

// Function to authenticate user
function authenticateUser($username, $password, $dbh) {
    // Prepare SQL statement
    $stmt = $dbh->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);

    // Get the result
    $user = $stmt->fetch();

    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['password'])) {
        return true; // Authentication successful
    }
    return false; // Authentication failed
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Authenticate user
    if (authenticateUser($username, $password, $dbh)) {
        $_SESSION['username'] = $username;
        // Redirect to desired page
        if (isset($_GET['page'])) {
            header("Location: ".$_GET['page'].".php");
            exit();
        }
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!-- HTML Form for login -->
<form method="post" action="">
    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username"><br>
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" value="Login">
</form>

<?php
// Display error message if authentication failed
if (isset($error)) {
    echo $error;
}
?>
