<?php
include('includes/config.php');
include('includes/db.php');
$error = "";
$input = "";
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}
if (isset($_POST['login'])) {
    $input = trim($_POST['login_input']);
    $password = $_POST['password'];
    if(empty($input) || empty($password)){
        $error = "All fields are required!";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE phone=? OR email=? LIMIT 1");
        $stmt->bind_param("ss", $input, $input);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $user['role'] ?? 'user';
                if ($_SESSION['role'] === 'admin') {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            } else {
                $error = "Invalid email/phone or password!";
            }
        } else {
            $error = "Invalid email/phone or password!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCart Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ddfdfd, #f5f7fb);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 850px;
            border-radius: 20px;
            overflow: hidden;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .login-image {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }
        .login-right {
            padding: 50px;
            background: #ffffff;
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
            padding: 12px;
            font-weight: 700;
            border-radius: 10px;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
        }
        .form-control {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        h3 {
            font-weight: 900;
            color: #333;
        }
        .brand-text {
            color: #0d6efd;
            font-family: cursive;
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center">
    <div class="card login-card w-100">
        <div class="row g-0">
            <div class="col-md-6 d-none d-md-block">
                <img src="uploads/home.png" class="login-image" alt="Login Illustration">
            </div>
            <div class="col-md-6 login-right">
                <div class="w-100">
                    <div class="text-center mb-4">
                        <h3 style="font-family: cursive;">MediCart <span class="brand-text">Login</span></h3>
                        <p class="text-muted">Welcome back! Please enter your details.</p>
                    </div>
                    <?php if (!empty($error)) { ?>
                        <div class="alert alert-danger text-center py-2" style="font-size: 14px;">
                            <?php echo $error; ?>
                        </div>
                    <?php } ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone or Email</label>
                            <input type="text" name="login_input" class="form-control" 
                                   placeholder="Enter your email or phone"
                                   value="<?= htmlspecialchars($input) ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" 
                                   placeholder="********" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100 mb-3">Login</button>
                    </form>
                    <div class="text-center mt-3">
                        <span class="text-muted">Don't have an account?</span> 
                        <a href="register.php" class="text-decoration-none fw-bold" style="color: #20c997;">Create Account</a>
                    </div>
                    <div class="text-center mt-2">
                        <a href="index.php" class="text-muted small text-decoration-none">← Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>