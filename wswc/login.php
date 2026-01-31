<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "wswc");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hash);
        $stmt->fetch();

        if (password_verify($password, $hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: admin.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
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

    <title>WSWC Login</title>

    <!-- PWA Requirements -->
    <link rel="manifest" href="/wswc/manifest.json">
    <meta name="theme-color" content="#0c3b77">

    <!-- Icons -->
    <link rel="icon" href="/wswc/Club/icon/icon192.png">
    <link rel="apple-touch-icon" href="/wswc/Club/icon/icon512.png">

    <!-- Service Worker Registration -->
    <script>
        if ("serviceWorker" in navigator) {
            navigator.serviceWorker.register("/wswc/sw.js")
            .then(reg => console.log("Service Worker registered:", reg))
            .catch(err => console.error("SW registration failed:", err));
        }
    </script>

    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }

        input {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h2>Login</h2>

<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="post">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>

<p><a href="forgot_password.php">Forgot Password?</a></p>

</body>
</html>
