<?php
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

$conn = mysqli_connect("localhost", "root", "", "wswc");
if (!$conn) { 
    die("Connection failed: " . mysqli_connect_error()); 
}

// Handle delete
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    // Fetch photo path before deleting
    $stmt = $conn->prepare("SELECT photo FROM notices WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($photoPath);
    $stmt->fetch();
    $stmt->close();

    // Delete record
    $stmt = $conn->prepare("DELETE FROM notices WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Delete photo file if exists and not default
    if (!empty($photoPath) && file_exists($photoPath) && strpos($photoPath, "default_notice.png") === false) {
        unlink($photoPath);
    }

    header("Location: notices.php");
    exit;
}

// Handle new notice submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $body  = $_POST['body'];
    $link  = !empty($_POST['link']) ? $_POST['link'] : NULL;
    $photoPath = NULL;

    // Handle file upload
    if (!empty($_FILES['photo']['name'])) {
        $targetDir = "uploads/notices/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . basename($_FILES["photo"]["name"]);
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            $photoPath = $targetFile;
        }
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO notices (title, body, photo, link) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $body, $photoPath, $link);
    $stmt->execute();
    $stmt->close();
}

$result = mysqli_query($conn, "SELECT * FROM notices ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Notices</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2, h3 { text-align: center; }
        form { margin-bottom: 20px; }
        .notice-inner {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 8px;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
        }
        .notice-image {
            border: 1px dashed #888;
            min-height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .notice-image img {
            max-width: 100%;
            max-height: 120px;
            object-fit: cover;
        }
        .notice-text {
            border: 1px dashed #888;
            padding: 5px;
            min-height: 80px;
        }
        .notice-text strong { font-size: 18px; }
        a { color: red; text-decoration: none; }
    </style>
</head>
<body>
<h2>Notices</h2>

<form method="post" enctype="multipart/form-data">
    <label>Title:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Body:</label><br>
    <textarea name="body" required></textarea><br><br>

    <label>Photo (upload like gallery):</label><br>
    <input type="file" name="photo" accept="image/*"><br><br>

    <label>Website Link:</label><br>
    <input type="url" name="link" placeholder="https://example.com"><br><br>

    <button type="submit">Add Notice</button>
</form>

<h3>Existing Notices</h3>
<?php if (mysqli_num_rows($result) === 0) { ?>
    <div class="notice-inner">
        <div class="notice-text" style="width:100%; text-align:center; padding:10px;">
            No notice available
        </div>
    </div>
<?php } else { ?>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <?php if (empty($row['title']) && empty($row['body'])) { ?>
            <div class="notice-inner">
                <div class="notice-text" style="width:100%; text-align:center; padding:10px;">
                    No notice available
                </div>
            </div>
        <?php } elseif (!empty($row['title']) || !empty($row['body'])) { ?>
            <?php if (!empty($row['photo'])) { ?>
                <!-- Photo + text -->
                <div class="notice-inner">
                    <div class="notice-image">
                        <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="notice photo">
                    </div>
                    <div class="notice-text">
                        <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
                        <?php echo nl2br(htmlspecialchars($row['body'])); ?><br>
                        <?php if (!empty($row['link'])) { ?>
                            <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank">Visit this Website</a><br>
                        <?php } ?>
                        <a href="notices.php?delete_id=<?php echo $row['id']; ?>" 
                           onclick="return confirm('Delete this notice and its photo?');">Delete</a>
                    </div>
                </div>
            <?php } else { ?>
                <!-- Only text -->
                <div class="notice-inner">
                    <div class="notice-text" style="grid-column: 1 / span 2;">
                        <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
                        <?php echo nl2br(htmlspecialchars($row['body'])); ?><br>
                        <?php if (!empty($row['link'])) { ?>
                            <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank">Visit this Website</a><br>
                        <?php } ?>
                        <a href="notices.php?delete_id=<?php echo $row['id']; ?>" 
                           onclick="return confirm('Delete this notice?');">Delete</a>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    <?php } ?>
<?php } ?>

<p style="text-align:center;"><a href="admin.php">Back to Dashboard</a></p>
</body>
</html>