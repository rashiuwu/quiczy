<?php
session_start();

if (!isset($_SESSION["quiz_questions"])) {
    header("Location: index.php");
    exit;
}

$total = count($_SESSION["quiz_questions"]);
$score = (int)($_SESSION["quiz_score"] ?? 0);
$name = $_SESSION["player_name"] ?? "Player";
$category = $_SESSION["category"] ?? "All";
$difficulty = $_SESSION["difficulty"] ?? "All";
$percentage = $total > 0 ? round(($score / $total) * 100, 2) : 0;

$scoresFile = __DIR__ . "/scores.json";
$scores = file_exists($scoresFile) ? json_decode(file_get_contents($scoresFile), true) : [];
if (!is_array($scores)) $scores = [];

$scores[] = [
    "name" => $name,
    "score" => $score,
    "total" => $total,
    "percentage" => $percentage,
    "category" => $category,
    "difficulty" => $difficulty,
    "date" => date("Y-m-d H:i:s")
];

file_put_contents($scoresFile, json_encode($scores, JSON_PRETTY_PRINT), LOCK_EX);

if ($percentage >= 80) {
    $message = "You're a Quiczy champion!";
    $class = "excellent";
} elseif ($percentage >= 60) {
    $message = "Great job — keep sharpening!";
    $class = "great";
} elseif ($percentage >= 40) {
    $message = "Good effort — practice makes progress.";
    $class = "okay";
} else {
    $message = "Keep learning and try again!";
    $class = "try";
}

session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results — Quiczy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="ambient ambient-one"></div>
<div class="ambient ambient-two"></div>

<main class="page center-page">
    <section class="result-card glass <?= $class ?>">
        <div class="eyebrow">QUIZ COMPLETE</div>
        <h1><?= htmlspecialchars($name) ?></h1>
        <p class="result-message"><?= $message ?></p>

        <div class="score-big"><?= $score ?><span>/<?= $total ?></span></div>
        <div class="percentage"><?= number_format($percentage, 0) ?>%</div>

        <div class="result-details">
            <div><small>CATEGORY</small><strong><?= htmlspecialchars($category) ?></strong></div>
            <div><small>DIFFICULTY</small><strong><?= htmlspecialchars($difficulty) ?></strong></div>
        </div>

        <div class="actions">
            <a class="btn btn-primary link-btn" href="index.php">PLAY AGAIN</a>
            <a class="btn btn-secondary link-btn" href="leaderboard.php">LEADERBOARD</a>
        </div>
    </section>
</main>
</body>
</html>
