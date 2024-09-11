<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Up Your Profile</title>
    <link rel="stylesheet" href="../css/memberInformation.css">

</head>
<body>
    <div class="page-container">
        <div class="form-container">
            <div class="form-header">
                <h1>Set Up Your Profile</h1>
                <div class="progress-bar">
                    <div class="progress"></div>
                </div>
            </div>
            <form id="profileForm" action="memberInformation.php" method="POST" enctype="multipart/form-data">
                <div class="form-section" id="section1">
                    <h2>Basic Information</h2>
                    <div class="avatar-upload">
                        <div class="avatar-edit">
                            <input type='file' id="avatarInput" name="avatar" accept=".png, .jpg, .jpeg" />
                            <label for="avatarInput"></label>
                        </div>
                        <div class="avatar-preview">
                            <div id="avatarPreview" style="background-image: url('../Img/cartoon 1.png');"></div>
                        </div>
                    </div>
                    <div class="input-group">
                        <input name="name" type="text" required>
                        <label>Name</label>
                    </div>
                    <div class="input-group">
                        <input name="membername" type="text" required>
                        <label>Username</label>
                    </div>
                    <div class="gender-group">
                        <label>Gender</label>
                        <div class="gender-options">
                            <input type="radio" id="male" name="gender" value="male" required>
                            <label for="male">Male</label>
                            <input type="radio" id="female" name="gender" value="female" required>
                            <label for="female">Female</label>
                        </div>
                    </div>
                    <button type="button" class="next-btn" onclick="nextSection(1)">Next</button>
                </div>
                
                <div class="form-section" id="section2" style="display:none;">
                    <h2>Contact Information</h2>
                    <div class="input-group">
                        <input name="phone" type="tel">
                        <label>Phone</label>
                    </div>
                    <div class="input-group">
                        <input name="address" type="text">
                        <label>Address</label>
                    </div>
                    <div class="input-group">
                        <input name="socialmedia" type="text">
                        <label>Social Media</label>
                    </div>
                    <button type="button" class="prev-btn" onclick="prevSection(2)">Previous</button>
                    <button type="button" class="next-btn" onclick="nextSection(2)">Next</button>
                </div>
                
                <div class="form-section" id="section3" style="display:none;">
                    <h2>About You</h2>
                    <div class="input-group">
                        <textarea name="bio" rows="4"></textarea>
                        <label>Bio</label>
                    </div>
                    <div class="input-group">
                        <textarea name="experience" rows="4"></textarea>
                        <label>Experience</label>
                    </div>
                    <button type="button" class="prev-btn" onclick="prevSection(3)">Previous</button>
                    <button type="submit">Submit Profile</button>
                </div>
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

        function nextSection(currentSection) {
            document.getElementById('section'+currentSection).style.display = 'none';
            document.getElementById('section'+(currentSection+1)).style.display = 'block';
            updateProgressBar(currentSection+1);
        }

        function prevSection(currentSection) {
            document.getElementById('section'+currentSection).style.display = 'none';
            document.getElementById('section'+(currentSection-1)).style.display = 'block';
            updateProgressBar(currentSection-1);
        }

        function updateProgressBar(section) {
            var progress = (section / 3) * 100;
            document.querySelector('.progress').style.width = progress + '%';
        }
    </script>
</body>
</html>