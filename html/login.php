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
<video autoplay muted loop id="video-background">
    <source src="../Img/Login Video.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>
<script>
        function showCustomAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.textContent = message;
            alertDiv.className = `custom-alert ${type}`;
            document.body.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.classList.add('show');
            }, 10);

            setTimeout(() => {
                alertDiv.classList.remove('show');
                setTimeout(() => {
                    alertDiv.remove();
                }, 300);
            }, 5000);
        }

        function switchForm() {
            const container = document.querySelector('.container');
            container.classList.toggle('flip');
        }
    </script>
<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'php-assignment');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function showAlert($message, $type = 'info') {
    echo "<script>window.showCustomAlert('$message', '$type');</script>";
}


function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password) {
    // At least 8 characters long and contain at least one number, one uppercase and one lowercase letter
    return preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['registerEmail']) && isset($_POST['registerPassword']) && isset($_POST['userpassword'])) {
        $email = trim($_POST['registerEmail']);
        $password = trim($_POST['registerPassword']);
        $confirmPassword = trim($_POST['userpassword']);
        
        if (empty($email) || empty($password) || empty($confirmPassword)) {
            showAlert("All fields are required.", 'error');
        } elseif (!validateEmail($email)) {
            showAlert("Please enter a valid email address.", 'error');
        } elseif (!validatePassword($password)) {
            showAlert("Password must be at least 8 characters long and contain at least one number, one uppercase and one lowercase letter.", 'error');
        } elseif ($password !== $confirmPassword) {
            showAlert("The passwords do not match.", 'error');
        } else {
            $stmt = $conn->prepare("SELECT member_id FROM member WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                showAlert("This email address is already registered.", 'error');
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO member (email, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $email, $hashedPassword);
                
                if ($stmt->execute()) {
                    $member_id = $conn->insert_id;
                    showAlert("Registration successful! You can now log in.", 'success');
                    $_SESSION['member_id'] = $member_id;
                    echo "<script>setTimeout(function() { window.location.href = 'memberInformation.php'; }, 2000);</script>";
                } else {
                    showAlert("Registration failed: " . $conn->error, 'error');
                }
            }
            $stmt->close();
        }
    } else if (isset($_POST['email']) && isset($_POST['loginPassword'])) {
        $email = trim($_POST['email']);
        $password = trim($_POST['loginPassword']);
        
        if (empty($email) || empty($password)) {
            showAlert("Email and password are required.", 'error');
        } elseif (!validateEmail($email)) {
            showAlert("Please enter a valid email address.", 'error');
        } else {
            $stmt = $conn->prepare("SELECT member_id, password FROM member WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($password=== $row['password']) {
                    $_SESSION['member_id'] = $row['member_id'];
                    $_SESSION['email'] = $email;
                    showAlert("Login successful!", 'success');
                    echo "<script>setTimeout(function() { window.location.href = 'home.php'; }, 2000);</script>";
                } else {
                    showAlert("Incorrect password.", 'error');
                }
            } else {
                $stmt = $conn->prepare("SELECT admin_id, password FROM admin WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $adminResult = $stmt->get_result();
                
                if ($adminResult->num_rows > 0) {
                    $adminRow = $adminResult->fetch_assoc();
                    if ($password===$adminRow['password']) {
                        $_SESSION['admin_id'] = $adminRow['admin_id'];
                        $_SESSION['email'] = $email;
                        showAlert("Administrator login successful!", 'success');
                        echo "<script>setTimeout(function() { window.location.href = 'administratorhome.php'; }, 2000);</script>";
                    } else {
                        showAlert("Incorrect password.", 'error');
                    }
                } else {
                    showAlert("User not found. Please register or check your input.", 'error');
                }
            }
            $stmt->close();
        }
    } else {
        showAlert("Invalid form submission.", 'error');
    }
}

$conn->close();
?>
<style>
        
    </style>
    
    <div class="container">
        <div class="form-box login-box">
            
            <h2>Login Now</h2>
            <form action="login.php" method="post">
                <div class="input-group">
                    <input type="text" name="email" required>
                    <label>Email</label>
                </div>
                <div class="input-group">
                    <input type="password" name="loginPassword" required>
                    <label>Password</label>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <p class="switch-btn" onclick="switchForm()">Don't have an account?</p>
        </div>
        <div class="form-box register-box">
            <h2>Register</h2>
            <form action="login.php" method="post">
                <div class="input-group">
                    <input type="text" name="registerEmail" required>
                    <label>Email</label>
                </div>
                <div class="input-group">
                    <input type="password" name="registerPassword" required>
                    <label>Password</label>
                </div>
                <div class="input-group">
                    <input type="password" name="userpassword" required>
                    <label>Comfirm Password</label>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
            <p class="switch-btn" onclick="switchForm()">Already have an account?</p>
        </div>
    </div>
    
    <script src="../js/login.js">
        
</script>
</body>
</html>