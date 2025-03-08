<?php
header("Content-Type: application/json; charset=UTF-8");

$file = "logs.json";

if (!file_exists($file)) {
    echo json_encode(["error" => "JSON 文件不存在"]);
    exit;
}

$data = json_decode(file_get_contents($file), true);

if (!$data) {
    echo json_encode(["error" => "JSON 文件为空或损坏"]);
    exit;
}

echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
