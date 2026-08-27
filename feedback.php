<?php
session_start();

if (!isset($_SESSION["last_result"])) {
    header("Location: index.php");
    exit;
}

$result = $_SESSION["last_result"];
unset($_SESSION["last_result"]);

$correct = $result["correct"];
$timedOut = $result["timed_out"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback — Quiczy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="ambient ambient-one"></div>
<div class="ambient ambient-two"></div>

<main class="page center-page">
    <section class="feedback-card glass <?= $correct ? "success" : "failure" ?>">
        <div class="result-icon"><?= $correct ? "✓" : ($timedOut ? "⏰" : "✕") ?></div>
        <div class="eyebrow"><?= $correct ? "NICE WORK" : "KEEP GOING" ?></div>
        <h1><?= $correct ? "Correct!" : ($timedOut ? "Time's up!" : "Not quite!") ?></h1>
        <p><?= $correct ? "+1 point added to your score." : "The correct answer was:" ?></p>

        <div class="answer-box">
            <strong><?= (int)$result["correct_answer"] ?>.</strong>
            <?= htmlspecialchars($result["correct_text"]) ?>
        </div>

        <a class="btn btn-primary btn-wide link-btn" href="quiz.php">NEXT QUESTION <span>→</span></a>
    </section>
</main>
</body>
</html>
