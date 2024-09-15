<?php
session_start();
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'php-assginment');
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

$member_id = $_SESSION['member_id'];
$stmt = $conn->prepare("SELECT * FROM member WHERE member_id = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();

$messages = ['success' => '', 'error' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update profile information
    $fields = ['name', 'username', 'gender', 'email', 'phone', 'address', 'bio', 'experience', 'socialmedia'];
    $params = array_map(fn($field) => $_POST[$field], $fields);
    $params[] = $member_id;

    $update_sql = "UPDATE member SET " . implode(" = ?, ", $fields) . " = ? WHERE member_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param(str_repeat('s', count($fields)) . 'i', ...$params);

    if ($update_stmt->execute()) {
        $messages['success'] .= "Profile updated successfully! ";
    } else {
        $messages['error'] .= "Failed to update profile. Please try again later. ";
    }

    // Update password
    if (!empty($_POST['old_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        $stmt = $conn->prepare("SELECT password FROM member WHERE member_id = ?");
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $result= $stmt->get_result();
        $members=$result->fetch_assoc();

        if ($old_password=== $members['password']) {
            if ($new_password === $confirm_password) {
                $stmt = $conn->prepare("UPDATE member SET password = ? WHERE member_id = ?");
                $stmt->bind_param("si", $new_password, $member_id);
                
                if ($stmt->execute()) {
                    $messages['success'] .= "Password updated successfully. ";
                } else {
                    $messages['error'] .= "Failed to update password. Please try again later. ";
                }
            } else {
                $messages['error'] .= "New password and confirmation do not match. ";
            }
        } else {
           
            $messages['error'] .= "Incorrect old password. ";
        }
    }

    // Update avatar
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['avatar']['tmp_name'];
        $file_ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));

        if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $upload_dir = './uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $avatar_name = uniqid() . '.' . $file_ext;
            $avatar_path = $upload_dir . $avatar_name;

            if (move_uploaded_file($file_tmp, $avatar_path)) {
                $stmt = $conn->prepare("UPDATE member SET avatar = ? WHERE member_id = ?");
                $stmt->bind_param("si", $avatar_path, $member_id);
                
                if ($stmt->execute()) {
                    $messages['success'] .= "Avatar updated successfully. ";
                } else {
                    $messages['error'] .= "Failed to update avatar. Please try again later. ";
                }
            } else {
                $messages['error'] .= "Failed to upload avatar. ";
            }
        } else {
            $messages['error'] .= "Unsupported file format. Please upload JPG, JPEG, PNG, or GIF files. ";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/edit_memberInformation.css">
    <title>Update Profile</title>
</head>
<body>
<header class="header">
  <div class="header-content">
    <div class="left-content">
      <a href="home.php" class="logo">First Fitness</a>
    </div>
    <a href="javascript:history.back()" class="back-btn">Go Back</a>
  </div>
</header>
    <div class="container">
        <h1>Update Profile</h1>
        
        <?php if (!empty($messages['success'])): ?>
            <div class="message success"><?php echo $messages['success']; ?></div>
        <?php endif; ?>
        <?php if (!empty($messages['error'])): ?>
            <div class="message error"><?php echo $messages['error']; ?></div>
        <?php endif; ?>

        <form id="updateForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div class="section avatar-section">
                <h2>Profile Picture</h2>
                <img id="avatar-preview" class="avatar-preview" src="<?php echo !empty($member['avatar']) ? $member['avatar'] : '../Img/cartoon 1.png'; ?>" alt="Avatar Preview">
                <input type="file" id="avatar-upload" name="avatar" accept="image/*">
                <label for="avatar-upload" class="avatar-upload-label">Choose New Avatar</label>
            </div>

            <div class="section">
                <h2>Personal Information</h2>
                <div class="form-grid">
                    <?php
                    $fields = [
                        'name' => 'Name',
                        'username' => 'Username',
                        'gender' => 'Gender',
                        'email' => 'Email',
                        'phone' => 'Phone',
                        'address' => 'Address',
                        'bio' => 'Bio',
                        'experience' => 'Work Experience',
                        'socialmedia' => 'Social Media'
                    ];
                    foreach ($fields as $field => $label):
                        if ($field === 'gender'): ?>
                            <div class="form-group">
                                <label for="<?php echo $field; ?>"><?php echo $label; ?>:</label>
                                <select id="<?php echo $field; ?>" name="<?php echo $field; ?>">
                                    <option value="">Select Gender</option>
                                    <option value="Male" <?php if ($member[$field] == 'Male') echo 'selected'; ?>>Male</option>
                                    <option value="Female" <?php if ($member[$field] == 'Female') echo 'selected'; ?>>Female</option>
                                </select>
                            </div>
                        <?php else: ?>
                            <div class="form-group">
                                <label for="<?php echo $field; ?>"><?php echo $label; ?>:</label>
                                <input type="<?php echo ($field === 'email') ? 'email' : 'text'; ?>" 
                                       id="<?php echo $field; ?>" 
                                       name="<?php echo $field; ?>" 
                                       value="<?php echo htmlspecialchars($member[$field]); ?>"
                                       <?php echo (in_array($field, ['name', 'username', 'email'])) ? 'required' : ''; ?>>
                            </div>
                        <?php endif;
                    endforeach; ?>
                </div>
            </div>

            <div class="section">
                <h2>Change Password</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="old_password">Old Password:</label>
                        <input type="password" id="old_password" name="old_password">
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                    </div>
                </div>
            </div>

            <input type="submit" value="Update Profile" class="btn">
        </form>
    </div>

    <script>
        document.getElementById('avatar-upload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            if (file) {
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('updateForm').addEventListener('submit', function(event) {
            const requiredFields = ['name', 'username', 'email'];
            let isValid = true;

            requiredFields.forEach(field => {
                const element = document.getElementById(field);
                if (element.value.trim() === '') {
                    isValid = false;
                    element.style.borderColor = 'var(--error-color)';
                } else {
                    element.style.borderColor = '';
                }
            });

            if (!isValid) {
                event.preventDefault();
                alert('Please fill in all required fields (Name, Username, and Email).');
            }

            const oldPassword = document.getElementById('old_password').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (oldPassword || newPassword || confirmPassword) {
                if (!(oldPassword && newPassword && confirmPassword)) {
                    event.preventDefault();
                    alert('To change your password, please fill in all password fields.');
                } else if (newPassword !== confirmPassword) {
                    event.preventDefault();
                    alert('New password and confirmation do not match.');
                }
            }
        });
    </script>
</body>
</html>