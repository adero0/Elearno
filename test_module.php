<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = htmlspecialchars($_SESSION['username']);

// Pobierz ostatni styl uczenia się
$stmt = $conn->prepare("SELECT dominant_style FROM questionnaire_results WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $style = $row['dominant_style'];
} else {
    $style = "brak";
}
$stmt->close();

// Ocena testu po przesłaniu
$score = null;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correct = 0;
    foreach ($_POST as $key => $value) {
        if (strpos($key, "q") === 0 && $value === "correct") {
            $correct++;
        }
    }
    $score = $correct;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Test dopasowany do stylu uczenia się</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body {
    font-family: "Segoe UI", sans-serif;
    margin: 0;
    background: #f3f4f6;
    color: #111827;
}
header {
    background: linear-gradient(135deg, #0d1117, #1f2937);
    color: #fff;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
header h1 {
    font-size: 1.4rem;
}
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
nav ul li a:hover {
    background-color: #2563eb;
    color: #fff;
}
nav ul li a.active {
    background-color: #1e40af;
    color: #fff;
}
.container {
    max-width: 900px;
    margin: 40px auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.question {
    margin: 20px 0;
    padding: 10px;
    background: #f1f5f9;
    border-radius: 8px;
}
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
button:hover {
    background: #1e3a8a;
}
footer {
    text-align: center;
    padding: 15px;
    background: #111827;
    color: #9ca3af;
    margin-top: 40px;
}
.result {
    background: #dbeafe;
    border-left: 5px solid #2563eb;
    padding: 15px;
    border-radius: 8px;
    margin-top: 20px;
}
</style>
</head>
<body>

<header>
    <h1>🧠 Inteligentny System Uczący</h1>
    <nav>
        <ul>
            <li><a href="index.php">Strona główna</a></li>
            <li><a href="questionnaire.php">Kwestionariusz</a></li>
            <li><a href="interactive_training.php">Szkolenia</a></li>
            <li><a href="test_module.php" class="active">Testy</a></li>
            <li><a href="logout.php">Wyloguj</a></li>
        </ul>
    </nav>
</header>

<div class="container">
<h2>Test dopasowany do Twojego stylu uczenia się</h2>

<?php if ($style === "brak"): ?>
    <p>⚠️ Najpierw wypełnij <a href="questionnaire.php">kwestionariusz stylu uczenia się</a>.</p>
<?php else: ?>

<?php if ($score === null): ?>
<form method="post">
    <p>Twój styl: <strong style="color:#2563eb"><?= htmlspecialchars($style) ?></strong></p>
    <hr>

    <?php if ($style === "wizualny"): ?>
        <div class="question">
            <p>1️⃣ Co przedstawia poniższy diagram?</p>
            <img src="schemat-reakcji.png" alt="Diagram" width="300"><br>
            <label><input type="radio" name="q1" value="correct"> Przepływ danych w komputerze</label><br>
            <label><input type="radio" name="q1" value="wrong"> Schemat chemiczny</label>
        </div>

        <div class="question">
            <p>2️⃣ Co najlepiej pomaga Ci zapamiętać informacje?</p>
            <label><input type="radio" name="q2" value="correct"> Kolorowe notatki i wykresy</label><br>
            <label><input type="radio" name="q2" value="wrong"> Głośne powtarzanie</label>
        </div>

    <?php elseif ($style === "słuchowy"): ?>
        <div class="question">
            <p>1️⃣ Co najlepiej utrwala wiedzę?</p>
            <label><input type="radio" name="q1" value="correct"> Słuchanie nagrań i dyskusje</label><br>
            <label><input type="radio" name="q1" value="wrong"> Samodzielne pisanie notatek</label>
        </div>
        <div class="question">
            <p>2️⃣ Jakie narzędzie pomoże Ci się uczyć?</p>
            <label><input type="radio" name="q2" value="wrong"> Tabela</label><br>
            <label><input type="radio" name="q2" value="correct"> Podcast edukacyjny</label>
        </div>

    <?php elseif ($style === "kinestetyczny"): ?>
        <div class="question">
            <p>1️⃣ Co najlepiej wspiera Twoje uczenie się?</p>
            <label><input type="radio" name="q1" value="correct"> Wykonywanie eksperymentów i ćwiczeń</label><br>
            <label><input type="radio" name="q1" value="wrong"> Czytanie notatek</label>
        </div>
        <div class="question">
            <p>2️⃣ Co zrobić, by lepiej zapamiętać?</p>
            <label><input type="radio" name="q2" value="wrong"> Oglądać film instruktażowy</label><br>
            <label><input type="radio" name="q2" value="correct"> Zbudować lub narysować coś samodzielnie</label>
        </div>

    <?php elseif ($style === "logiczny"): ?>
        <div class="question">
            <p>1️⃣ Jak rozwiążesz problem z działającym wolno komputerem?</p>
            <label><input type="radio" name="q1" value="correct"> Analiza krok po kroku przyczyny spowolnienia</label><br>
            <label><input type="radio" name="q1" value="wrong"> Restart bez diagnozy</label>
        </div>
        <div class="question">
            <p>2️⃣ Co najbardziej pomaga w nauce?</p>
            <label><input type="radio" name="q2" value="correct"> Logiczne powiązania i schematy</label><br>
            <label><input type="radio" name="q2" value="wrong"> Odtwarzanie dźwięków</label>
        </div>
    <?php endif; ?>

    <button type="submit">Zatwierdź odpowiedzi</button>
</form>

<?php else: ?>
    <div class="result">
        <h3>Twój wynik: <?= $score ?>/2</h3>
        <?php if ($score == 2): ?>
            <p>🎉 Świetnie! Opanowałeś materiał w swoim stylu.</p>
        <?php elseif ($score == 1): ?>
            <p>🙂 Dobrze, ale spróbuj jeszcze raz i zwróć uwagę na szczegóły.</p>
        <?php else: ?>
            <p>😅 Spróbuj ponownie – obejrzyj szkolenie jeszcze raz i powtórz test.</p>
        <?php endif; ?>
        <a href="interactive_training.php"><button>Powrót do szkolenia</button></a>
    </div>
<?php endif; ?>

<?php endif; ?>
</div>

<footer>
    <p>&copy; <?= date("Y") ?> Inteligentny System Uczący – testy adaptacyjne</p>
</footer>

</body>
</html>