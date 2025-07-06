<?php 
session_start();

$pdo = new PDO("mysql:host=localhost;dbname=analytics", "root", "");
$pdo -> setAttribute(PDO:: ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);

$error = "";

//Login/Register
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];

    //GET user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if($user) {
        //Login
        if(password_verify($password,$user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: " . ($user['role'] === 'admin' ? 'dashboard.php' : 'download.php'));
            exit;
        } else {
            $error = "❌ Incorrect password.";
        }
    } else {
        //Register
        $hashPass = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashPass]);

        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $username;

        //GET user for Role
        $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $_SESSION['role'] = $stmt->fetchColumn();
        
        header("Location: " . ($role === "admin"? 'dashboard.php' : 'download.php'));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
    <h2>🔐 Login / Register</h2>
    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>Username:</label>
        <input name="username" required>
      </div>
      <div class="form-group">
        <label>Password:</label>
        <input name="password" type="password" required>
      </div>
      <button type="submit">Continue</button>
    </form>
  </div>
</body>
</html>