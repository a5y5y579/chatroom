<?php
// 資料庫連線
$host = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "message";

$conn = new mysqli($host, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 初始化消息變量
$message = "";

// 接收來自註冊表單的資料
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // 檢查使用者名稱是否已經存在
    $check_username_query = "SELECT * FROM user WHERE username='$username'";
    $check_username_result = $conn->query($check_username_query);

    if ($check_username_result->num_rows > 0) {
        // 用戶已存在
        $message = "用戶已存在";
    } else {
        // 密碼雜湊處理
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 執行 SQL 插入語句
        $insert_query = "INSERT INTO user (username, password) VALUES ('$username', '$hashed_password')";

        if ($conn->query($insert_query) === TRUE) {
            $message = "註冊成功";
        } else {
            $message = "Error: " . $insert_query . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>使用者註冊</title>
    <!-- 使用 Bootstrap 的 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .btn-primary {
            background-color: #800080;
            border-color: #800080;
            padding: 10px 20px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #4B0082;
            border-color: #4B0082;
        }

        .alert {
            margin-top: 20px;
        }

        .back-to-login {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">使用者註冊</h2>
        <!-- 簡化表單 -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- 在這裡放置你的表單字段 -->
        </form>

        <?php
        // 顯示消息
        echo '<div class="alert alert-danger">' . $message . '</div>';
        ?>

        <!-- 使用 Bootstrap 按鈕樣式 -->
        <a href="login.html" class="btn btn-primary back-to-login">返回登入</a>
    </div>
</body>
</html>
