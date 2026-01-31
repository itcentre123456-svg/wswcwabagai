<?php
$conn = mysqli_connect("localhost", "root", "", "wswc");
$result = mysqli_query($conn, "SELECT * FROM members ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head><title>All Members</title></head>
<body>
<h2>Current Members</h2>
<?php if (mysqli_num_rows($result) === 0) { ?>
  <p>No members available</p>
<?php } else { ?>
  <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <div>
      <?php if (!empty($row['photo'])) { ?>
        <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="member photo" style="max-height:100px;">
      <?php } ?>
      <strong><?php echo htmlspecialchars($row['name']); ?></strong> - <?php echo htmlspecialchars($row['role']); ?>
    </div>
    <hr>
  <?php } ?>
<?php } ?>
<p style="text-align:center;"><a href="index.php">Back to Home</a></p>
</body>
</html>