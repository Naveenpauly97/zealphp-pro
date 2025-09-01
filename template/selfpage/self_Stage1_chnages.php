<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Selfmade Ninja Academy</title>
  <style>
    /* Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      line-height: 1.6;
      background: #fff;
      color: #333;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 8%;
      border-bottom: 1px solid #eee;
    }

    header .logo {
      display: flex;
      align-items: center;
      font-weight: bold;
      color: #ff6600;
      font-size: 1.2rem;
    }

    header .register-btn {
      padding: 10px 20px;
      border: 1px solid #333;
      border-radius: 25px;
      background: #fff;
      cursor: pointer;
      transition: 0.3s;
    }

    header .register-btn:hover {
      background: #ff6600;
      color: #fff;
      border: 1px solid #ff6600;
    }

    /* Hero Section */
    .hero {
      text-align: center;
      padding: 60px 10%;
    }

    .hero h1 {
      font-size: 2.2rem;
      font-weight: bold;
    }

    .hero h1 span {
      color: #ff6600;
    }

    .hero p {
      max-width: 650px;
      margin: 20px auto;
      color: #555;
    }

    .hero .buttons {
      margin-top: 25px;
    }

    .hero .btn-orange {
      background: #ff6600;
      color: #fff;
      padding: 12px 25px;
      border: none;
      border-radius: 25px;
      margin-right: 15px;
      cursor: pointer;
      transition: 0.3s;
    }

    .hero .btn-orange:hover {
      background: #e05500;
    }

    .hero .btn-outline {
      border: 1px solid #333;
      padding: 12px 25px;
      border-radius: 25px;
      background: #fff;
      cursor: pointer;
      transition: 0.3s;
    }

    .hero .btn-outline:hover {
      background: #ff6600;
      color: #fff;
      border-color: #ff6600;
    }

    /* Cyber Security Section */
    .cyber {
      background: #fafafa;
      padding: 60px 10%;
      text-align: center;
    }

    .cyber h2 {
      font-size: 1.8rem;
      font-weight: bold;
    }

    .cyber h2 span {
      color: #ff6600;
    }

    .cyber-content {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      align-items: center;
      margin-top: 40px;
    }

    .cyber-box {
      padding: 20px;
      border-left: 4px solid #ff6600;
      background: #fff;
      text-align: left;
      border-radius: 10px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.05);
    }

    .cyber-box h3 {
      color: #ff6600;
      margin-bottom: 10px;
    }

    .cyber-box p {
      color: #555;
    }

    .cyber img {
      width: 100%;
      border-radius: 15px;
    }

    /* Responsive */
    @media(max-width: 900px) {
      .cyber-content {
        grid-template-columns: 1fr;
      }
      header {
        flex-direction: column;
      }
      .hero h1 {
        font-size: 1.6rem;
      }
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header>
    <div class="logo">SELFMADE Ninja Academy</div>
    <button class="register-btn">Register Now</button>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <h1>Lorem <span>ipsum 1%</span> Dolar si amet consectetur adipiscing elit</h1>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
    <div class="buttons">
      <button class="btn-orange">New Secret Tips Master Cyber Security</button>
      <button class="btn-outline">Register Now</button>
    </div>
  </section>

  <!-- Cyber Security Section -->
  <section class="cyber">
    <h2>Lorem ipsum <span>CYBER SECURITY</span> dolar si amet? <br>How can you break through!</h2>

    <div class="cyber-content">
      <div class="cyber-box">
        <h3>How can you break through!</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis aute irure dolor in reprehenderit in voluptate velit esse.</p>
      </div>
      <div>
        <img src="https://via.placeholder.com/400x300" alt="Instructor">
      </div>
    </div>
  </section>

</body>
</html>
