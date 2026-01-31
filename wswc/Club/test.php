<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>THE WABAGAI SOCIAL WELFARE CLUB, WABAGAI</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f5f5f5;
      color: #333;
    }
    header > div {
      display: flex;
      align-items: center;
      max-width: 800px;
      margin: 0 auto;
      padding: 20px 15px;
      background-color: #004080;
      color: white;
      text-align: center;
    }
    #logo {
      max-width: 150px;
      height: auto;
    }
    header div > div {
      margin-left: 15px;
    }
    header h1 {
      margin: 0;
      font-size: 1.8em;
    }
    header p {
      margin: 5px 0 0 0;
      font-weight: bold;
    }
    .container {
      max-width: 800px;
      margin: 20px auto;
      background-color: white;
      padding: 20px 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    #gallery {
      display: flex;
      justify-content: center;
      gap: 12px;
      flex-wrap: wrap;
      margin-bottom: 20px;
    }
    #gallery img {
      max-width: 180px;
      border: 2px solid #004080;
      border-radius: 6px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      cursor: pointer;
      transition: transform 0.3s ease;
    }
    #gallery img:hover {
      transform: scale(1.05);
    }
    h2 {
      border-bottom: 2px solid #004080;
      padding-bottom: 8px;
      color: #004080;
    }
    #notice {
      margin-top: 15px;
      padding: 15px;
      background-color: #e7f0fd;
      border-left: 5px solid #004080;
      font-size: 1em;
    }
    .notice-gallery img {
      max-width: 250px;
      border: 2px solid #004080;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      cursor: pointer;
      transition: transform 0.3s ease;
    }
    .notice-gallery img:hover {
      transform: scale(1.05);
    }
    footer {
      background-color: #004080;
      color: white;
      text-align: center;
      padding: 12px 0;
      margin-top: 40px;
      font-size: 0.9em;
    }
  </style>
</head>
<body>

  <header>
    <div>
      <img id="logo" src="Club/club_logo.jpg" alt="Club Logo" />
      <div>
        <h1>THE WABAGAI SOCIAL WELFARE CLUB, WABAGAI</h1>
        <p>Wabagai Tera Urak, Kakching District, Manipur - 795103</p>
        <p>Estd. 1958</p>
      </div>
    </div>
  </header>

  <div class="container">
    <div id="gallery">
      <img src="Club/Club1.jpg" alt="Club Event 1" />
      <img src="Club/club2.jpg" alt="Club Event 2" />
      <img src="Club/club3.jpg" alt="Club Event 3" />
    </div>

    <h2>Notice</h2>
    <div id="notice">
      <div class="notice-gallery">
         <img src="Club/tapta_copy1.jpg" alt="Notice Image" />
         <p><?php echo nl2br(file_get_contents("Club/notice.txt")); ?></p>
      </div>
    </div>

  <footer>
    &copy; 2025 THE WABAGAI SOCIAL WELFARE CLUB. All rights reserved.
  </footer>

</body>
</html>