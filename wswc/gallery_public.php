<?php
$conn = mysqli_connect("localhost", "root", "", "wswc");
$result = mysqli_query($conn, "SELECT * FROM gallery ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Gallery</title></head>
<body>
<h2>Gallery</h2>
<div style="display:flex; flex-wrap:wrap; gap:10px;">
<?php if (mysqli_num_rows($result) === 0) { ?>
  <p>No photos available</p>
<?php } else { ?>
  <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <div style="border:1px solid #ccc; padding:5px;">
      <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" style="max-width:200px;">
      <p><?php echo htmlspecialchars($row['title']); ?></p>
    </div>
  <?php } ?>
<?php } ?>
</div>
<p style="text-align:center;"><a href="index.php">Back to Home</a></p>
</body>
</html>