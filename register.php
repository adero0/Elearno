<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION['token'])) $_SESSION['token'] = bin2hex(random_bytes(32));

$error = '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) { die('Błąd CSRF'); }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';
    $email = trim($_POST['email'] ?? '');

    if (strlen($username) < 3) $error = "Login musi mieć co najmniej 3 znaki.";
    elseif ($password !== $confirm) $error = "Hasła muszą się zgadzać.";
    elseif (strlen($password) < 6) $error = "Hasło musi mieć co najmniej 6 znaków.";
    else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) $error = "Login lub email już zajęty.";
        else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';
            $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?,?,?,?)");
            $stmt->bind_param("ssss", $username, $email, $hash, $role);
            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['username'] = $username;
                $_SESSION['user_role'] = $role;
                $_SESSION['user_token'] = bin2hex(random_bytes(16));
                header("Location: index.php");
                exit;
            } else {
                $error = "Błąd przy rejestracji.";
            }
        }
    }
}
?>
<!doctype html>
<html lang="pl">
<head>
<meta charset="utf-8">
<title>Rejestracja</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="card container">
  <h2>Rejestracja</h2>
  <form method="post" class="form">
    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
    <label>Login
      <input type="text" name="username" required value="<?= htmlspecialchars($username) ?>">
    </label>
    <label>Email
      <input type="email" name="email" required value="<?= htmlspecialchars($email) ?>">
    </label>
    <label>Hasło
      <input type="password" name="password" required>
    </label>
    <label>Powtórz hasło
      <input type="password" name="confirm" required>
    </label>
    <button type="submit">Zarejestruj</button>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    <p>Masz konto? <a href="login.php">Zaloguj się</a></p>
  </form>
</div>
</body>
</html>