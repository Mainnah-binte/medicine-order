<?php
include('includes/config.php');
include('includes/db.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>MediCart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #ddfdfd, #f5f7fb);
            scroll-behavior: smooth;
            overflow-x: hidden;
        }
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }
        .navbar {
    background: rgba(255, 255, 255, 0.8);
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
        .navbar-brand {
            display: flex;
            align-items: center;
        }
        .navbar-brand img {
            height: 50px;
            width: 55px;
            margin-right: 8px;
            border-radius: 30px;
        }
        .navbar-brand span {
            font-size: 28px;
            font-weight: 900;
            color: #0d6efd;
        }
        .nav-link {
            font-weight: 600;
            color: #333;
        }
        .nav-link:hover {
            color: #0d6efd;
        }
        section {
            padding: 90px 0;
        }
        h2 {
            font-weight: 900;
            margin-bottom: 40px;
            font-family: cursive;
        }
        #home {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 80px;
        }
        .home-box {
            background: #fff;
            padding: 90px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            text-align: center;
            max-width: 850px;
            width: 100%;
        }
        .home-box h1 {
            font-size: 50px;
            font-weight: 800;
        }
        .home-box p {
            font-size: 18px;
            color: #555;
        }
        .btn-custom {
            width: 180px;
            font-size: 20px;
            border-radius: 60px;
            font-weight: 700;
        }
        .btn-login {
            background: #0d6efd;
            color: white;
        }
        .btn-register {
            background: #20c997;
            color: white;
        }
        .about-box,.feature-box,.contact-box {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        .about-box {
            padding: 35px;
        }
        .about-box p {
            color: #555;
            line-height: 1.7;
        }
        .feature-box {
            padding: 30px;
            height: 100%;
        }
        .feature-box img {
            width: 65px;
            margin-bottom: 12px;
        }
        .feature-box h4 {
            font-weight: 700;
        }
        .feature-box p {
            color: #666;
        }
        .contact-box {
            padding: 30px;
        }
        footer {
            background: #f8f9fa;
            padding: 50px 0 20px;
            border-top: 1px solid #e0e0e0;
            position: relative;
        }
        .footer-logo span {
            font-size: 24px;
            font-weight: 900;
            color: #0d6efd;
        }
        .footer-links h5 {
            font-weight: 700;
            margin-bottom: 20px;
            color: #333;
        }
        .footer-links ul {
            list-style: none;
            padding: 0;
        }
        .footer-links ul li {
            margin-bottom: 10px;
        }
        .footer-links ul li a {
            text-decoration: none;
            color: #666;
            transition: 0.3s;
        }
        .footer-links ul li a:hover {
            color: #0d6efd;
            padding-left: 5px;
        }
        .copy-right {
            border-top: 1px solid #ddd;
            margin-top: 40px;
            padding-top: 20px;
            color: #888;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div id="particles-js"></div>
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#home">
            <img src="uploads/logo.png" alt="logo">
            <span>MediCart</span>
        </a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>
<section id="home">
    <div class="container">
        <div class="home-box mx-auto">
            <h1 style="font-family: cursive;">Welcome to MediCart</h1><br>
            <p>Your trusted online pharmacy for safe, fast & reliable medicine delivery</p>
            <div class="mt-4">
                <a href="login.php" class="btn btn-custom btn-login m-2">Login</a>
                <a href="register.php" class="btn btn-custom btn-register m-2">Create Account</a>
            </div>
        </div>
    </div>
</section>
<section id="about">
    <div class="container text-center">
        <h2>About Us</h2>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="about-box">
                    <p>MediCart is a modern online pharmacy platform dedicated to making essential medicines accessible to everyone, ensuring 100% authentic products, fast delivery, and secure healthcare services at your doorstep. In an era where time is of the essence and health is a priority, we stand as a digital beacon for patient-centric care, recognizing that the journey to recovery should be seamless and stress-free. By integrating advanced inventory tracking with a compassionate support team, we have optimized our supply chain to eliminate the logistical hurdles of traditional pharmacy visits, providing a frictionless experience that prioritizes your recovery over paperwork. At MediCart, we aren't just delivering prescriptions; we are delivering time, comfort, and the security of knowing that your well-being is in expert hands through a platform built on transparency, safety, and unwavering reliability.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="features">
    <div class="container text-center">
        <h2>Why Choose Us</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-box">
                    <img src="uploads/fast.jpeg">
                    <h4>Fast Delivery</h4>
                    <p>Quick medicine delivery at your doorstep.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <img src="uploads/gen.jpeg">
                    <h4>Genuine Medicines</h4>
                    <p>100% verified pharmacy products.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <img src="uploads/secure.png">
                    <h4>Secure System</h4>
                    <p>Safe login and secure transactions.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="contact">
    <div class="container text-center">
        <h2 style="font-family: cursive;">Contact Us</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="contact-box">
                    <form>
                        <input type="text" class="form-control mb-3" placeholder="Your Name">
                        <input type="email" class="form-control mb-3" placeholder="Your Email">
                        <textarea class="form-control mb-3" rows="4" placeholder="Message"></textarea>
                        <button class="btn btn-primary w-100">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="footer-logo mb-3">
                    <img src="uploads/logo.png" alt="logo" style="height: 40px; width: 45px; border-radius: 30px;">
                    <span>MediCart</span>
                </div>
                <p class="text-muted">Your wellness is our commitment.</p>
            </div>
            <div class="col-md-2 mb-4 footer-links">
                <h5>Quick Links</h5>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4 footer-links">
                <h5>Services</h5>
                <ul>
                    <li><a href="#">Medicine Order</a></li>
                    <li><a href="#">Health Tips</a></li>
                    <li><a href="#">Fast Delivery</a></li>
                    <li><a href="#">Secure Payment</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h5>Connect With Us</h5>
                <p class="text-muted mb-1">Email: support@medicart.com</p>
                <p class="text-muted mb-1">Phone: +880 1234 567890</p>
                <p class="text-muted">Address: Chattogram, Bangladesh</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center copy-right">
                <p>&copy; <?php echo date("Y"); ?> MediCart. All Rights Reserved. | Developed with Care</p>
            </div>
        </div>
    </div>
</footer>
<script>
particlesJS("particles-js", {
  "particles": {
    "number": { "value": 70 },
    "color": { "value": "#0d73d3" },
    "shape": { "type": "circle" },
    "opacity": { "value": 0.2 },
    "size": { "value": 3 },
    "move": {
      "enable": false
    },
    "line_linked": {
      "enable": true,
      "opacity": 0.15
    }
  },
  "interactivity": {
    "events": {
      "onhover": { "enable": false },
      "onclick": { "enable": false }
    }
  }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>