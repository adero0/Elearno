<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = htmlspecialchars($_SESSION['username']);

// Pobierz ostatni wynik kwestionariusza
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
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Interaktywne szkolenia - Inteligentny System Uczący</title>
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
.training-section {
    text-align: center;
}
.video {
    width: 100%;
    max-width: 640px;
    border-radius: 12px;
    margin-top: 20px;
}
.audio-player {
    margin-top: 20px;
}
.exercise {
    margin-top: 30px;
    background: #e0f2fe;
    border-left: 5px solid #2563eb;
    padding: 15px;
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
</style>
</head>
<body>

<header>
    <h1>🎓 Inteligentny System Uczący</h1>
    <nav>
        <ul>
            <li><a href="index.php">Strona główna</a></li>
            <li><a href="questionnaire.php">Kwestionariusz</a></li>
            <li><a href="interactive_training.php" class="active">Szkolenia</a></li>
            <li><a href="test_module.php">Testy</a></li>
            <li><a href="logout.php">Wyloguj</a></li>
        </ul>
    </nav>
</header>

<div class="container training-section">
    <h2>Witaj, <?= $username ?>!</h2>
    <?php if ($style === "brak"): ?>
        <p>Nie wykryto stylu uczenia się. Wypełnij najpierw <a href="questionnaire.php">kwestionariusz</a>.</p>
    <?php else: ?>
        <h3>Twój styl uczenia się: <span style="color:#2563eb; text-transform:capitalize;"><?= htmlspecialchars($style) ?></span></h3>
        <hr>

        <?php if ($style === "wizualny"): ?>
            <p>📊 Uczysz się najlepiej przez obserwację i obrazy. Obejrzyj poniższą animację:</p>
            <video class="video" controls>
                <source src="media/visual_training.mp4" type="video/mp4">
                Twoja przeglądarka nie obsługuje wideo.
            </video>
            <div class="exercise">
                <h4>Ćwiczenie:</h4>
                <p>Stwórz mapę myśli na temat „Jak działa komputer” – użyj kolorów, ikon i połączeń między pojęciami.</p>
            </div>

        <?php elseif ($style === "słuchowy"): ?>
            <p>🎧 Uczysz się skutecznie przez słuchanie. Posłuchaj materiału audio:</p>
            <audio class="audio-player" controls>
                <source src="media/audio_training.mp3" type="audio/mpeg">
                Twoja przeglądarka nie obsługuje odtwarzacza audio.
            </audio>
            <div class="exercise">
                <h4>Ćwiczenie:</h4>
                <p>Po wysłuchaniu spróbuj opowiedzieć materiał własnymi słowami lub nagrać krótkie streszczenie.</p>
            </div>

        <?php elseif ($style === "kinestetyczny"): ?>
            <p>🧩 Uczysz się poprzez działanie i doświadczenie. Oto interaktywny quiz:</p>
            <div class="exercise">
                <h4>Ćwiczenie:</h4>
                <p>Przeciągnij poniższe pojęcia we właściwe miejsca:</p>
                <div id="drag-area" style="display:flex;justify-content:center;gap:15px;flex-wrap:wrap;">
                    <div draggable="true" ondragstart="drag(event)" id="cpu" style="background:#93c5fd;padding:10px;border-radius:8px;">Procesor (CPU)</div>
                    <div draggable="true" ondragstart="drag(event)" id="ram" style="background:#a7f3d0;padding:10px;border-radius:8px;">Pamięć RAM</div>
                </div>
                <div id="drop-area" ondrop="drop(event)" ondragover="allowDrop(event)" style="border:2px dashed #2563eb;padding:30px;margin-top:20px;border-radius:10px;">
                    Upuść tu elementy, które należą do jednostki centralnej
                </div>
            </div>

        <?php elseif ($style === "logiczny"): ?>
            <p>🧠 Lubisz analizować dane i rozwiązywać problemy. Spróbuj poniższego zadania logicznego:</p>
            <div class="exercise">
                <h4>Ćwiczenie:</h4>
                <p>Masz 3 żarówki i 3 przełączniki w innym pomieszczeniu. Jak dowiedzieć się, który przełącznik steruje którą żarówką, wykonując tylko jedno wejście do pokoju?</p>
                <button onclick="alert('Rozwiązanie: Włącz jeden przełącznik na kilka minut, wyłącz go i włącz drugi. Po wejściu – żarówka świecąca to drugi, ciepła to pierwszy, zimna to trzeci.')">Pokaż podpowiedź</button>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; <?= date("Y") ?> Inteligentny System Uczący – adaptacyjne szkolenia</p>
</footer>

<script>
function allowDrop(ev) { ev.preventDefault(); }
function drag(ev) { ev.dataTransfer.setData("text", ev.target.id); }
function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    ev.target.appendChild(document.getElementById(data));
    alert("Dobrze! Przeniosłeś element: " + data.toUpperCase());
}
</script>

</body>
</html>