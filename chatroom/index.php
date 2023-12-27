<?php
// 啟動 session
session_start();

// 檢查使用者是否已登入
if (!isset($_SESSION['username'])) {
    // 如果未登入，導向到登入頁面
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fancy Chat Room</title>
    <!-- 使用 Bootstrap 的 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- 使用 Font Awesome 的 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #3494E6, #EC6EAD);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 50px;
        }

        #chat-box {
            height: 500px;
            overflow-y: scroll;
            border-bottom: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 10px 10px 0 0;
            background-color: #f8f9fa;
        }

        .message {
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 10px;
            background-color: #4B0082;
            color: #ffffff;
            animation: fadeIn 0.5s ease-out, moveUp 0.5s ease-out, bounce 1s ease-out;
            position: relative;
        }

        .message strong {
            font-weight: bold;
        }

        .message span {
            display: block;
            margin-top: 5px;
            font-size: 0.8em;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes moveUp {
            from {
                transform: translateY(20px);
            }
            to {
                transform: translateY(0);
            }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }

        .input-group {
            padding: 10px;
            border-top: 1px solid #ccc;
            border-radius: 0 0 10px 10px;
            background-color: #f8f9fa;
        }

        .btn-primary {
            background-color: #800080;
            border-color: #800080;
        }

        .btn-primary:hover {
            background-color: #4B0082;
            border-color: #4B0082;
        }

        .btn-danger {
            background-color: #DC143C;
            border-color: #DC143C;
        }

        .btn-danger:hover {
            background-color: #8B0000;
            border-color: #8B0000;
        }

        input.form-control {
            border-radius: 5px;
            border-color: #800080;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="mt-3 mb-3 text-center">Fancy Chat Room</h2>
                <div id="chat-box" class="bg-light"></div>
                <div class="input-group">
                    <input type="text" id="username" class="form-control" placeholder="你的名字">
                    <input type="text" id="message" class="form-control" placeholder="輸入訊息...">
                    <button onclick="sendMessage()" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                    <!-- 登出按鈕 -->
                    <button onclick="logout()" class="btn btn-danger">登出</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateChat(messages) {
            var chatBox = document.getElementById('chat-box');

            // 反轉 messages，最新的訊息會顯示在最底部
            messages.reverse();
            
            messages.forEach(function (data) {
                var messageDiv = document.createElement('div');
                messageDiv.className = 'message';
                messageDiv.innerHTML = '<strong>' + data.username + '</strong>: ' + data.message +
                    '<span class="float-end">' + data.timestamp + '</span>';
                chatBox.appendChild(messageDiv);
            });

            chatBox.scrollTop = chatBox.scrollHeight; // 滾動到最底部
        }

        function sendMessage() {
            var username = document.getElementById('username').value;
            var message = document.getElementById('message').value;

            if (username.trim() === '' || message.trim() === '') {
                alert('請輸入名字和訊息！');
                return;
            }

            var timestamp = new Date().toLocaleTimeString();

            // 發送消息到 chat.php
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'chat.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send('username=' + encodeURIComponent(username) + '&message=' + encodeURIComponent(message));

            // 傳送成功後，只更新新發送的消息
            var newMessage = {
                username: username,
                message: message,
                timestamp: timestamp
            };
            updateChat([newMessage]);
        }

        function fetchMessages() {
            // 發送 GET 請求到 chat.php
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'chat.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = xhr.responseText;
                    var messages = JSON.parse(response);
                    updateChat(messages);
                }
            };
            xhr.send();
        }

        function logout() {
            // 發送 GET 請求到 chat.php 以處理登出
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'chat.php?logout=true', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // 導向到登入頁面
                    window.location.href = 'login.html';
                }
            };
            xhr.send();
        }

        // 初始化時只檢查一次新訊息
        fetchMessages();
    </script>
</body>
</html>
