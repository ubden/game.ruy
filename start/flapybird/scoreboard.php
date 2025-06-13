<?php
header('Content-Type: application/json');
$scoreFile = __DIR__ . '/flapybird_scores.json';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$score = isset($_POST['score']) ? intval($_POST['score']) : 0;
if ($name === '') {
    echo json_encode(['success' => false, 'message' => 'Name required']);
    exit;
}
if (!file_exists($scoreFile)) {
    file_put_contents($scoreFile, json_encode(['top_scores' => []]));
}
$data = json_decode(file_get_contents($scoreFile), true);
if (!$data) {
    $data = ['top_scores' => []];
}
$data['top_scores'][] = ['name' => $name, 'score' => $score];
usort($data['top_scores'], function($a, $b) {
    return $b['score'] <=> $a['score'];
});
$data['top_scores'] = array_slice($data['top_scores'], 0, 5);
file_put_contents($scoreFile, json_encode($data, JSON_PRETTY_PRINT));
echo json_encode(['success' => true, 'scores' => $data['top_scores']]);
?>
