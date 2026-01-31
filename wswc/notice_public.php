<?php
$conn = mysqli_connect("localhost", "root", "Itcenter001@", "wswc");
if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }

$result = mysqli_query($conn, "SELECT * FROM notices ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>All Notices</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .notice-inner {
      display: grid;
      grid-template-columns: 140px 1fr;
      gap: 8px;
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 15px;
    }
    .notice-image img { max-width: 100%; max-height: 120px; object-fit: cover; }
    .notice-text { padding: 5px; }
    .notice-text strong { font-size: 18px; }
  </style>
</head>
<body>
<h2>All Notices</h2>
<?php if (mysqli_num_rows($result) === 0) { ?>
  <p>No notice available</p>
<?php } else { ?>
  <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <div class="notice-inner">
      <?php if (!empty($row['photo'])) { ?>
        <div class="notice-image">
          <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="notice photo">
        </div>
      <?php } ?>
      <div class="notice-text">
        <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
        <?php echo nl2br(htmlspecialchars($row['body'])); ?><br>
        <?php if (!empty($row['link'])) { ?>
          <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank">Read more</a>
        <?php } ?>
      </div>
    </div>
  <?php } ?>
<?php } ?>
<p style="text-align:center;"><a href="index.php">Back to Home</a></p>
</body>
</html>