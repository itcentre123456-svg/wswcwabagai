<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Admin Dashboard</title></head>
<body>
<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
<p>Select a section to manage:</p>
<ul>
    <li><a href="events.php">Manage Events</a></li>
    <li><a href="notices.php">Manage Notices</a></li>
    <li><a href="members.php">Manage Members</a></li>
    <li><a href="gallery.php">Manage Gallery</a></li>
</ul>
<p><a href="logout.php">Logout</a></p>
</body>
</html>