<?php
// config.php
$host = 'localhost';   // 数据库主机
$dbname = '';    // 数据库名称
$username = '';    // 数据库用户名
$password = '';        // 数据库密码

try {
    // 创建数据库连接
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // 设置错误模式
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "数据库连接失败: " . $e->getMessage();
}
?>
