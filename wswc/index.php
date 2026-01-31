<?php
$conn = mysqli_connect("localhost", "root", "", "wswc");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch latest events
$events = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date DESC");

// Fetch latest notices
$notices = mysqli_query($conn, "SELECT * FROM notices ORDER BY created_at DESC");

// Fetch members
$members = mysqli_query($conn, "SELECT * FROM members ORDER BY id ASC");

// Fetch gallery images
$gallery = mysqli_query($conn, "SELECT * FROM gallery ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Wabagai Social Welfare Club</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="top-bar">
  <div class="logo">
    <img src="Club/club_logo.jpg" alt="Wabagai Social Welfare Club logo">
  </div>
  <div class="header-title">
    <h1>ꯋꯕꯒꯥꯢ ꯁꯣꯁꯤꯌꯦꯜ ꯋꯦꯜꯐꯤꯌꯔ ꯀ꯭ꯂꯕ</h1>
    <p>OFFICE OF THE</p>
    <h2>WABAGAI SOCIAL WELFARE CLUB (WSWC)</h2>
    <p>Wabagai Tera Urak, PO Kakching, Kakching District, Manipur-795103</p>
    <p>Estd. 1958 | Regd. No. 107/1962</p>
  </div>
</header>

<div class="layout">

<!-- LEFT: Events -->
<?php $eventCount = mysqli_num_rows($events); $shown = 0; ?>
<section class="events">
  <h3>Recent Year's Events</h3>
  <?php if ($eventCount === 0) { ?>
    <p>No events available</p>
  <?php } else { ?>
    <?php while ($row = mysqli_fetch_assoc($events)) { 
      if ($shown >= 4) break; ?>
      <div class="event-box">
        <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
        <?php echo htmlspecialchars($row['event_date']); ?><br>
        <?php echo nl2br(htmlspecialchars($row['description'])); ?>
      </div>
    <?php $shown++; } ?>
    <?php if ($eventCount > 4) { ?>
      <div style="text-align:center; margin-top:10px;">
        <a href="events_public.php" class="more-link">+ More Events</a>
      </div>
    <?php } ?>
  <?php } ?>
</section>

  <!-- CENTER: Notice, Members, About -->
  <main class="center">
    <section class="notice">
      <h3>Anouncement</h3>
      <?php $noticeCount = mysqli_num_rows($notices); $shown = 0; ?>
      <?php if ($noticeCount === 0) { ?>
        <div class="notice-inner">
          <div class="notice-text" style="width:100%; text-align:center; padding:10px;">
            No notice available
          </div>
        </div>
      <?php } else { ?>
        <?php while ($row = mysqli_fetch_assoc($notices)) {
          if ($shown >= 2) break; ?>
          <div class="notice-inner">
            <?php if (!empty($row['photo'])) { ?>
              <div class="notice-image">
                <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="notice photo">
              </div>
            <?php } ?>
            <div class="notice-text" <?php if (empty($row['photo'])) echo 'style="grid-column:1/span 2;"'; ?>>
              <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
              <?php echo nl2br(htmlspecialchars($row['body'])); ?><br>
              <?php if (!empty($row['link'])) { ?>
                <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank">Visit this Website</a>
              <?php } ?>
            </div>
          </div>
        <?php $shown++; } ?>
        <?php if ($noticeCount > 2) { ?>
          <div style="text-align:center; margin-top:10px;">
            <a href="notices_public.php" class="more-link">+ More Notice</a>
          </div>
        <?php } ?>
      <?php } ?>
    </section>

    <section class="members">
      <h3>Current Members</h3>
      <div class="member-row">
        <?php $memberCount = mysqli_num_rows($members); $shown = 0; ?>
        <?php if ($memberCount === 0) { ?>
          <p>No members available</p>
        <?php } else { ?>
          <?php while ($row = mysqli_fetch_assoc($members)) {
            if ($shown >= 3) break; ?>
            <div class="member">
              <div class="member-photo">
                <?php if (!empty($row['photo'])) { ?>
                  <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="<?php echo htmlspecialchars($row['role']); ?>"
                       onerror="this.style.display='none'; this.parentNode.textContent='👤';">
                <?php } else { ?> 👤 <?php } ?>
              </div>
              <div class="member-text">
                <div class="member-name"><?php echo htmlspecialchars($row['name']); ?></div>
                <div class="member-role"><?php echo htmlspecialchars($row['role']); ?></div>
              </div>
            </div>
          <?php $shown++; } ?>
          <?php if ($memberCount > 3) { ?>
            <div class="member more">
              <div class="member-photo">+</div>
              <div class="member-text">
                <div class="member-name">More</div>
                <div class="member-role"><a href="members_public.php" class="more-link">+ More Members</a></div>
              </div>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
    </section>

    <section class="about">
      <h2>About Us</h2>
      <p>
        The Wabagai Social Welfare Club (WSWC), established in 1958, is dedicated to community upliftment,
        cultural preservation, and educational support in Wabagai Tera Urak, Kakching District.
        For over six decades, the club has organized various social services including medical camps,
        educational workshops, and disaster relief activities, embodying the spirit of mutual assistance.</p><p>This is the Club's Rules and Regulations.<a href="Club/RULES AND REGULATIONS.pdf" target="_blank">Open Rules and Regulations</a></p>
<p>And this is the recognised organisation list of the Manipur Government<a href=https://rcsmanipur.gov.in/wp-content/uploads/2022/09/msr-kakching.pdf" target="_blank">Open this and Look at page 20</a>
      </p>
    </section>

<!-- FEEDBACK SECTION -->
<section class="feedback">
  <h3>Feedback</h3>
  <form action="submit_feedback.php" method="post">
    <label for="name">Your Name:</label><br>
    <input type="text" id="name" name="name" required><br><br>

    <label for="message">Your Feedback:</label><br>
    <textarea id="message" name="message" rows="4" required></textarea><br><br>

    <button type="submit">Submit</button>
  </form>
</section>

<!-- CONTACT -->
<section class="contact-social">
  <h3>Contact Us</h3>
  <div class="social-icons">
    <a href="https://www.facebook.com/YourPage" target="_blank" title="Facebook">
      <img src="/wswc/Club/gallery/1000_F_580400358_j1Zi4rHaDHQV0Y2ERBpXxlZs9A20E5s2copy.png" alt="Facebook">
    </a>
    <a href="https://twitter.com/YourHandle" target="_blank" title="Twitter">
      <img src="/wswc/Club/gallery/1000_F_580400358_j1Zi4rHaDHQV0Y2ERBpXxlZs9A20E5s200000.png" alt="Twitter">
    </a>
    <a href="https://wa.me/919xxxxxxxxx" target="_blank" title="WhatsApp">
      <img src="/wswc/Club/gallery/1000_F_580400358_j1Zi4rHaDHQV0Y2ERBpXxlZs9A20E5s2.png" alt="WhatsApp">
    </a>
    <a href="mailto:itcentre.12345@gmail.com" title="Email">
      <img src="/wswc/Club/gallery/1000_F_580400358_j1Zi4rHaDHQV0Y2ERBpXxlZs9A20E5s2000.png" alt="Email">
    </a>
    <a href="tel:+919378022836" target="_blank" title="Call Us">
      <img src="/wswc/Club/gallery/1000_F_580400358_j1Zi4rHaDHQV0Y2ERBpXxlZs9A20E5s20.png" alt="WhatsApp">
    </a>
  </div>
</section>  
</main>

  <!-- RIGHT: Gallery + Location -->
  <aside class="right-side">
    <?php $galleryCount = mysqli_num_rows($gallery); $shown = 0; ?>
    <section class="gallery">
      <h3>Gallery</h3>
      <div class="gallery-grid">
        <?php if ($galleryCount === 0) { ?>
          <p>No photos available</p>
        <?php } else { ?>
          <?php while ($row = mysqli_fetch_assoc($gallery)) {
            if ($shown >= 4) break; ?>
            <div class="gallery-item">
              <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
            </div>
          <?php $shown++; } ?>
        <?php } ?>
      </div>
      <?php if ($galleryCount > 3) { ?>
        <div style="text-align:center; margin-top:10px;">
          <a href="gallery_public.php" class="more-link">+ More Photos</a>
        </div>
      <?php } ?>
    </section>

    <section class="location">
      <h3>Location</h3>
      <img src="/wswc/Club/location_map.jpg" alt="Location photo">
      <p>
        📍 <a href="https://maps.app.goo.gl/4DTs3ZLPtidRYvnY7"
              target="_blank" rel="noopener noreferrer">
          View Club Location on Google Maps
        </a>
      </p>
    </section>
  </aside>
</div>

<footer class="footer">
  &copy; Wabagai Social Welfare Club. We respect your privacy,<?php echo date('Y'); ?>
</footer>

</body>
</html>