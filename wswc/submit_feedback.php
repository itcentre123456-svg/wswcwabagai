<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = htmlspecialchars($_POST["name"]);
  $message = htmlspecialchars($_POST["message"]);

  // Save to file or database
  file_put_contents("feedback.txt", "$name: $message\n", FILE_APPEND);

  echo "<p>Thank you for your feedback!</p>";
} else {
  echo "<p>Invalid request.</p>";
}
?>