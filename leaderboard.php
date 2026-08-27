<?php
$scoresFile = __DIR__ . "/scores.json";
$scores = file_exists($scoresFile) ? json_decode(file_get_contents($scoresFile), true) : [];
if (!is_array($scores)) $scores = [];

usort($scores, function ($a, $b) {
    $percentage = ($b["percentage"] ?? 0) <=> ($a["percentage"] ?? 0);
    if ($percentage !== 0) return $percentage;
    return ($b["score"] ?? 0) <=> ($a["score"] ?? 0);
});

$scores = array_slice($scores, 0, 10);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard — Quiczy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="ambient ambient-one"></div>
<div class="ambient ambient-two"></div>

<main class="page leaderboard-page">
    <div class="leaderboard-heading">
        <a href="index.php" class="mini-brand">✦ QUICZY</a>
        <div class="eyebrow">TOP QUIZ SCORES</div>
        <h1>Leaderboard</h1>
    </div>

    <section class="leaderboard-card glass">
        <?php if (!$scores): ?>
            <div class="empty-state">No scores yet. Be the first to play!</div>
        <?php else: ?>
            <?php foreach ($scores as $rank => $entry): ?>
                <div class="score-row">
                    <div class="rank">#<?= $rank + 1 ?></div>
                    <div class="score-player">
                        <strong><?= htmlspecialchars($entry["name"] ?? "Unknown") ?></strong>
                        <small><?= htmlspecialchars($entry["category"] ?? "All") ?> • <?= htmlspecialchars($entry["difficulty"] ?? "All") ?></small>
                    </div>
                    <div class="score-value">
                        <strong><?= (int)($entry["score"] ?? 0) ?>/<?= (int)($entry["total"] ?? 0) ?></strong>
                        <small><?= number_format((float)($entry["percentage"] ?? 0), 0) ?>%</small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <a class="btn btn-primary link-btn back-home" href="index.php">← HOME</a>
</main>
</body>
</html>
