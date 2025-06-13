<?php
header('Content-Type: application/json');
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
if ($name === '') {
    echo json_encode(['error' => 'No name']);
    exit;
}
$file = __DIR__ . '/scoreboard.json';
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : ['players' => []];
$found = false;
foreach ($data['players'] as &$player) {
    if ($player['name'] === $name) {
        $player['score'] += 1;
        $found = true;
        break;
    }
}
unset($player);
if (!$found) {
    $data['players'][] = ['name' => $name, 'score' => 1];
}
usort($data['players'], function ($a, $b) {
    return $b['score'] <=> $a['score'];
});
$data['players'] = array_slice($data['players'], 0, 5);
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
echo json_encode(['success' => true]);
