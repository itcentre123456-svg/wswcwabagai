<?php
$conn = mysqli_connect("localhost", "root", "", "wswc");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $newpass = $_POST['new_password'];

    // Generate a new hash
    $hash = password_hash($newpass, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE username = ?");
    $stmt->bind_param("ss", $hash, $username);

    if ($stmt->execute()) {
        $message = "Password updated successfully!";
    } else {
        $message = "Error updating password.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head><title>Forgot Password</title></head>
<body>
<h2>Reset Password</h2>
<?php if (!empty($message)) echo "<p style='color:green;'>$message</p>"; ?>
<form method="post">
    Username: <input type="text" name="username" required><br><br>
    New Password: <input type="password" name="new_password" required><br><br>
    <button type="submit">Reset Password</button>
</form>
</body>
</html>