<?php
header('Content-Type: application/json');
$file = __DIR__ . '/scores.json';
$data = is_file($file) ? json_decode(file_get_contents($file), true) : [];
$data = is_array($data) ? $data : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        echo json_encode($data);
        exit;
    }
    $found = false;
    foreach ($data as &$entry) {
        if ($entry['name'] === $name) {
            $entry['score'] += 1;
            $found = true;
            break;
        }
    }
    unset($entry);
    if (!$found) {
        $data[] = ['name' => $name, 'score' => 1];
    }
    usort($data, function($a, $b){return $b['score'] <=> $a['score'];});
    $data = array_slice($data, 0, 5);
    file_put_contents($file, json_encode($data));
    echo json_encode($data);
    exit;
}

// GET request just returns top scores
echo json_encode($data);
