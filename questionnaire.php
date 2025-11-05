<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

$user_id = $_SESSION['user_id'];
$username = htmlspecialchars($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Ankieta VARK – rozpoznaj swój styl uczenia się</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body {
    font-family: "Segoe UI", sans-serif;
    background: #f3f4f6;
    color: #111827;
    margin: 0;
}
header {
    background: linear-gradient(135deg, #0d1117, #1f2937);
    color: #fff;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
header h1 { font-size: 1.4rem; }
nav ul {
    list-style: none;
    display: flex;
    gap: 20px;
    margin: 0;
    padding: 0;
}
nav ul li a {
    text-decoration: none;
    color: #f3f4f6;
    font-weight: 500;
    padding: 8px 14px;
    border-radius: 8px;
    transition: all 0.3s ease;
}
nav ul li a:hover { background: #2563eb; color: #fff; }
nav ul li a.active { background: #1e40af; color: #fff; }
.container {
    max-width: 900px;
    margin: 40px auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.question { margin-bottom: 25px; }
button {
    background: #2563eb;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.3s;
}
button:hover { background: #1e3a8a; }
footer {
    text-align: center;
    padding: 15px;
    background: #111827;
    color: #9ca3af;
    margin-top: 40px;
}
</style>
</head>
<body>

<header>
  <h1>📘 Ankieta VARK</h1>
  <nav>
    <ul>
      <li><a href="index.php">Strona główna</a></li>
      <li><a href="questionnaire_vark.php" class="active">Ankieta VARK</a></li>
      <li><a href="interactive_training.php">Szkolenia</a></li>
      <li><a href="test_module.php">Testy</a></li>
      <li><a href="logout.php">Wyloguj</a></li>
    </ul>
  </nav>
</header>

<div class="container">
  <h2>Rozpoznaj swój styl uczenia się (VARK)</h2>
  <p>Wybierz odpowiedź, która najlepiej Cię opisuje.</p>

  <form method="post" action="save_questionnaire.php">
    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">

    <div class="question">
      <p>1️⃣ Gdy uczysz się nowego tematu, wolisz:</p>
      <label><input type="radio" name="q1" value="V" required> Oglądać wykresy i diagramy</label><br>
      <label><input type="radio" name="q1" value="A"> Słuchać wyjaśnień</label><br>
      <label><input type="radio" name="q1" value="R"> Czytać notatki lub tekst</label><br>
      <label><input type="radio" name="q1" value="K"> Wykonywać ćwiczenia praktyczne</label>
    </div>

    <div class="question">
      <p>2️⃣ Wolisz, gdy nauczyciel:</p>
      <label><input type="radio" name="q2" value="A" required> Opowiada i tłumaczy ustnie</label><br>
      <label><input type="radio" name="q2" value="V"> Pokazuje rysunki lub prezentacje</label><br>
      <label><input type="radio" name="q2" value="R"> Daje materiały do przeczytania</label><br>
      <label><input type="radio" name="q2" value="K"> Pozwala spróbować samemu</label>
    </div>

    <div class="question">
      <p>3️⃣ Aby zapamiętać informacje, najczęściej:</p>
      <label><input type="radio" name="q3" value="R" required> Robię notatki i je przeglądam</label><br>
      <label><input type="radio" name="q3" value="A"> Powtarzam je na głos</label><br>
      <label><input type="radio" name="q3" value="V"> Kojarzę obrazy z pojęciami</label><br>
      <label><input type="radio" name="q3" value="K"> Odtwarzam czynności lub przykłady</label>
    </div>

    <div class="question">
      <p>4️⃣ Gdy widzisz nowy sprzęt, wolisz:</p>
      <label><input type="radio" name="q4" value="K" required> Od razu go przetestować</label><br>
      <label><input type="radio" name="q4" value="R"> Przeczytać instrukcję</label><br>
      <label><input type="radio" name="q4" value="A"> Posłuchać jak ktoś tłumaczy</label><br>
      <label><input type="radio" name="q4" value="V"> Obejrzeć schemat działania</label>
    </div>

    <div class="question">
      <p>5️⃣ Najlepiej uczysz się z:</p>
      <label><input type="radio" name="q5" value="V" required> Obrazków, filmów</label><br>
      <label><input type="radio" name="q5" value="A"> Dyskusji i rozmów</label><br>
      <label><input type="radio" name="q5" value="R"> Czytania książek</label><br>
      <label><input type="radio" name="q5" value="K"> Robienia eksperymentów</label>
    </div>

    <button type="submit">Zapisz i zobacz wynik</button>
  </form>
</div>

<footer>
  <p>&copy; <?= date("Y") ?> Inteligentny System Uczący – ankieta VARK</p>
</footer>

</body>
</html>