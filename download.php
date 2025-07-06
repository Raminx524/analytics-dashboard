<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'installer') {
    header("Location: index.php");
    exit;
}

// Handle download + log
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deviceId = $_POST['device_id'] ?? 'unknown';

    // Get IP address
    function getClientIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
        return $_SERVER['REMOTE_ADDR'];
    }

    $ipAddress = getClientIP();

    $pdo = new PDO("mysql:host=localhost;dbname=analytics", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        INSERT INTO install_events (user_id, device_id, ip_address)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$_SESSION['user_id'], $deviceId, $ipAddress]);

    // Trigger download
    $filePath = 'install_package.txt';
    if (file_exists($filePath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="install_package.txt"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        die("❌ File not found.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Analytics - Download</title>
    <link rel="stylesheet" href="style.css" />
    <script>
        // Store or retrieve device_id from localStorage
        function setupDeviceId() {
            let deviceId = localStorage.getItem("device_id");
            if (!deviceId) {
                deviceId = crypto.randomUUID();
                localStorage.setItem("device_id", deviceId);
            }
            document.getElementById("device_id").value = deviceId;
        }
        window.onload = setupDeviceId;
    </script>
</head>

<body>
    <div class="header">
        <h1>📊 Analytics Downloader</h1>
        <a href="logout.php">Go Back</a>
    </div>
    <div class="container">
        <h2>📦 Installer Panel</h2>
        <div id="form_container">
            <div>
                <p>Click below to download the package:</p>
                <span>It's a simple txt file, I swear ♥</span>
            </div>
            <form method="POST">
                <input type="hidden" name="device_id" id="device_id">
                <button type="submit">⬇️ Download Package</button>
            </form>
        </div>
    </div>
</body>
</html>