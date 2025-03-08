<?php

header('Content-Type: application/json; charset=utf-8'); // 设置返回 JSON 格式

$log_file = 'logs.json'; // 存储日志的 JSON 文件

// 获取传递的参数
$key = isset($_GET['key']) ? $_GET['key'] : ''; // 获取 key 值
$wz = isset($_GET['wz']) ? $_GET['wz'] : ''; // 测试网站
$cs = isset($_GET['cs']) ? (int)$_GET['cs'] : 10; // 请求次数，默认 10
$bf = isset($_GET['bf']) ? (int)$_GET['bf'] : 3; // 并发次数，默认 3
$retry = 3; // 重试次数，默认 3

// 检查 key 是否正确
if ($key !== '8888') {
    $error_result = ["错误" => "无效的 key，无法执行测试！"];
    echo json_encode($error_result, JSON_UNESCAPED_UNICODE);
    exit;
}

// 检查 URL 是否为空
if (empty($wz)) {
    $error_result = ["错误" => "未指定测试网站！"];
    echo json_encode($error_result, JSON_UNESCAPED_UNICODE);
    exit;
}

// 限制最大请求次数
if ($cs > 50000) {
    $error_result = ["错误" => "请求次数不能超过 50,000 次！"];
    echo json_encode($error_result, JSON_UNESCAPED_UNICODE);
    exit;
}

// 存储结果
$success_count = 0;
$fail_count = 0;
$max_time = 0;
$min_time = PHP_INT_MAX;
$total_time = 0;

// 执行性能测试
for ($i = 0; $i < $cs; $i++) {
    $start_time = microtime(true); // 请求开始时间

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $wz);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // 设置超时
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $end_time = microtime(true); // 请求结束时间

    $response_time = $end_time - $start_time; // 计算响应时间

    // 记录最大、最小响应时间
    if ($response_time > $max_time) $max_time = $response_time;
    if ($response_time < $min_time) $min_time = $response_time;

    // 统计请求成功或失败
    if ($http_code == 200) {
        $success_count++;
    } else {
        $fail_count++;
    }

    $total_time += $response_time;

    curl_close($ch);
}

// 计算平均响应时间
$average_time = $total_time / $cs;

// 创建结果数组
$result = [
    "时间戳" => date("Y-m-d H:i:s"),
    "攻击网站" => $wz,
    "请求次数" => $cs,
    "并发次数" => $bf,
    "重试次数" => $retry,
    "请求成功次数" => $success_count,
    "请求失败次数" => $fail_count,
    "最大响应时间（秒）" => number_format($max_time, 4),
    "最小响应时间（秒）" => number_format($min_time, 4),
    "平均响应时间（秒）" => number_format($average_time, 4),
];

// 读取现有 JSON 文件
$log_data = [];
if (file_exists($log_file)) {
    $log_content = file_get_contents($log_file);
    if (!empty($log_content)) {
        $log_data = json_decode($log_content, true);
    }
}

// 限制 JSON 文件大小（最多保存 1000 条记录）
if (count($log_data) >= 1000) {
    array_shift($log_data); // 删除最早的一条记录
}

// 添加新记录
$log_data[] = $result;

// 保存到 JSON 文件
file_put_contents($log_file, json_encode($log_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

// 返回 JSON 结果
echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>
