<?php
require 'db.php';

function calculateSalary($applicant_id, $month) {
    global $pdo;
    
    // Get daily rate from area assigned
    $stmt = $pdo->prepare("SELECT a.daily_rate FROM areas a 
                           JOIN deployments d ON a.id = d.area_id 
                           WHERE d.applicant_id = ?");
    $stmt->execute([$applicant_id]);
    $area = $stmt->fetch();
    $rate = $area ? $area['daily_rate'] : 0;

    // Count present days
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM attendance 
                           WHERE applicant_id = ? AND status = 'Present' 
                           AND MONTH(date) = ?");
    $stmt->execute([$applicant_id, $month]);
    $daysWorked = $stmt->fetchColumn();

    return $daysWorked * $rate;
}

// Example usage:
// echo "Total Salary: P" . calculateSalary(1, 12); // ID 1 for December
?>