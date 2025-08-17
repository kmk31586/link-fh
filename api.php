<?php
include 'config.php';

// 设置返回格式为JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mode']) && isset($_POST['targetLink'])) {
    $mode = $_POST['mode'];
    $targetLink = $_POST['targetLink'];

    // 将用户的URL转换为Base64编码
    $base64Link = base64_encode($targetLink);

    // 获取数据库中的URL
    $stmt = $pdo->prepare("SELECT url FROM url_links WHERE mode = :mode ORDER BY RAND() LIMIT 1");
    $stmt->bindParam(':mode', $mode);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // 添加时间戳参数
        $timestamp = time();
        $finalLink = $row['url'] . "?s=" . urlencode($base64Link) . "&timestamp=" . $timestamp;
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($finalLink);

        // 返回生成的链接和二维码URL
        echo json_encode([
            'status' => 'success',
            'finalLink' => $finalLink,
            'qrCodeUrl' => $qrCodeUrl
        ]);
    } else {
        // 返回错误信息
        echo json_encode([
            'status' => 'error',
            'message' => '没有找到可用的URL。'
        ]);
    }
} else {
    // 返回参数缺失的错误信息
    echo json_encode([
        'status' => 'error',
        'message' => '请求缺少必要参数。'
    ]);
}
?>
