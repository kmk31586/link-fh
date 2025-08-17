<?php
include 'config.php';

// 处理删除操作
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    // 删除对应ID的链接
    $stmt = $pdo->prepare("DELETE FROM url_links WHERE id = :id");
    $stmt->bindParam(':id', $delete_id);
    $stmt->execute();
    header("Location: " . $_SERVER['PHP_SELF']); // 删除后刷新页面
    exit();
}

// 处理插入操作
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $url = $_POST['url'];
    $mode = $_POST['mode'];

    // 插入数据到数据库
    $stmt = $pdo->prepare("INSERT INTO url_links (url, mode) VALUES (:url, :mode)");
    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':mode', $mode);

    $success = $stmt->execute();

    // 重定向以防表单重复提交
    if ($success) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// 获取所有链接
$stmt = $pdo->query("SELECT * FROM url_links");
$links = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理 - 添加防红链接</title>
    <style>
        /* 美化页面 */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #495057;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1000px; /* 调整最大宽度 */
            box-sizing: border-box;
        }
        h1 {
            text-align: center;
            color: #007bff;
            font-size: 28px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        label {
            font-size: 18px;
            color: #495057;
            margin-bottom: 8px;
            display: block;
        }
        input[type="text"], select, button {
            width: 100%;
            padding: 14px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            font-size: 16px;
            background-color: #f8f9fa;
            margin-top: 10px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        td {
            word-wrap: break-word; /* 允许单词换行 */
            word-break: break-all; /* 长链接也会换行 */
            max-width: 250px; /* 限制最大宽度 */
        }
        td button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            font-weight: bold;
        }
        td button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>添加防红链接</h1>
        <form method="POST">
            <div class="form-group">
                <label for="url">防红链接：</label>
                <input type="text" id="url" name="url" required placeholder="输入防红链接">
            </div>
            <div class="form-group">
                <label for="mode">选择模式：</label>
                <select id="mode" name="mode">
                    <option value="1">内嵌框架</option>
                    <option value="2">跳转模式</option>
                </select>
            </div>
            <button type="submit">添加链接</button>
        </form>

        <h2>已添加的防红链接</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>防红链接</th>
                    <th>模式</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($links as $link) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($link['id']); ?></td>
                        <td><?php echo htmlspecialchars($link['url']); ?></td>
                        <td><?php echo $link['mode'] == 1 ? '内嵌框架' : '跳转模式'; ?></td>
                        <td><a href="?delete_id=<?php echo $link['id']; ?>"><button>删除</button></a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>
