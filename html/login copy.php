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
                <form action="login copy.php" method="post">
                <h1>register</h1>
                <div class="search-container"> 
                    <input name="registerName" type="text" class="search-input" placeholder=" " required>
                    <span class="line"></span>
                    <label for="name" class="search-placeholder">用戶名</label>
                </div>
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
                <form action="Register.php" method="post">
                <h1>login</h1>
                <div class="search-container">
                    <input name="loginName" type="text" class="search-input" placeholder=" " required>
                    <span class="line"></span>
                    <label class="search-placeholder">用戶名</label>
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
<?php
$file = 'userdata.json';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $registerName = isset($_POST['registerName']) ? $_POST['registerName'] : null;
    $registerEmail = isset($_POST['registerEmail']) ? $_POST['registerEmail'] : null;
    $registerPassword = isset($_POST['registerPassword']) ? $_POST['registerPassword'] : null;
    $userPassword = isset($_POST['userpassword']) ? $_POST['userpassword'] : null;

    if ($registerName === null || $registerEmail === null || $registerPassword === null || $userPassword === null) {

        echo "<script>showAlert('All fields are required.',true);</script>";
        
        exit;
    }

    if ($registerPassword !== $userPassword) {
        echo "<script>showAlert('Passwords do not match.',true);</script>";

        exit;
    }

    $users = json_decode(file_get_contents($file), true);
    if ($users === null) {
        $users = [];
    }else{
    foreach ($users as $user) {
        if ($user['username'] == $registerName) {
            echo "<script>showAlert('Username is already exists. You can now login.',true);</script>";
            exit();
        }
    }
}

    $users[] = array('username' => $registerName, 'email' => $registerEmail, 'password' => $registerPassword);
    echo "<script>showAlert('Registration successful. You can now login.',true);</script>";
    file_put_contents($file, json_encode($users));
    exit();




// $servername="localhost";
// $username="root";
// $userpassword="";
// $dbname="userdatabase";

// $conn=new mysqli($servername,$username,$userpassword,$dbname);

// if($conn->connect_error){
//     die("connect_error" . $conn->connect_error);
// }
// $sql="INSERT INFO users(name, email, password) VALUES('$name,$email,$password')";
// if($conn->query($sql)===TRUE){
//     echo "good";
// }else{
//     echo "error";
// }
// $conn->close();
}
?>
</body>
</html>