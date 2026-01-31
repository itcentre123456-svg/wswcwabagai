<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$conn = mysqli_connect("localhost", "root", "", "wswc");
if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }

// DELETE EVENT
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();

    // Refresh the page after deleting
    header("Location: events.php");
    exit;
}

// ADD EVENT
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $date = $_POST['event_date'];
    $desc = $_POST['description'];
    $stmt = $conn->prepare("INSERT INTO events (title, event_date, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $date, $desc);
    $stmt->execute();
    $stmt->close();
}

// GET ALL EVENTS
$result = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Manage Events</title></head>
<body>
<h2>Events</h2>

<form method="post">
    Title: <input type="text" name="title" required><br><br>
    Date: <input type="date" name="event_date" required><br><br>
    Description: <textarea name="description"></textarea><br><br>
    <button type="submit">Add Event</button>
</form>

<h3>Existing Events</h3>
<ul>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <li>
        <?php echo htmlspecialchars($row['title']); ?> 
        (<?php echo $row['event_date']; ?>) -
        <?php echo htmlspecialchars($row['description']); ?>

        <br>
        <a href="events.php?delete_id=<?php echo $row['id']; ?>" 
           onclick="return confirm('Delete this event?');">
           Delete
        </a>
    </li>
<?php } ?>
</ul>

<p><a href="admin.php">Back to Dashboard</a></p>

</body>
</html>
