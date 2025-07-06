<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=analytics", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="users.xls"');

$output = fopen('php://output', 'w');

// Add column headers
fputcsv($output, ['ID', 'Username' , 'Role' , 'Created At']);

// Fetch and output rows
$stmt = $pdo-> query("SELECT id, username, role, created_at FROM users");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}
fclose($output);
exit;

?>