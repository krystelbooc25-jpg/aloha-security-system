<?php
require 'db.php';
$message = "";

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];

    // Handle File Upload
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
    
    $file_name = time() . "_" . basename($_FILES["resume"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
        $stmt = $pdo->prepare("INSERT INTO applicants (first_name, last_name, email, phone, position_applied, resume_path, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
        if ($stmt->execute([$first_name, $last_name, $email, $phone, $position, $target_file])) {
            $message = "Application submitted successfully! We will contact you soon.";
        }
    } else {
        $message = "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Careers | Aloha Security Agency</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div><strong>ALOHA SECURITY AGENCY</strong></div>
    <div><a href="login.php" style="color:white; text-decoration:none;">Admin Login</a></div>
</header>

<section class="hero">
    <h1>Join Our Elite Force</h1>
    <p>Help us protect and serve the community with professionalism and integrity.</p>
</section>

<div class="container">
    <?php if($message): ?>
        <div class="success-msg"><?= $message ?></div>
    <?php endif; ?>

    <h2 class="section-title">Available Positions</h2>
    
    <!-- Static Job Listings -->
    <div class="job-card">
        <div class="job-info">
            <h3>Security Guard (License Required)</h3>
            <p>Location: Davao City | Full-Time</p>
        </div>
        <a href="#apply-form" class="btn-apply">Apply Now</a>
    </div>

    <div class="job-card">
        <div class="job-info">
            <h3>Security Supervisor</h3>
            <p>Location: Various Areas | 5+ Years Experience</p>
        </div>
        <a href="#apply-form" class="btn-apply">Apply Now</a>
    </div>

    <!-- Application Form -->
    <div id="apply-form" class="form-container">
        <h2 style="text-align:center; color:var(--primary-color);">Application Form</h2>
        <form method="POST" enctype="multipart/form-data">
            <div style="display:flex; gap:10px;">
                <div class="form-group" style="flex:1;">
                    <label>First Name</label>
                    <input type="text" name="first_name" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Last Name</label>
                    <input type="text" name="last_name" required>
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" required>
            </div>

            <div class="form-group">
                <label>Position Desired</label>
                <select name="position" required>
                    <option value="Security Guard">Security Guard</option>
                    <option value="Security Supervisor">Security Supervisor</option>
                    <option value="Office Staff">Office Staff</option>
                </select>
            </div>

            <div class="form-group">
                <label>Upload Resume (PDF/Word)</label>
                <input type="file" name="resume" accept=".pdf,.doc,.docx" required>
            </div>

            <button type="submit" class="btn-apply" style="width:100%; border:none; cursor:pointer;">SUBMIT APPLICATION</button>
        </form>
    </div>
</div>

<footer style="text-align:center; padding: 20px; color:#777;">
    &copy; 2025 Aloha Security Agency. All Rights Reserved.
</footer>

</body>
</html>