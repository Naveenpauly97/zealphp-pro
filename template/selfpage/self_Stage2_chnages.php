<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Selfmade Ninja Academy</title>
  <style>
    /* General reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Poppins", Arial, sans-serif;
      background: #ffffff;
      color: #000000;
    }

    /* Container width for pixel perfect */
    .container {
      width: 1200px;
      margin: 0 auto;
    }

    /* Header */
    header {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 25px 50px;
    }

    .logo {
      font-size: 22px;
      font-weight: 700;
      color: #ff6b00;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .register-top {
      border: 1px solid #ccc;
      border-radius: 20px;
      padding: 10px 20px;
      background: #fff;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      transition: 0.3s;
    }

    .register-top:hover {
      background: #ff6b00;
      color: #fff;
      border-color: #ff6b00;
    }

    /* Hero Section */
    .hero {
      text-align: center;
      margin-top: 60px;
      padding: 0 80px;
    }

    .hero h1 {
      font-size: 38px;
      font-weight: 700;
      line-height: 1.4;
    }

    .hero h1 span {
      color: #ff6b00;
    }

    .hero p {
      font-size: 15px;
      font-weight: 400;
      color: #555;
      margin-top: 18px;
      max-width: 780px;
      margin-left: auto;
      margin-right: auto;
      line-height: 1.6;
    }

    .hero-buttons {
      margin-top: 30px;
    }

    .btn-orange {
      background: #ff6b00;
      color: #fff;
      font-size: 14px;
      font-weight: 600;
      padding: 14px 30px;
      border-radius: 25px;
      border: none;
      margin-right: 15px;
      cursor: pointer;
    }

    .btn-outline {
      background: #fff;
      border: 1px solid #333;
      font-size: 14px;
      font-weight: 600;
      padding: 14px 30px;
      border-radius: 25px;
      cursor: pointer;
    }

    /* Cyber Security Section */
    .cyber {
      margin-top: 80px;
      padding: 0 80px;
      text-align: center;
    }

    .cyber h2 {
      font-size: 28px;
      font-weight: 700;
      line-height: 1.5;
    }

    .cyber h2 span {
      color: #ff6b00;
    }

    .cyber-content {
      margin-top: 50px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      gap: 60px;
    }

    .cyber-box {
      max-width: 460px;
      text-align: left;
    }

    .cyber-box h3 {
      font-size: 18px;
      font-weight: 600;
      color: #ff6b00;
      margin-bottom: 12px;
    }

    .cyber-box p {
      font-size: 14px;
      color: #555;
      line-height: 1.6;
    }

    .cyber img {
      width: 320px;
      border-radius: 12px;
    }

  </style>
</head>
<body>

  <!-- Header -->
  <header class="container">
    <div class="logo">SELFMADE <span style="color:#000">Ninja Academy</span></div>
    <button class="register-top">Register Now</button>
  </header>

  <!-- Hero Section -->
  <section class="hero container">
    <h1>Lorem <span>ipsum 1%</span> Dolar si amet consectetur adipiscing elit</h1>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    <div class="hero-buttons">
      <button class="btn-orange">New Secret Tips Master Cyber Security</button>
      <button class="btn-outline">Register Now</button>
    </div>
  </section>

  <!-- Cyber Security Section -->
  <section class="cyber container">
    <h2>Lorem ipsum <span>CYBER SECURITY</span> dolar si amet ?<br>How can you break through!</h2>
    <div class="cyber-content">
      <div class="cyber-box">
        <h3>How can you break through!</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
      </div>
      <div>
        <img src="https://via.placeholder.com/320x280" alt="Instructor">
      </div>
    </div>
  </section>

</body>
</html>
