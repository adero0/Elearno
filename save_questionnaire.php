<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
    die("Błąd bezpieczeństwa CSRF.");
}

$user_id = $_SESSION['user_id'];

// Zlicz punkty dla każdej kategorii
$counts = ['V' => 0, 'A' => 0, 'R' => 0, 'K' => 0];
foreach ($_POST as $key => $value) {
    if (in_array($value, ['V', 'A', 'R', 'K'])) {
        $counts[$value]++;
    }
}

// Znajdź dominujący styl
arsort($counts);
$dominant = array_key_first($counts);

// Opis stylu
$descriptions = [
    'V' => "Preferujesz naukę poprzez obrazy, wykresy i schematy. Używaj kolorowych notatek i diagramów.",
    'A' => "Najlepiej uczysz się słuchając – wybieraj nagrania, podcasty i dyskusje.",
    'R' => "Lubisz czytać i pisać – notatki, artykuły i teksty to Twój sposób nauki.",
    'K' => "Uczysz się poprzez działanie – eksperymenty, ćwiczenia praktyczne, modele."
];

// Zapisz wynik do bazy
$stmt = $conn->prepare("INSERT INTO questionnaire_results (user_id, dominant_style, v_points, a_points, r_points, k_points, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("isiiii", $user_id, $dominant, $counts['V'], $counts['A'], $counts['R'], $counts['K']);
$stmt->execute();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Wynik ankiety VARK</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body { font-family: "Segoe UI", sans-serif; background: #f3f4f6; color: #111827; margin: 0; }
.container {
    max-width: 700px; margin: 40px auto; background: #fff; padding: 30px;
    border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    text-align: center;
}
h2 { color: #2563eb; }
footer { text-align: center; padding: 15px; background: #111827; color: #9ca3af; margin-top: 40px; }
.btn {
    background: #2563eb; color: #fff; text-decoration: none; padding: 10px 16px;
    border-radius: 6px; display: inline-block; margin-top: 15px;
}
.btn:hover { background: #1e3a8a; }
</style>
</head>
<body>
<div class="container">
    <h2>Twój dominujący styl uczenia się: <?= $dominant ?></h2>
    <p><?= $descriptions[$dominant] ?></p>
    <p><strong>Punkty:</strong> V: <?= $counts['V'] ?> | A: <?= $counts['A'] ?> | R: <?= $counts['R'] ?> | K: <?= $counts['K'] ?></p>
    <a href="interactive_training.php" class="btn">Przejdź do spersonalizowanego szkolenia</a>
</div>
<footer>
  <p>&copy; <?= date("Y") ?> Inteligentny System Uczący – wynik ankiety VARK</p>
</footer>
</body>
</html>