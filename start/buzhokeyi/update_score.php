<?php
$scoreFile = __DIR__ . '/buzhokeyi_scores.json';
$name = $_POST['name'] ?? '';
$score = isset($_POST['score']) ? (int)$_POST['score'] : 0;
if ($name === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Name required']);
    exit;
}

if (!file_exists($scoreFile)) {
    file_put_contents($scoreFile, json_encode([]));
}

$data = json_decode(file_get_contents($scoreFile), true);
if (!is_array($data)) {
    $data = [];
}

$data[] = ['name' => $name, 'score' => $score];
usort($data, function ($a, $b) {
    return $b['score'] <=> $a['score'];
});
$data = array_slice($data, 0, 5);
file_put_contents($scoreFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(['success' => true, 'scores' => $data]);
?>
