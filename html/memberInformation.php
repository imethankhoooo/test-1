<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Up Your Profile</title>
    <link rel="stylesheet" href="../css/memberInformation.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
</head>
<body>
<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'php-assginment');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['member_id'])) {
    $member_id = $_SESSION['member_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $membername = $_POST['membername'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $bio = $_POST['bio'];
        $experience = $_POST['experience'];
        $socialmedia = $_POST['socialmedia'];

        // Handle avatar upload
        if(isset($_FILES['avatar'])){
            $file_tmp = $_FILES['avatar']['tmp_name'];
            $file_ext = strtolower(end(explode('.',$_FILES['avatar']['name'])));
        
                $upload_dir = '../uploads/';
                $avatar_name = uniqid() . '.' . $file_ext;
                move_uploaded_file($file_tmp, $upload_dir . $avatar_name);
                $avatar_path = '/uploads/' . $avatar_name; // Store relative path in database
        }

        $stmt = $conn->prepare("UPDATE member SET name=?, username=?, gender=?, phone=?, address=?, bio=?, experience=?, socialmedia=?, avatar=? WHERE member_id=?");
        $stmt->bind_param("sssssssssi", $name, $membername, $gender, $phone, $address, $bio, $experience, $socialmedia, $avatar_path, $member_id);

        if ($stmt->execute()) {
            echo "<script>alert('Profile updated successfully'); window.location.href='home.html';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } 
    
} else {
    echo "Member ID not received or invalid.";
}

$conn->close();
?>
    <div class="profileInput-container container1">
        <img src="../Img/00002-3114174374.png" alt="Background Image">
    </div>
    <div class="profileInput-container container2">
        <div class="form-container">
            <form id="profileForm" action="memberInformation.php" method="POST" enctype="multipart/form-data">
                <h1>Set Up Your Profile</h1>
                
                <div class="avatar-upload">
                    <div class="avatar-edit">
                        <input type='file' id="avatarInput" name="avatar" accept=".png, .jpg, .jpeg" />
                        <label for="avatarInput"></label>
                    </div>
                    <div class="avatar-preview">
                        <div id="avatarPreview" style="background-image: url('../Img/cartoon 1');">
                        </div>
                    </div>
                </div>


                <fieldset>
                    <legend>Basic Information</legend>
                    <div class="search-container">
                        <input name="name" type="text" class="search-input" placeholder=" " required>
                        <span class="line"></span>
                        <label class="search-placeholder">Name</label>
                    </div>
                    <div class="search-container">
                        <input name="membername" type="text" class="search-input" placeholder=" " required>
                        <span class="line"></span>
                        <label class="search-placeholder">Username</label>
                    </div>
                    
                    <div class="gender-container">
                        <div class="gender-option">
                            <input type="radio" id="male" name="gender" value="male" required>
                            <label for="male">Male</label>
                        </div>
                        <div class="gender-option">
                            <input type="radio" id="female" name="gender" value="female" required>
                            <label for="female">Female</label>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Other Information</legend>
                    <div class="search-container">
                        <input name="phone" type="tel" class="search-input" placeholder=" ">
                        <span class="line"></span>
                        <label class="search-placeholder">Phone</label>
                    </div>
                    <div class="search-container">
                        <input name="address" type="text" class="search-input" placeholder=" ">
                        <span class="line"></span>
                        <label class="search-placeholder">Address</label>
                    </div>
                    <div class="search-container">
                        <input name="bio" type="text" class="search-input" placeholder=" ">
                        <span class="line"></span>
                        <label class="search-placeholder">Bio</label>
                    </div>
                    <div class="search-container">
                        <input name="socialmedia" type="text" class="search-input" placeholder=" ">
                        <span class="line"></span>
                        <label class="search-placeholder">Social Media</label>
                    </div>
                    <div class="search-container">
                        <input name="experience" type="text" class="search-input" placeholder=" ">
                        <span class="line"></span>
                        <label class="search-placeholder">Experience</label>
                    </div>
                </fieldset>
                <button type="submit">Submit Profile</button>
            </form>
        </div>
    </div>

    <script>
        function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').style.backgroundImage = "url('" + e.target.result + "')";
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById("avatarInput").addEventListener("change", function() {
        readURL(this);
    });
    </script>
</body>
</html>