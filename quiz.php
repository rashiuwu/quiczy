<?php
session_start();

if (empty($_SESSION["quiz_questions"])) {
    header("Location: index.php");
    exit;
}

$questions = $_SESSION["quiz_questions"];
$index = (int)($_SESSION["quiz_index"] ?? 0);
$total = count($questions);

if ($index >= $total) {
    header("Location: result.php");
    exit;
}

$q = $questions[$index];
$selected = $_SESSION["last_selected"] ?? 0;
unset($_SESSION["last_selected"]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $answer = (int)($_POST["answer"] ?? 0);
    $correct = (int)$q["answer"];
    $isCorrect = $answer === $correct;

    if ($isCorrect) {
        $_SESSION["quiz_score"] = (int)($_SESSION["quiz_score"] ?? 0) + 1;
    }

    $_SESSION["last_result"] = [
        "correct" => $isCorrect,
        "timed_out" => !empty($_POST["timed_out"]),
        "selected" => $answer,
        "correct_answer" => $correct,
        "correct_text" => $q["options"][$correct - 1] ?? ""
    ];

    $_SESSION["quiz_index"] = $index + 1;
    header("Location: feedback.php");
    exit;
}

$progress = (($index + 1) / $total) * 100;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question <?= $index + 1 ?> — Quiczy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="ambient ambient-one"></div>
<div class="ambient ambient-two"></div>

<main class="page quiz-page">
    <header class="quiz-header">
        <a href="index.php" class="mini-brand">✦ QUICZY</a>
        <div class="player-pill"><?= htmlspecialchars($_SESSION["player_name"] ?? "Player") ?></div>
        <div class="question-count"><?= $index + 1 ?> / <?= $total ?></div>
    </header>

    <div class="progress-track">
        <div class="progress-fill" style="width: <?= $progress ?>%"></div>
    </div>

    <section class="quiz-card glass">
        <div class="meta-row">
            <span class="tag cyan"><?= htmlspecialchars($q["category"]) ?></span>
            <span class="tag pink"><?= htmlspecialchars(strtoupper($q["difficulty"])) ?></span>
        </div>

        <div class="timer-ring" id="timer">
            <span id="timer-number">15</span>
            <small>SEC</small>
        </div>

        <div class="question-number">QUESTION <?= $index + 1 ?></div>
        <h1 class="question-title"><?= htmlspecialchars($q["question"]) ?></h1>

        <form method="POST" id="quiz-form">
            <div class="options">
                <?php foreach ($q["options"] as $i => $option): ?>
                    <label class="option">
                        <input type="radio" name="answer" value="<?= $i + 1 ?>">
                        <span class="option-number"><?= $i + 1 ?></span>
                        <span><?= htmlspecialchars($option) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>

            <input type="hidden" name="timed_out" id="timed-out" value="0">
            <button class="btn btn-primary submit-btn" type="submit">SUBMIT ANSWER <span>→</span></button>
        </form>
    </section>
</main>

<script>
const form = document.getElementById("quiz-form");
const number = document.getElementById("timer-number");
const timer = document.getElementById("timer");
const timedOut = document.getElementById("timed-out");
let seconds = 15;
let submitted = false;

const interval = setInterval(() => {
    seconds--;
    number.textContent = seconds;

    if (seconds <= 5) timer.classList.add("danger");

    if (seconds <= 0) {
        clearInterval(interval);
        submitted = true;
        timedOut.value = "1";
        form.submit();
    }
}, 1000);

form.addEventListener("submit", () => {
    if (!submitted) {
        submitted = true;
        clearInterval(interval);
    }
});
</script>
</body>
</html>
