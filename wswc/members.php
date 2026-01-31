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
    $stmt = $conn->prepare("SELECT photo FROM members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($photoPath);
    $stmt->fetch();
    $stmt->close();

    // Delete record
    $stmt = $conn->prepare("DELETE FROM members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Delete photo file if exists
    if (!empty($photoPath) && file_exists($photoPath)) {
        unlink($photoPath);
    }

    header("Location: members.php");
    exit;
}

// Handle new member submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $photoPath = NULL;

    // Handle file upload
    if (!empty($_FILES['photo']['name'])) {
        $targetDir = "uploads/members/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . basename($_FILES["photo"]["name"]);
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            $photoPath = $targetFile;
        }
    }

    $stmt = $conn->prepare("INSERT INTO members (name, role, photo) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $role, $photoPath);
    $stmt->execute();
    $stmt->close();
}

// Handle photo update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_photo_id'])) {
    $id = intval($_POST['update_photo_id']);
    $photoPath = NULL;

    if (!empty($_FILES['change_photo']['name'])) {
        $targetDir = "uploads/members/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . basename($_FILES["change_photo"]["name"]);
        if (move_uploaded_file($_FILES["change_photo"]["tmp_name"], $targetFile)) {
            $photoPath = $targetFile;
        }
    }

    if ($photoPath) {
        // Fetch old photo to delete
        $stmt = $conn->prepare("SELECT photo FROM members WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($oldPhoto);
        $stmt->fetch();
        $stmt->close();

        if (!empty($oldPhoto) && file_exists($oldPhoto)) {
            unlink($oldPhoto);
        }

        // Update new photo
        $stmt = $conn->prepare("UPDATE members SET photo = ? WHERE id = ?");
        $stmt->bind_param("si", $photoPath, $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: members.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM members ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Members</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    h2, h3 { text-align: center; }
    form { margin-bottom: 20px; text-align: center; }
    .member-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      justify-content: center;
    }
    .member-card {
      border: 1px solid #ccc;
      padding: 10px;
      width: 180px;
      text-align: center;
      border-radius: 6px;
      background: #f9f9f9;
    }
    .member-card img {
      max-width: 100%;
      height: auto;
      border-radius: 5px;
    }
    .member-name { font-weight: bold; margin-top: 5px; }
    .member-role { font-size: 14px; color: #555; margin-bottom: 5px; }
    .delete-link { color: red; font-size: 13px; text-decoration: none; }
    .delete-link:hover { text-decoration: underline; }
  </style>
</head>
<body>
<h2>Members</h2>

<form method="post" enctype="multipart/form-data">
    Name: <input type="text" name="name" required><br><br>
    Role: <input type="text" name="role" required><br><br>
    Photo: <input type="file" name="photo" accept="image/*"><br><br>
    <button type="submit">Add Member</button>
</form>

<h3>Existing Members</h3>
<div class="member-grid">
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <div class="member-card">
        <?php if (!empty($row['photo']) && file_exists($row['photo'])) { ?>
            <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="photo">
        <?php } else { ?>
            <div style="font-size:40px;">👤</div>
        <?php } ?>

        <form method="post" enctype="multipart/form-data" style="margin-top:10px;">
            <input type="hidden" name="update_photo_id" value="<?php echo $row['id']; ?>">
            Change Photo: <input type="file" name="change_photo" accept="image/*"><br><br>
            <button type="submit">Update Photo</button>
        </form>

        <div class="member-name"><?php echo htmlspecialchars($row['name']); ?></div>
        <div class="member-role"><?php echo htmlspecialchars($row['role']); ?></div>
        <a href="members.php?delete_id=<?php echo $row['id']; ?>" 
           class="delete-link" 
           onclick="return confirm('Delete this member?');">Delete</a>
    </div>
<?php } ?>
</div>

<p style="text-align:center;"><a href="admin.php">Back to Dashboard</a></p>
</body>
</html>