<?php
include('includes/config.php');
include('includes/db.php');
$error = "";
$success = "";
if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $phone_digits = preg_replace('/[^0-9]/', '', $phone);
    $phone_len = strlen($phone_digits);
    if (empty($name) || empty($phone) || empty($password)) {
        $error = "Name, Phone and Password are required!";
    } 
    elseif ($phone_len < 11 || $phone_len > 13) {
        $error = "Phone number must be between 11 to 13 digits!";
    }
    elseif ($password !== $confirm) {
        $error = "Passwords do not match!";
    } 
    elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } 
    else {
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE phone = ? LIMIT 1");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $error = "This phone number is already registered!";
        } else {
            if (!empty($email)) {
                $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? LIMIT 1");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->num_rows > 0) {
                    $error = "This email is already in use!";
                }
            }
            if (empty($error)) {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $finalEmail = !empty($email) ? $email : NULL;
                $insert = $conn->prepare("INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)");
                $insert->bind_param("ssss", $name, $phone, $finalEmail, $hashed);
                if ($insert->execute()) {
                    $success = "Registration successful! Redirecting to login...";
                    header("refresh:3;url=login.php");
                } else {
                    $error = "Registration failed! Please try again later.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MediCart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ddfdfd, #f5f7fb);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .register-card {
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            overflow: hidden;
            border-radius: 20px;
        }
        .btn-register {
            background: #20c997;
            border: none;
            font-weight: 700;
            padding: 12px;
            color: white;
            transition: 0.3s;
        }
        .btn-register:hover {
            background: #1ba87e;
            transform: translateY(-2px);
        }
        .brand-text {
            color: #0d6efd;
            font-family: 'Trebuchet MS', sans-serif;
            font-weight: 900;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #20c997;
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center">
    <div class="card register-card p-4 w-100" style="max-width: 550px;">
        <div class="text-center mb-4">
            <h3 class="mb-1" style="font-family: cursive;">MediCart <span class="brand-text" style="font-family: cursive;">Register</span></h3>
        </div>
        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger py-2 text-center" style="font-size: 14px;"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (!empty($success)) : ?>
            <div class="alert alert-success py-2 text-center" style="font-size: 14px;"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-2">
                <label class="form-label small fw-bold">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
            </div>
            <div class="mb-2">
                <label class="form-label small fw-bold">Phone Number</label>
                <input type="text" name="phone" class="form-control" 
                       placeholder="017XXXXXXXX or +8801..." 
                       pattern="[0-9+]{11,14}" 
                       title="Please enter a valid phone number (11-13 digits)" required>
            </div>
            <div class="mb-2">
                <label class="form-label small fw-bold">Email (Optional)</label>
                <input type="email" name="email" class="form-control" placeholder="example@mail.com">
            </div>
            <div class="mb-2">
                <label class="form-label small fw-bold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="At least 6 characters" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Repeat password" required>
            </div>
            <button name="register" class="btn btn-register w-100 shadow-sm">Create Account</button>
        </form>
        <div class="text-center mt-3">
            <span class="text-muted small">Already have an account?</span>
            <a href="login.php" class="text-decoration-none fw-bold small" style="color: #0d6efd;"> Login Now</a>
        </div>
        <div class="text-center mt-2">
            <a href="index.php" class="text-muted small text-decoration-none">← Back to Home</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>