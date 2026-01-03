<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

// Fetch Summary Counts
$stats = [
    'total' => $pdo->query("SELECT COUNT(*) FROM applicants")->fetchColumn(),
    'pending' => $pdo->query("SELECT COUNT(*) FROM applicants WHERE status='Pending'")->fetchColumn(),
    'accepted' => $pdo->query("SELECT COUNT(*) FROM applicants WHERE status='Accepted'")->fetchColumn()
];

// Fetch Applicants
$stmt = $pdo->query("SELECT * FROM applicants ORDER BY created_at DESC");
$applicants = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Aloha Security</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div style="display:flex; align-items:center; gap:15px;">
            <i class="fas fa-shield-alt fa-2x"></i>
            <div><strong>ALOHA SECURITY AGENCY</strong><br><small>Admin Dashboard</small></div>
        </div>
        <div>Welcome, <?= htmlspecialchars($_SESSION['full_name']) ?> | <a href="logout.php" style="color:white;">Logout</a></div>
    </header>

    <div class="container">
        <h1>Recruitment Dashboard</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Apps</h3>
                <p><?= $stats['total'] ?></p>
            </div>
            <div class="stat-card" style="border-bottom-color: #ef6c00;">
                <h3>Pending</h3>
                <p><?= $stats['pending'] ?></p>
            </div>
            <div class="stat-card" style="border-bottom-color: #2e7d32;">
                <h3>Accepted</h3>
                <p><?= $stats['accepted'] ?></p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Applicant Name</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th>Date Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($applicants) > 0): ?>
                    <?php foreach ($applicants as $row): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></strong></td>
                        <td><?= htmlspecialchars($row['position_applied']) ?></td>
                        <td><span class="badge badge-<?= strtolower($row['status']) ?>"><?= $row['status'] ?></span></td>
                        <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                        <td>
                            <a href="view-applicant.php?id=<?= $row['id'] ?>" class="btn btn-primary" style="padding:5px 10px; font-size:12px;">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">No applications found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>