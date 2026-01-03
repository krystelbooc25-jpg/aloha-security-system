<?php
session_start();
require 'db.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        header("Location: dashboard.php");
        exit();
    } else { $error = "Invalid credentials."; }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Aloha Security</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="branding-panel">
            <img src="Resources/logo.jpg" width="100" style="border-radius:10px; margin-bottom:20px;">
            <h1>Welcome, Administrator</h1>
            <p>Your command center for managing professional security services.</p>
            <a href="index.php" style="color:white; border: 1px solid white; padding: 10px 20px; border-radius: 50px; text-decoration: none;">Back to Site</a>
        </div>
        <div class="form-panel">
            <div class="login-box">
                <h2>Admin Login</h2>
                <?php if($error) echo "<p style='color:red'>$error</p>"; ?>
                <form method="POST">
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%">LOGIN SECURELY</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>