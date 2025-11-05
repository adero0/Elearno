<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$username = htmlspecialchars($_SESSION['username']);
$role = $_SESSION['user_role'] ?? 'user';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inteligentny System Uczący</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* 🧭 Stylizacja nowoczesnej nawigacji */
        header {
            background: linear-gradient(135deg, #0d1117, #1f2937);
            color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        header h1 {
            font-size: 1.4rem;
            letter-spacing: 0.5px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            position: relative;
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
            font-weight: 600;
        }

        /* 🌐 Menu responsywne (mobile) */
        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .menu-toggle span {
            background: #fff;
            height: 3px;
            width: 25px;
            margin: 4px 0;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            nav ul {
                display: none;
                flex-direction: column;
                background: #1f2937;
                position: absolute;
                top: 60px;
                right: 20px;
                border-radius: 10px;
                padding: 10px;
            }

            nav ul.show {
                display: flex;
            }

            .menu-toggle {
                display: flex;
            }
        }

        /* 💡 Drobne poprawki ogólne */
        body {
            font-family: "Segoe UI", sans-serif;
            margin: 0;
            background: #f3f4f6;
            color: #111827;
        }

        main {
            padding: 30px;
            text-align: center;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            margin: 20px;
            display: inline-block;
            max-width: 300px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            vertical-align: top;
        }

        .btn {
            background: #2563eb;
            color: #fff;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 6px;
            display: inline-block;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #1e3a8a;
        }

        footer {
            text-align: center;
            padding: 15px;
            background: #111827;
            color: #9ca3af;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<header>
    <h1>🎓 Inteligentny System Uczący</h1>
    <div class="menu-toggle" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <nav>
        <ul id="menu">
            <li><a href="index.php" class="active">Strona główna</a></li>
            <li><a href="questionnaire.php">Kwestionariusz</a></li>
            <li><a href="interactive_training.php">Szkolenia</a></li>
            <li><a href="test_module.php">Testy</a></li>
            <?php if ($role === 'admin'): ?>
                <li><a href="admin_panel.php">Panel admina</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Wyloguj</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="welcome">
        <h2>Witaj, <?= $username ?>!</h2>
        <p>
            Ten system inteligentnie dopasowuje sposób nauki do Twojego stylu uczenia się. 
            Zacznij od <strong>kwestionariusza stylu uczenia się</strong>, aby poznać swój profil
            i otrzymać spersonalizowane szkolenia i testy.
        </p>
    </section>

    <section class="shortcuts">
        <div class="card">
            <h3>🧠 Styl uczenia się</h3>
            <p>Dowiedz się, czy uczysz się lepiej przez wzrok, słuch czy działanie.</p>
            <a href="questionnaire.php" class="btn">Rozpocznij quiz</a>
        </div>

        <div class="card">
            <h3>📚 Szkolenia interaktywne</h3>
            <p>System automatycznie dopasuje sposób prezentacji treści do Twojego stylu.</p>
            <a href="interactive_training.php" class="btn">Rozpocznij naukę</a>
        </div>

        <div class="card">
            <h3>🧩 Testy wiedzy</h3>
            <p>Sprawdź, czego się nauczyłeś — pytania są dobierane adaptacyjnie.</p>
            <a href="test_module.php" class="btn">Rozwiąż test</a>
        </div>
    </section>

    <?php if ($role === 'admin'): ?>
    <section class="admin-tools">
        <h3>🔧 Narzędzia administratora</h3>
        <ul>
            <li><a href="manage_users.php">Zarządzaj użytkownikami</a></li>
            <li><a href="view_statistics.php">Podgląd statystyk</a></li>
            <li><a href="content_editor.php">Edytuj treści</a></li>
        </ul>
    </section>
    <?php endif; ?>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> Inteligentny System Uczący — projekt adaptacyjny</p>
</footer>

<script>
function toggleMenu() {
    document.getElementById('menu').classList.toggle('show');
}
</script>

</body>
</html>