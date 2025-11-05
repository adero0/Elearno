<?php
session_start();
require_once "db_connect.php";
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { header("Location: login.php"); exit(); }

$error=''; $success='';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $type = $_POST['type'] ?? 'text';
  $content = $_POST['content'] ?? '';
  $difficulty = intval($_POST['difficulty'] ?? 1);
  $tags = trim($_POST['tags'] ?? '');
  if (!$title) $error = "Tytuł wymagany.";
  else {
    $stmt = $conn->prepare("INSERT INTO materials (title,type,content,difficulty,tags) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssis", $title, $type, $content, $difficulty, $tags);
    if ($stmt->execute()) $success = "Dodano materiał.";
    else $error = "Błąd zapisu.";
  }
}

$materials = $conn->query("SELECT * FROM materials ORDER BY created_at DESC");
?>
<!doctype html>
<html lang="pl">
<head>
<meta charset="utf-8">
<title>Panel materiałów (admin)</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container card">
  <h2>Dodaj materiał</h2>
  <form method="post" class="form">
    <label>Tytuł<input type="text" name="title" required></label>
    <label>Typ
      <select name="type">
        <option value="text">Tekst</option>
        <option value="video">Wideo (embed)</option>
        <option value="interactive">Interaktywny</option>
      </select>
    </label>
    <label>Treść (HTML / embed link / opis)
      <textarea name="content" rows="6"></textarea>
    </label>
    <label>Trudność<input type="number" name="difficulty" min="1" max="5" value="1"></label>
    <label>Tagi (oddzielone przecinkami)<input type="text" name="tags"></label>
    <button type="submit">Dodaj</button>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    <?php if ($success) echo "<p class='success'>$success</p>"; ?>
  </form>

  <h3>Istniejące materiały</h3>
  <ul>
  <?php while($m = $materials->fetch_assoc()): ?>
    <li><strong><?= htmlspecialchars($m['title']) ?></strong> (<?= $m['type'] ?>) - <?= htmlspecialchars($m['tags']) ?></li>
  <?php endwhile; ?>
  </ul>
</div>
</body>
</html>