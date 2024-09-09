<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/login.css">
<link rel="stylesheet" href="../css/search.css">
<title>Login Page</title>
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
            showAlert("All fields are required. Please make sure you fill out all form fields.", true);
        } elseif ($password !== $confirmPassword) {
            showAlert("The passwords do not match. Please make sure you enter the same password twice.", true);
        } else {
    
            
            $stmt = $conn->prepare("SELECT member_id FROM member WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($cresult->num_rows > 0) {
                showAlert("This email address has been registered. You can log in directly.", true);
            } else {
                $stmt = $conn->prepare("INSERT INTO member (email, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $email, $password);
                
                if ($stmt->execute()) {
                    $member_id = $conn->insert_id;
                    showAlert("Registration successful! You can now log in.");
                    header('Location:memberInformation.php');
                    $_SESSION['member_id'] = $member_id;
                    exit();
                } else {
                    showAlert("Registration failed:" . $conn->error, true);
                }
            }
            $stmt->close();
        }
    } elseif (isset($_POST['email']) && isset($_POST['loginPassword'])) {
        // Login code (unchanged)
        $email = trim($_POST['email']);
        $password = trim($_POST['loginPassword']);
        
        if (empty($email) || empty($password)) {
            showAlert("Username or password cannot be empty.", true);
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
                    showAlert("Login successful!");
                    header('Location: home.php');
                    exit();
                } else {
                    showAlert("Wrong password. Please try again.", true);
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
                        showAlert("Administrator login successful!");
                        header('Location: administratorhome.php');
                        exit();
                    } else {
                        showAlert("Wrong password. Please try again.", true);
                    }
                } else {
                    showAlert("member does not exist. Please register or check your input.", true);
                }
            }
            $stmt->close();
        }
    } else {
        showAlert("Invalid form submission. Please check your form.", true);
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
                    <label class="search-placeholder">Email</label>
                </div>
                <div class="search-container">
                    <input name="registerPassword" type="password" class="search-input" placeholder=" " required>
                    <span class="line"></span>
                    <label for="password" class="search-placeholder">password</label>
                </div>
                <div class="search-container">
                    <input name="userpassword" type="password" class="search-input" placeholder=" " required>
                    <span class="line"></span>
                    <label for="userpassword" class="search-placeholder">Confirm Password</label>
                </div>
                <button type="submit" >Registration</button>
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
                    <label class="search-placeholder">Password</label>
                </div>
                <button type="submit" >Log In</button>
            </form>
            </div>
        </div>
        <div class="con-box left">
            <h2>Welcome to the<span> event booking website</span></h2>
            <p></p>
            <img src=" ../img/cartoon 1.png" alt="cartoon">
            <p>Already have an account?</p>
            <button id="register" onclick="login()">Go to login</button>
        </div>
        <div class="con-box right">
            <h2>Please log in<span> the event booking website</span></h2>
            <img src=" ../img/cartoon 2.png" alt="cartoon">
            <p>Don't have an account?</p>
            <button id="register" onclick="register()">Go to register</button>
        </div>
    </div>
    <script src="../js/login.js">
</script>
</body>
</html>