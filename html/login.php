<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/login.css">
<link rel="stylesheet" href="../css/search.css">
<title>Login 弹出窗口</title>
</head>
<body>
<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'php-assginment');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function showAlert($message, $isError = false) {
    $class = $isError ? 'error' : 'success';
    echo "<script>
        var alertDiv = document.createElement('div');
        alertDiv.textContent = '$message';
        alertDiv.className = 'Alert $class';
        alertDiv.style.display = 'block';
        alertDiv.style.padding = '10px';
        alertDiv.style.margin = '10px 0';
        alertDiv.style.border = '1px solid " . ($isError ? 'red' : 'green') . "';
        document.body.insertBefore(alertDiv, document.body.firstChild);
        setTimeout(function() {
            alertDiv.style.display = 'none';
        }, 5000);
    </script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    
    if (isset($_POST['registerEmail']) && isset($_POST['registerPassword']) && isset($_POST['userpassword'])) {
        $email = trim($_POST['registerEmail']);
        $password = trim($_POST['registerPassword']);
        $confirmPassword = trim($_POST['userpassword']);
        
        if (empty($email) || empty($password) || empty($confirmPassword)) {
            showAlert("所有字段都是必填的。请确保您填写了所有表单字段。", true);
        } elseif ($password !== $confirmPassword) {
            showAlert("密码不匹配。请确保两次输入的密码相同。", true);
        } else {
    
            
            $stmt = $conn->prepare("SELECT member_id FROM member WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($cresult->num_rows > 0) {
                showAlert("该邮箱已被注册。您可以直接登录。", true);
            } else {
                $stmt = $conn->prepare("INSERT INTO member (email, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $email, $password);
                
                if ($stmt->execute()) {
                    $member_id = $conn->insert_id;
                    showAlert("注册成功！您现在可以登录了。");
                    header('Location:memberInformation.php');
                    $_SESSION['member_id'] = $member_id;
                    exit();
                } else {
                    showAlert("注册失败: " . $conn->error, true);
                }
            }
            $stmt->close();
        }
    } elseif (isset($_POST['email']) && isset($_POST['loginPassword'])) {
        // Login code (unchanged)
        $email = trim($_POST['email']);
        $password = trim($_POST['loginPassword']);
        
        if (empty($email) || empty($password)) {
            showAlert("用户名或密码不能为空。", true);
        } else {
            $stmt = $conn->prepare("SELECT member_id, password FROM member WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Verify password
                if ($password === $row['password']) {
                    // Member login successful
                    $_SESSION['member_id'] = $row['member_id'];
                    $_SESSION['email'] = $email;
                    showAlert("登录成功！");
                    header('Location: home.php');
                    exit();
                } else {
                    showAlert("密码错误。请重试。", true);
                }
            } else {
                // Member not found, check admin_member table
                $stmt = $conn->prepare("SELECT admin_id, password FROM admin_member WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $adminResult = $stmt->get_result();
                
                if ($adminResult->num_rows > 0) {
                    $adminRow = $adminResult->fetch_assoc();
                    // Verify admin password
                    if ($password === $adminRow['password']) {
                        // Admin login successful
                        $_SESSION['admin_id'] = $adminRow['admin_id'];
                        $_SESSION['email'] = $email;
                        showAlert("管理员登录成功！");
                        header('Location: administratorhome.php');
                        exit();
                    } else {
                        showAlert("管理员密码错误。请重试。", true);
                    }
                } else {
                    showAlert("用户不存在。请注册或检查您的输入。", true);
                }
            }
            $stmt->close();
        }
    } else {
        showAlert("无效的表单提交。请检查您的表单。", true);
    }
}



$conn->close();
?>
<style>
        .search-container {
            width: 300px;
            height: 30px;
            margin: 30px 10px;
        }

        .line {
            top: 50px;

        }
    </style>
    
    <div id="alert" class="Alert"></div>
    <div class="container">
        <div class="form-box">
            <div class="register-box hidden">
                <form action="login.php" method="post">
                <h1>register</h1>
                
                <div class="search-container">
                    <input name="registerEmail" type="email" class="search-input" placeholder=" " required>
                    <span class="line"></span>
                    <label class="search-placeholder">郵箱</label>
                </div>
                <div class="search-container">
                    <input name="registerPassword" type="password" class="search-input" placeholder=" " required>
                    <span class="line"></span>
                    <label for="password" class="search-placeholder">密碼</label>
                </div>
                <div class="search-container">
                    <input name="userpassword" type="password" class="search-input" placeholder=" " required>
                    <span class="line"></span>
                    <label for="userpassword" class="search-placeholder">確認密碼</label>
                </div>
                <button type="submit" >注冊</button>
            </form>
            </div>
            <div class="login-box">
                <form action="login.php" method="post">
                <h1>login</h1>
                <div class="search-container">
                    <input name="email" type="text" class="search-input" placeholder=" " required>
                    <span class="line"></span>
                    <label class="search-placeholder">Email</label>
                </div>
                <div class="search-container">
                    <input name="loginPassword" type="password" class="search-input" placeholder=" " required>
                    <span class="line"></span>
                    <label class="search-placeholder">密碼</label>
                </div>
                <button type="submit" >登錄</button>
            </form>
            </div>
        </div>
        <div class="con-box left">
            <h2>歡迎來到<span>客戶資料管理網站</span></h2>
            <p></p>
            <img src=" ../img/cartoon 1.png" alt="cartoon">
            <p>已有賬號？</p>
            <button id="register" onclick="login()">去登陸</button>
        </div>
        <div class="con-box right">
            <h2>請登錄<span>客戶資料管理網站</span></h2>
            <img src=" ../img/cartoon 2.png" alt="cartoon">
            <p>沒有賬號？</p>
            <button id="register" onclick="register()">去注冊</button>
        </div>
    </div>
    <script src="../js/login.js">
</script>
</body>
</html>