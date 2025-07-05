<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Animal Translator</title>
  <meta name="description" content="Animal Translator - Understand your pet's thoughts with our advanced translation app." />
  <link rel="stylesheet" href="css/styles.css" />
  <script src="js/scripts.js"></script>
</head>
<body>
  <!-- transparent navbar with blur effect -->
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
        <?php
        session_start();
        if (isset($_SESSION['username'])) {
            // Display welcome message on left side when logged in
            echo '<p class="welcome-message">Welcome, ' . $_SESSION['username'] . '!</p>';
        } else {
            // Display login button on left side when not logged in
            echo '<a href="login.php" class="auth-button">Login</a>';
        }
        ?>
      </div>

      <!-- middle logo -->
      <div class="logo">
        <a href="index.php"><img src="pictures/jake1.png" alt="Our Company" /></a>
      </div>

      <!-- right links -->
      <div class="nav-links right">
        <?php
        if (isset($_SESSION['username'])) {
            // Only show logout button on right side when logged in
            echo '<a href="logout.php" class="auth-button">Logout</a>';
        } else {
            // Display register button on right side when not logged in
            echo '<a href="register.php" class="auth-button">Register</a>';
        }
        ?>
        <a href="#pricing">Pricer</a>
        <a href="#contact">Contact Us</a>
      </div>
    </div>
  </div>

  <!-- Mobile dropdown menu -->
  <div class="mobile-menu" id="mobileMenu">
    <a href="index.php">Home</a>
    <a href="about.php">About</a>
    <?php
    if (isset($_SESSION['username'])) {
        echo '<p class="welcome-message">Welcome, ' . $_SESSION['username'] . '!</p>';
        echo '<a href="logout.php">Logout</a>';
    } else {
        echo '<a href="login.php">Login</a>';
        echo '<a href="register.php">Register</a>';
    }
    ?>
    <a href="#pricing">Pricing</a>
    <a href="#contact">Contact Us</a>
  </div>

  <!-- hero Section with blurred raccoon background -->
  <header class="hero">
    <h1>Welcome to Animal Translator!</h1>
    <p>Track, analyze, and understand your pet like never before!</p>
    <a href="https://www.newyorker.com/science/elements/the-challenges-of-animal-translation" class="cta-button" target="_blank">Learn More</a>
    <link rel="preload" href="pictures/raccoon.jpg" as="image">
  </header>

  <div class="divstyle">
    Experience the future of pet interaction with real-time translations of your pet's thoughts and emotions.
    Our app bridges the communication gap between you and your furry friends.
  </div>

  <div class="translator-heading">The Animal Translator</div>

  <div class="image-container">
    <img src="pictures/dog-1.jpg" class="imagestyle" alt="A person using the Animal Translator app with their dog" width="560" height="280" />
    <img src="pictures/dog-2.jpg" class="imagestyle" alt="Features of the Animal Translator app" width="560" height="280" />
  </div>

  <!-- pricing with money.jpg as background -->
  <section class="pricing" id="pricing">
    <div class="pricing-content">
      <h1>Pricing</h1>
      <p>
        We have all sorts of deals! Originally the base price was $9999.99 but for today only we are offering it for $20! Buy now!
      </p>
      <a href="makePurchase.php">
        <button>BUY NOW!!</button>
      </a>
    </div>
  </section>

  <!-- Contact Us Section  -->
  <section class="pricing contact" id="contact">
    <div class="pricing-content">
      <h1>Contact Us</h1>
      <p>
        Please let us know if your software is failing as this could be an issue with the neurotransmitters we have imbedded in the iguanas under Cupertino, CA.
      </p>
      <a href="https://github.com/jaymeedoescode/Landing-Page-Animal-Translator" class="cta-button" target="_blank">Go Now</a>
    </div>
  </section>

  <!-- New bottom navigation links -->
  <div class="bottom-nav" style="background-color: #187795; color: white; padding: 10px; text-align: center;">
    <a href="index.php" style="color: white; margin: 0 15px;">Home</a>
    <a href="#pricing" style="color: white; margin: 0 15px;">Pricing</a>
    <a href="#contact" style="color: white; margin: 0 15px;">Contact Us</a>
    <a href="about.php" style="color: white; margin: 0 15px;">About</a>
  </div>

  <script>
    function isElementInViewport(el) {
      const rect = el.getBoundingClientRect();
      return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
      );
    }

    function handleScrollAnimation() {
      const element = document.querySelector('.translator-heading');
      if (isElementInViewport(element)) {
        element.classList.add('visible');
      }
    }

    window.addEventListener('scroll', handleScrollAnimation);
    handleScrollAnimation();

    // JavaScript to toggle mobile menu
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');

    mobileMenuButton.addEventListener('click', () => {
      mobileMenu.classList.toggle('open');
      mobileMenuButton.classList.toggle('open');
    });
  </script>
</body>
</html>