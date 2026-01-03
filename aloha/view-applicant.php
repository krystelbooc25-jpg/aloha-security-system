<?php
session_start();
require 'db.php';

// 1. Check if Admin is logged in
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

// 2. Check if an ID was actually sent in the URL
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

// 3. Fetch the specific applicant data
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM applicants WHERE id = ?");
$stmt->execute([$id]);
$app = $stmt->fetch();

// 4. If applicant doesn't exist, stop
if (!$app) {
    echo "Applicant not found.";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Applicant Profile - Aloha Security</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div style="display:flex; align-items:center; gap:15px;">
            <i class="fas fa-shield-alt fa-2x"></i>
            <div><strong>ALOHA SECURITY AGENCY</strong><br><small>Applicant Profile View</small></div>
        </div>
        <div><a href="dashboard.php" style="color:white; text-decoration:none;"><i class="fas fa-arrow-left"></i> Back to Dashboard</a></div>
    </header>

    <div class="container">
        <div style="background:white; padding:40px; border-radius:10px; margin-top:20px; box-shadow:0 10px 30px rgba(0,0,0,0.1);">
            <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #eee; padding-bottom:20px;">
                <h2>
                    <i class="fas fa-user-circle" style="color:#002b5c;"></i> 
                    <?= htmlspecialchars($app['first_name'] . ' ' . $app['last_name']) ?>
                </h2>
                <div class="action-buttons">
                    <?php if (!empty($app['resume_path'])): ?>
                        <a href="<?= htmlspecialchars($app['resume_path']) ?>" class="btn btn-accent" target="_blank">
                            <i class="fas fa-download"></i> View Resume
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:40px; margin-top:30px;">
                <div>
                    <h3 style="color:#002b5c;"><i class="fas fa-info-circle"></i> Personal Information</h3>
                    <p><strong>Position Applied:</strong> <?= htmlspecialchars($app['position_applied']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($app['email']) ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($app['phone']) ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($app['street_address'] ?? 'N/A') ?>, <?= htmlspecialchars($app['city'] ?? '') ?></p>
                    <p><strong>Date of Birth:</strong> <?= htmlspecialchars($app['dob'] ?? 'N/A') ?></p>
                    <p><strong>Status:</strong> <span class="badge badge-<?= strtolower($app['status']) ?>"><?= $app['status'] ?></span></p>
                </div>
                
                <div>
                    <h3 style="color:#002b5c;"><i class="fas fa-graduation-cap"></i> Experience & Education</h3>
                    <p><strong>Experience:</strong><br>
                        <span style="color:#555;"><?= !empty($app['experience']) ? nl2br(htmlspecialchars($app['experience'])) : 'No experience listed.' ?></span>
                    </p>
                    <p><strong>Education:</strong><br>
                        <span style="color:#555;"><?= !empty($app['education']) ? nl2br(htmlspecialchars($app['education'])) : 'No education details provided.' ?></span>
                    </p>
                </div>
            </div>
            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                <p style="font-size: 12px; color: #999;">Application received on: <?= date('F j, Y, g:i a', strtotime($app['created_at'])) ?></p>
            </div>
        </div>
    </div>
</body>
</html>