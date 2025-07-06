<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=analytics", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Total users (excluding admin)
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'installer'")->fetchColumn();

// Total installs
$totalInstalls = $pdo->query("SELECT COUNT(*) FROM install_events")->fetchColumn();

// Users who never installed
$notInstalledStmt = $pdo->query("
    SELECT COUNT(*) FROM users u
    LEFT JOIN install_events i ON u.id = i.user_id
    WHERE i.id IS NULL AND u.role = 'installer'
");
$usersNotInstalled = $notInstalledStmt->fetchColumn();

// Get all users + install count
$installStatsStmt = $pdo->query("
    SELECT u.username, COUNT(i.id) AS installs
    FROM users u
    LEFT JOIN install_events i ON u.id = i.user_id
    WHERE u.role = 'installer'
    GROUP BY u.id
    ORDER BY installs DESC
");
$userStats = $installStatsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>📊 Admin Dashboard</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="header">
        <h1>📊 Admin Dashboard</h1>
        <a href="logout.php">Log Out</a>
    </div>
    <div class="container">
        <h2>📊 Admin Dashboard</h2>
        <div class="stats">
            <div class="stat">👥 Total Registered Installers: <strong><?= $totalUsers ?></strong></div>
            <div class="stat">⬇️ Total Install Events: <strong><?= $totalInstalls ?></strong></div>
            <div class="stat">❌ Users Who Didn't Install: <strong><?= $usersNotInstalled ?></strong></div>
        </div>

        <h3>📋 User Install Breakdown</h3>
        <div class="table_container">
            <table id="user-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Install Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userStats as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= $row['installs'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
                <a href="export_users.php" class="export-btn">⬇️ Export Users to Excel</a>
        </div>
    </div>
</body>

</html>