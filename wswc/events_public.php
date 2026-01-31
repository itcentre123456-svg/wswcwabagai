<?php
$conn = mysqli_connect("localhost", "root", "", "wswc");
$result = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date DESC");
?>
<!DOCTYPE html>
<html>
<head><title>All Events</title></head>
<body>
<h2>All Events</h2>
<?php if (mysqli_num_rows($result) === 0) { ?>
  <p>No events available</p>
<?php } else { ?>
  <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <div>
      <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
      <?php echo htmlspecialchars($row['event_date']); ?><br>
      <?php echo nl2br(htmlspecialchars($row['description'])); ?>
    </div>
    <hr>
  <?php } ?>
<?php } ?>
<p style="text-align:center;"><a href="index.php">Back to Home</a></p>
</body>
</html>