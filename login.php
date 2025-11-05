<?php
session_start();
require_once "db_connect.php";
if (!isset($_SESSION['token'])) $_SESSION['token'] = bin2hex(random_bytes(32));

$error = '';
$username = $_POST['username'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) { die('Błąd CSRF'); }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, password_hash, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) $error = "Użytkownik nie istnieje.";
    else {
        $stmt->bind_result($id, $hash, $role);
        $stmt->fetch();
        if (password_verify($password, $hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['user_role'] = $role;
            $_SESSION['user_token'] = bin2hex(random_bytes(16));
            header("Location: index.php");
            exit;
        } else $error = "Nieprawidłowe hasło.";
    }
}
?>
<!doctype html>
<html lang="pl">
<head>
<meta charset="utf-8">
<title>Logowanie</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="card container">
  <h2>Logowanie</h2>
  <form method="post" class="form">
    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
    <label>Login
      <input type="text" name="username" required value="<?= htmlspecialchars($username) ?>">
    </label>
    <label>Hasło
      <input type="password" name="password" required>
    </label>
    <button type="submit">Zaloguj</button>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    <p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
  </form>
</div>
</body>
</html>