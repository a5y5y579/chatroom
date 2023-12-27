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

// 初始化錯誤訊息
$error_message = "";

// 接收來自登入表單的資料
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // 在這裡進行資料庫中使用者名稱和密碼的驗證
    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            // 登入成功，將使用者資訊存入 Session
            session_start();
            $_SESSION['username'] = $row['username'];

            // 將使用者導向到 index.php
            header("Location: index.php");
            exit();
        } else {
            $error_message = "密碼錯誤";
        }
    } else {
        $error_message = "使用者不存在";
    }
}

$conn->close();
?>

<!DOCTYPE html>  
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatroom</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: '微軟正黑體', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(45deg, #141e30, #243b55);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            width: 300px;
            background: linear-gradient(45deg, #1b2838, #2d485e);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            text-align: center;
            color: #fff;
            animation: fadeIn 0.8s ease-out;
            position: relative;
        }

        h2 {
            color: #61dafb;
            margin-bottom: 20px;
            font-size: 28px;
        }

        .error-message {
            color: #ff4f4f;
            margin-top: 10px;
            animation: fadeIn 0.8s ease-out;
        }

        .back-to-login,
        .register-link {
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 4px;
            transition: background 0.3s, color 0.3s;
            animation: fadeIn 0.8s ease-out;
        }

        .back-to-login:hover,
        .register-link:hover {
            background: #1e87eb;
            color: #fff;
        }

        hr {
            border-color: #61dafb;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        button {
            background: linear-gradient(45deg, #61dafb, #2196f3);
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            transition: background 0.5s, transform 0.3s, box-shadow 0.3s;
        }

        button:hover {
            background: linear-gradient(45deg, #1e87eb, #155f96);
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(97, 218, 251, 0.7);
        }

        button:active {
            transform: scale(0.95);
            box-shadow: 0 0 10px rgba(97, 218, 251, 0.5);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animation-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            background: #61dafb;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            animation: moveParticle 2s infinite;
            animation-delay: calc(-1 * var(--delay));
        }

        @keyframes moveParticle {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-30px);
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="animation-container">
            <?php for ($i = 0; $i < 20; $i++) : ?>
                <div class="particle" style="--delay: <?php echo $i * 0.1; ?>s;"></div>
            <?php endfor; ?>
        </div>

        <i class="fas fa-lock"></i>
        <h2>安全登入</h2>

        <!-- 顯示錯誤訊息 -->
        <?php if (!empty($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <div class="back-to-login">
            <a href="login.html">返回登入頁面</a>
        </div>

        <hr>

        <a href="register.html" class="register-link">註冊新帳號</a>
    </div>
</body>
</html>
