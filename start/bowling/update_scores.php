<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}
$score = intval($_POST['score'] ?? 0);
$name = $_SESSION['player'] ?? 'Unknown';
$scoreFile = __DIR__ . '/bowling_scores.json';
$scores = [];
if (file_exists($scoreFile)) {
    $json = file_get_contents($scoreFile);
    $scores = json_decode($json, true) ?: [];
}
$scores[] = ['name' => $name, 'score' => $score];
usort($scores, function($a, $b) {
    return $b['score'] <=> $a['score'];
});
$scores = array_slice($scores, 0, 5);
file_put_contents($scoreFile, json_encode($scores, JSON_PRETTY_PRINT));
echo json_encode(['success' => true]);
