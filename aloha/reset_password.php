<?php
require 'db.php'; // Ensure your database connection file name is correct

$new_password = 'admin123';
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$username = 'admin';

try {
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->execute([$hashed_password, $username]);
    
    if ($stmt->rowCount() > 0) {
        echo "Password updated successfully! You can now login with: admin / admin123";
    } else {
        echo "User 'admin' not found. Make sure you ran the SQL INSERT code first.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>