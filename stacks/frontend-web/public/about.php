<?php
// Start a PHP session
session_start();

// Redirect to the login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>About Page - Animal Translator</title>
  <link rel="stylesheet" href="css/style-about.css" />
  <link rel="stylesheet" href="css/styles.css" />
  <script src="js/scripts.js"></script>
  <style>
    /* Global body styling */
    body {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin: 0;
      text-align: center;
      background: url('whatevers') no-repeat center center fixed;
      background-size: cover;
      position: relative; /* makes sure content appears on top of background */
      overflow-x: hidden;
    }

    /* transparent navbar with blur effect */
    .top-nav {
      width: 100%;
      padding: 10px 20px;
      position: static; /* messed to change from sticky to static */
      background-color: rgba(255, 255, 255, 0);
      backdrop-filter: blur(5px);
      display: flex;
      justify-content: center;
      align-items: center;
      box-shadow: none;
      z-index: 10;
      height: 70px;
    }

    /* container to hold all the nav items */
    .nav-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      max-width: 1200px; 
      position: relative;
    }

    /* left links */
    .nav-links.left {
      display: flex;
      justify-content: flex-start;
      gap: 20px;
      position: absolute;
      left: 0;
    }

    /* middle logo */
    .logo {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      z-index: 5;
    }

    .logo img {
      width: 60px; 
      height: auto;
    }

    /* right links */
    .nav-links.right {
      display: flex;
      justify-content: flex-end;
      gap: 20px;
      position: absolute;
      right: 0;
    }

    /* styles for the links */
    .nav-links a {
      color: black;
      text-decoration: none;
      font-size: 18px;
      transition: background-color 0.3s ease;
    }

    .nav-links a:hover {
      background-color: rgba(255, 255, 255, 0.2);
      border-radius: 4px;
    }

    /* Welcome message styling */
    .welcome-message {
      margin: 0;
      padding: 0;
      color: #187795;
      font-weight: bold;
    }

    /* Auth links styling */
    .auth-links {
      display: flex;
      gap: 20px;
    }

    .auth-button {
      display: inline-block;
      padding: 8px 15px;
      background-color: #187795;
      color: white !important;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    .auth-button:hover {
      background-color: #145f75;
    }

    .feature-links {
      display: flex;
      gap: 20px;
    }

    /* bottom of page nav bar */
    .bottom-nav {
      width: 100%;
      padding: 15px 0;
      background-color: #187795;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
      border-radius: 0;
      position: relative;
      bottom: 0;
      border: none;
    }

    .bottom-nav a {
      color: white;
      text-decoration: none;
      padding: 0 20px;
      font-size: 20px;
      white-space: nowrap;
    }

    /* Image styles */
    .image-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 20px;
      background-color: #38686A;
      padding: 5px;
      width: 100%;
      margin: 20px 0;
    }

    .imagestyle {
      margin: 50px;
      width: 560px; 
      height: 280px; 
    }

    /* hero with big heading */
    .hero {
      height: 80vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #fff;
      text-align: center;
      position: relative;
      z-index: 1;
    }

    .hero::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('./pictures/cat-1.png') no-repeat center center;
      background-size: cover;
      filter: blur(5px); /* applies blur to the image */
      z-index: -1; 
    }

    /* Additional text styling */
    .divstyle {
      margin: 40px auto;
      font-size: 18px;
      color: #187795;
      font-family: Georgia, serif;
      max-width: 800px;
      line-height: 1.6;
      padding: 0 20px;
    }

    .linkstyle {
      margin-left: 100px;
      margin-right: 100px;
      font-size: 20px;
    }

    .iframestyle {
      margin: 50px;
    }

    .hero h1, .hero p {
      color: white; 
    }

    .translator-heading {
      font-family: 'Brush Script MT', 'Lucida Handwriting', cursive;
      font-size: 48px;
      color: #187795;
      margin: 30px 0;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 40px; 
    }

    
    .cta-button {
      padding: 15px 30px;
      background-color: #187795;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-size: 1.1em;
      transition: background-color 0.3s ease;
    }

    .cta-button:hover {
      background-color: #145f75;
    }

    
    .button-container {
      display: flex;
      justify-content: center;
      gap: 40px;
      margin: 20px 0;
    }

    .white-space {
      height: 40vh;
      background-color: white;
    }

    /* Mobile styles */
    @media (max-width: 768px) {
      .nav-links {
        display: none !important;
      }
      
      .mobile-menu-button {
        display: flex !important;
      }
    }
  </style>


</head>
<body>
  <div class="top-nav">
    <div class="nav-container">
      <!-- Mobile menu button -->
      <div class="mobile-menu-button" id="mobileMenuButton">
        <span></span>
        <span></span>
        <span></span>
      </div>

      <!-- Left-side links -->
      <div class="nav-links left">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <!-- Welcome message will be on the left side -->
        <?php
        if (isset($_SESSION['username'])) {
            echo '<p class="welcome-message">Welcome, ' . $_SESSION['username'] . '!</p>';
        } else {
            echo '<a href="register.php" class="auth-button">Register</a>';
        }
        ?>
      </div>

      <!-- middle logo -->
      <div class="logo">
        <a href="index.php"><img src="pictures/jake1.png" alt="Our Company" /></a>
      </div>

      <!-- right links -->
      <div class="nav-links right">
        <!-- Logout button will be on the right side -->
        <?php
        if (isset($_SESSION['username'])) {
            echo '<a href="logout.php" class="auth-button">Logout</a>';
        } else {
            echo '<a href="login.php" class="auth-button">Login</a>';
        }
        ?>
        <a href="#features">Features</a>
        <a href="#contact">Contact</a>
      </div>
    </div>
  </div>

  <!-- Mobile dropdown menu -->
  <div class="mobile-menu" id="mobileMenu">
    <a href="index.php">Home</a>
    <a href="about.php">About</a>
    <?php
    if (isset($_SESSION['username'])) {
        echo '<p>Welcome, ' . $_SESSION['username'] . '!</p>';
        echo '<a href="logout.php">Logout</a>';
    } else {
        echo '<a href="register.php">Register</a>';
        echo '<a href="login.php">Login</a>';
    }
    ?>
    <a href="#features">Features</a>
    <a href="#contact">Contact</a>
  </div>

  <!-- placeholder for navbar -->
  <div class="top-nav-placeholder"></div>

  <!-- hero section -->
  <header class="hero">
    <h1>About Us - Our Mission - Our Founders</h1>
    <p>Our founders are the lovely and luxurious Jaime and Abdu</p>
  </header>

  <div class="divstyle">
    From the first time we met in daycare, Jaime and I have tried talking to animals, we did not have many friends so we sought out furry friends instead!
    We decided to bring that sad childhood magic to reality with...
  </div>

  <div class="translator-heading">The Animal Translator</div>

    
  <div class="button-container" id="features">
    <a class="cta-button" href="https://www.broadinstitute.org/elephant/elephant-genome-project" target="_blank">Project Genome</a>
    <a class="cta-button" href="https://www.theguardian.com/technology/2022/dec/05/neuralink-animal-testing-elon-musk-investigation" target="_blank">TESLA Collaboration</a>
  </div>
    
  <div class="image-container">
    <div class="image-wrapper">
        <img src="pictures/deer2.jpg" class="imagestyle" alt="Here is a deer, an animal pictured in Snow White." />
        <div class="quote-overlay">"This product changed my deer's life!" - Happy Owner</div>
    </div>
    <div class="image-wrapper">
        <img src="pictures/squirrel.jpg" class="imagestyle" alt="Here is a baby squirrel, an animal also pictured in Snow White" />
        <div class="quote-overlay squirrel-overlay">"My squirrel finally understands me!" - Satisfied User</div>
    </div>
  </div>

  <!-- New pricing section with phone image -->
  <section class="pricing contact" id="contact">
    <div class="about-pricing-content">
        <h1>Contact Us</h1>
        <p>
            Please let us know if your software is failing as this could be an issue with the neurotransmitters we have imbedded in the iguanas under Cupertino, CA.
        </p>
        <a href="https://github.com/jaymeedoescode/Landing-Page-Animal-Translator" class="cta-button" target="_blank">Go Now</a>
    </div>
  </section>

  <!-- New white space div -->
  <div class="white-space">
    <div class="legal-fine-print">
        <p>
            Animal Translator and Co. are not responsible for any loss of pet, animal uprising, or world domination. 
            By using our services, you acknowledge that communication with animals may lead to unexpected outcomes, including but not limited to:
        </p>
        <ul>
            <li>Increased demands for treats and attention.</li>
            <li>Unforeseen animal behavior changes.</li>
            <li>Potential for animals to form unions or alliances.</li>
            <li>Heightened expectations from your furry friends.</li>
            <li>Possibility of your pet becoming the new leader of the household.</li>
        </ul>
        <p>
            
<!-- PURCHASE ACTIONS SECTION -->
<div class="purchase-actions">
    <div class="purchase-card">
        <h2>🛒 Purchase Options</h2>
        <a href="makePurchase.php" class="purchase-btn">Buy NOW!</a>
    </div>

    <div class="purchase-card">
        <h2>📦 Manage Purchases</h2>
        <a href="getPurchase.php" class="purchase-btn">Find a Previous Purchase</a>
        <a href="updatePurchase.php" class="purchase-btn">Update a Purchase</a>
        <a href="refundPurchase.php" class="purchase-btn">Refund a Purchase</a>
    </div>
</div>

<style>
.purchase-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
    margin-top: 40px;
}

.purchase-card {
    background-color: #f4f9fb;
    border: 2px solid #187795;
    border-radius: 12px;
    padding: 25px;
    min-width: 280px;
    max-width: 320px;
    text-align: center;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.purchase-card h2 {
    color: #187795;
    margin-bottom: 20px;
}

.purchase-btn {
    display: block;
    margin: 10px auto;
    padding: 12px 20px;
    background-color: #187795;
    color: white;
    text-decoration: none;
    font-size: 16px;
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

.purchase-btn:hover {
    background-color: #145f75;
}
</style>


Please consult with your veterinarian or animal behaviorist before making any significant changes to your pet's routine based on translations provided by our service. 
            Animal Translator and Co. disclaims any liability for damages resulting from the use of our services.
        </p>
       

        


  </div>

  <div class="bottom-nav" style="background-color: #187795; color: white; padding: 5px 0; text-align: center;">
    <a href="index.php" style="color: white; margin: 0 15px;">Home</a>
    <a href="#features" style="color: white; margin: 0 15px;">Features</a>
    <a href="about.php" style="color: white; margin: 0 15px;">About</a>
    <a href="#contact" style="color: white; margin: 0 15px;">Contact</a>
  </div>

  <script>
    // JavaScript to toggle mobile menu
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');

    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('open');
        mobileMenuButton.classList.toggle('open');
    });

    // fun to see if element is in viewport
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // fun for scroll animation
    function handleScrollAnimation() {
        const element = document.querySelector('.translator-heading');
        if (isElementInViewport(element)) {
            element.classList.add('visible');
        }
    }

    // adds scroll event listener
    window.addEventListener('scroll', handleScrollAnimation);
    // looks at first load 
    handleScrollAnimation();
  </script>
</body>
</html>