<?php
session_start();

$questionsFile = __DIR__ . "/questions.json";
$questions = file_exists($questionsFile) ? json_decode(file_get_contents($questionsFile), true) : [];

$categories = ["Linux", "Python", "Java", "SQL", "General IT", "All"];
$difficulties = ["Easy", "Medium", "Hard", "All"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $category = $_POST["category"] ?? "All";
    $difficulty = $_POST["difficulty"] ?? "All";

    if ($name === "") {
        $error = "Please enter your name.";
    } elseif (!in_array($category, $categories, true) || !in_array($difficulty, $difficulties, true)) {
        $error = "Invalid quiz selection.";
    } else {
        $_SESSION["player_name"] = htmlspecialchars($name, ENT_QUOTES, "UTF-8");
        $_SESSION["category"] = $category;
        $_SESSION["difficulty"] = $difficulty;

        $filtered = array_values(array_filter($questions, function ($q) use ($category, $difficulty) {
            $categoryOk = $category === "All" || ($q["category"] ?? "") === $category;
            $difficultyOk = $difficulty === "All" || ($q["difficulty"] ?? "") === $difficulty;
            return $categoryOk && $difficultyOk;
        }));

        shuffle($filtered);
        $_SESSION["quiz_questions"] = array_slice($filtered, 0, 10);
        $_SESSION["quiz_score"] = 0;
        $_SESSION["quiz_index"] = 0;

        if (count($_SESSION["quiz_questions"]) > 0) {
            header("Location: quiz.php");
            exit;
        }

        $error = "No questions match that category and difficulty.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiczy — IT Quiz</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="ambient ambient-one"></div>
<div class="ambient ambient-two"></div>

<main class="page center-page">
    <section class="hero-card glass">
        <div class="brand"><span>✦</span> QUICZY</div>
        <div class="eyebrow">IT QUIZ</div>
        <h1>Test your <span>tech brain.</span></h1>
        <p class="hero-copy">Linux • Python • Java • SQL • General IT</p>

        <?php if (!empty($error)): ?>
            <div class="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="setup-form">
            <label>Your name</label>
            <input type="text" name="name" maxlength="40" placeholder="Enter your name" required>

            <div class="two-col">
                <div>
                    <label>Category</label>
                    <select name="category">
                        <?php foreach ($categories as $item): ?>
                            <option value="<?= htmlspecialchars($item) ?>"><?= htmlspecialchars($item) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Difficulty</label>
                    <select name="difficulty">
                        <?php foreach ($difficulties as $item): ?>
                            <option value="<?= htmlspecialchars($item) ?>"><?= htmlspecialchars($item) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <button class="btn btn-primary btn-wide" type="submit">START QUIZ <span>→</span></button>
        </form>

        <a class="btn btn-secondary btn-wide link-btn" href="leaderboard.php">VIEW LEADERBOARD</a>

        <div class="feature-row">
            <div><strong>2-10</strong><small>Questions</small></div>
            <div><strong>15s</strong><small>Per question</small></div>
            <div><strong>∞</strong><small>Replay</small></div>
        </div>
    </section>

    <p class="footer-note">Quiczy • PHP Web Edition • Linux </p>
</main>
</body>
</html>
