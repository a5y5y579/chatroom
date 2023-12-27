<?php
// 設置時區為台北
date_default_timezone_set('Asia/Taipei');

// 資料庫連線設定
$host = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "message";

// 啟動 session
session_start();

// 檢查使用者是否已登入
if (!isset($_SESSION['username'])) {
    // 如果未登入，返回空字串
    echo '';
    exit();
}

// 創建資料庫連線
$conn = new mysqli($host, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 如果有 POST 資料，表示有新訊息
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $message = $_POST['message'];

    // 防止 SQL 注入攻擊，這只是一個簡單的例子，實際應用中需要更多安全處理
    $username = $conn->real_escape_string($username);
    $message = $conn->real_escape_string($message);

    if (!empty($username) && !empty($message)) {
        // 將新訊息寫入資料庫
        $timestamp = date('H:i:s'); // 24 小時制時間格式
        $sql = "INSERT INTO record (username, message, timestamp) VALUES ('$username', '$message', '$timestamp')";
        $conn->query($sql);
    }
} else {
    // 如果是 GET 請求，表示需要檢查新訊息
    $sql = "SELECT * FROM record ORDER BY timestamp DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // 直接將查詢結果轉換成 JSON 字串
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    } else {
        // 如果沒有資料，回傳空陣列
        echo '[]';
    }
}

// 關閉資料庫連線
$conn->close();
?>
