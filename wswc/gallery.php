<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$conn = mysqli_connect("localhost", "root", "", "wswc");
if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }

// Handle delete
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    // First get image path to delete file
    $res = mysqli_query($conn, "SELECT image FROM gallery WHERE id=$id");
    if ($row = mysqli_fetch_assoc($res)) {
        if (!empty($row['image']) && file_exists($row['image'])) {
            unlink($row['image']); // delete file from server
        }
    }
    $stmt = $conn->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: gallery.php"); // refresh page
    exit;
}

// Handle new gallery submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image'])) {
    $title = $_POST['title'];
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) { mkdir($targetDir, 0777, true); }
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);

    $stmt = $conn->prepare("INSERT INTO gallery (title, image) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $targetFile);
    $stmt->execute();
    $stmt->close();
}

$result = mysqli_query($conn, "SELECT * FROM gallery ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Manage Gallery</title></head>
<body>
<h2>Gallery</h2>
<form method="post" enctype="multipart/form-data">
    Title: <input type="text" name="title" required><br><br>
    Image: <input type="file" name="image" required><br><br>
    <button type="submit">Add Image</button>
</form>

<h3>Existing Gallery</h3>
<ul>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <li>
        <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
        <?php if (!empty($row['image'])) { ?>
            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="gallery image" style="max-width:150px;height:auto;">
        <?php } ?>
        <br>
        <a href="gallery.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this image?');">Delete</a>
    </li>
<?php } ?>
</ul>
<p><a href="admin.php">Back to Dashboard</a></p>
</body>
</html>