<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'php-assginment');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function isAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        return false;
    }

    global $conn;
    $sql = "SELECT admin_id FROM admin_member WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $_SESSION['admin_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $stmt->close();
            return true;
        }
        
        $stmt->close();
    }

    return false;
}

if (!isAdmin()) {
    die("Access denied. Admins only.");
}

$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['event_name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $event_date = $_POST['event_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $fee = $_POST['fee'];
    $event_host = $_POST['event_host'];
    $phone = $_POST['phone'];
    $note = $_POST['note'];
    $seat = $_POST['seat'];


    if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["banner_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["banner_image"]["tmp_name"]);
        if($check !== false) {

            if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                if (move_uploaded_file($_FILES["banner_image"]["tmp_name"], $target_file)) {
                    $banner_image = $target_file;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        } else {
            echo "File is not an image.";
        }
    }

    $sql = "UPDATE event SET 
            event_name = ?, description = ?, location = ?, event_date = ?,
            start_time = ?, end_time = ?, fee = ?, event_host = ?, phone = ?,
            note = ?, seat = ?" . 
            (isset($banner_image) ? ", banner_image = ?" : "") .
            " WHERE event_id = ?";

    $stmt = $conn->prepare($sql);

    if (isset($banner_image)) {
        $stmt->bind_param("ssssssssssssi", $event_name, $description, $location, $event_date,
                          $start_time, $end_time, $fee, $event_host, $phone,
                          $note, $seat, $banner_image, $event_id);
    } else {
        $stmt->bind_param("sssssssssssi", $event_name, $description, $location, $event_date,
                          $start_time, $end_time, $fee, $event_host, $phone,
                          $note, $seat, $event_id);
    }

    if ($stmt->execute()) {
  
        if (isset($_POST['things_to_bring'])) {
            $things = $_POST['things_to_bring'];
            $thing_ids = isset($_POST['thing_ids']) ? $_POST['thing_ids'] : array();
            
   
            $delete_stmt = $conn->prepare("DELETE FROM item WHERE event_id = ? AND item_id NOT IN (" . implode(',', array_fill(0, count($thing_ids), '?')) . ")");
            if ($delete_stmt) {
                $delete_params = array_merge(array($event_id), $thing_ids);
                $delete_stmt->bind_param(str_repeat('i', count($delete_params)), ...$delete_params);
                $delete_stmt->execute();
                $delete_stmt->close();
            }

            for ($i = 0; $i < count($things); $i++) {
                if (!empty($things[$i])) {
                    $logo = null;
                    $item_id = isset($thing_ids[$i]) ? $thing_ids[$i] : null;

               
                    if (isset($_FILES['things_to_bring_logo']['name'][$i]) && $_FILES['things_to_bring_logo']['error'][$i] == 0) {
                        $target_dir = "uploads/";
                        $target_file = $target_dir . uniqid() . '_' . basename($_FILES["things_to_bring_logo"]["name"][$i]);
                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                        
                    
                        $check = getimagesize($_FILES["things_to_bring_logo"]["tmp_name"][$i]);
                        if($check !== false && in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                            if (move_uploaded_file($_FILES["things_to_bring_logo"]["tmp_name"][$i], $target_file)) {
                                $logo = $target_file;
                            } else {
                                echo "Sorry, there was an error uploading your file.";
                            }
                        } else {
                            echo "File is not an image or not an allowed type.";
                        }
                    }

                    if ($item_id) {
                    
                        $sql = $logo ? 
                            "UPDATE item SET description = ?, logo = ? WHERE item_id = ? AND event_id = ?" :
                            "UPDATE item SET description = ? WHERE item_id = ? AND event_id = ?";
                        $stmt = $conn->prepare($sql);
                        if ($logo) {
                            $stmt->bind_param("ssii", $things[$i], $logo, $item_id, $event_id);
                        } else {
                            $stmt->bind_param("sii", $things[$i], $item_id, $event_id);
                        }
                    } else {
                     
                        $sql = "INSERT INTO item (event_id, description, logo) VALUES (?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("iss", $event_id, $things[$i], $logo);
                    }
                    
                    if (!$stmt->execute()) {
                        echo "Error updating/inserting item: " . $stmt->error;
                    }
                  
                }
            }
        }
        echo "Event updated successfully";
    } else {
        echo "Error updating event: " . $stmt->error;
    }

    $stmt->close();

}

$sql = "SELECT * FROM event WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();


$sql = "SELECT * FROM item WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$things_to_bring = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="../css/eventPage.css">
    <link rel="stylesheet" href="../css/edit_event.css">
    <link rel="stylesheet" href="../css/color.css">
   
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <a href="home.php" class="logo">First Fitness</a>
            <ul class="nav-menu">
                <li><a href="administratorhome.php">Home</a></li>
                <li><a href="event.php?event_id=<?php echo $event_id; ?>">Back to Event</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="edit-form">
            <h1>Edit Event: <?php echo htmlspecialchars($event['event_name']); ?></h1>
            <form action="edit_event.php?event_id=<?php echo $event_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                    <label for="event_name">Event Name:</label>
                    <input type="text" id="event_name" name="event_name" value="<?php echo htmlspecialchars($event['event_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="event_date">Event Date:</label>
                    <input type="date" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event['event_date']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="start_time">Start Time:</label>
                    <input type="time" id="start_time" name="start_time" value="<?php echo htmlspecialchars($event['start_time']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="end_time">End Time:</label>
                    <input type="time" id="end_time" name="end_time" value="<?php echo htmlspecialchars($event['end_time']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="fee">Fee:</label>
                    <input type="text" id="fee" name="fee" value="<?php echo htmlspecialchars($event['fee']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="event_host">Event Host:</label>
                    <input type="text" id="event_host" name="event_host" value="<?php echo htmlspecialchars($event['event_host']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($event['phone']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="note">Note:</label>
                    <textarea id="note" name="note"><?php echo htmlspecialchars($event['note']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="seat">Available Seats:</label>
                    <input type="number" id="seat" name="seat" value="<?php echo htmlspecialchars($event['seat']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="banner_image">Banner Image:</label>
                    <input type="file" id="banner_image" name="banner_image" onchange="previewImage(this);">
                    <?php if (!empty($event['banner_image'])): ?>
                        <p>Current image:</p>
                        <img id="image-preview" src="<?php echo htmlspecialchars($event['banner_image']); ?>" alt="Current banner image">
                    <?php else: ?>
                        <img id="image-preview" src="" alt="Image preview" style="display: none;">
                    <?php endif; ?>
                </div>

                <div class="form-group things-to-bring">
            <h2>Things to Bring</h2>
            <div id="things-to-bring-container">
                <?php foreach ($things_to_bring as $index => $thing): ?>
                    <div class="things-to-bring-item">
                        <input type="hidden" name="thing_ids[]" value="<?php echo htmlspecialchars($thing['item_id']); ?>">
                        <img src="<?php echo htmlspecialchars($thing['logo']); ?>" alt="Item logo" class="thing-logo">
                        <input type="text" name="things_to_bring[]" value="<?php echo htmlspecialchars($thing['description']); ?>" placeholder="Item description">
                        <input type="file" name="things_to_bring_logo[]" onchange="previewThingLogo(this)" accept="image/*">
                        <button type="button" class="remove-thing-btn" onclick="removeThingToBring(this)">&times;</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="add-thing-btn" onclick="addThingToBring()">Add Item</button>
        </div>

                <div class="form-group">
                    <input type="submit" value="Update Event" class="submit-btn">
                </div>
            </form>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2024 EventMaster. All rights reserved.</p>
    </footer>

    <script>
         function addThingToBring() {
        var container = document.getElementById('things-to-bring-container');
        var newItem = document.createElement('div');
        newItem.className = 'things-to-bring-item';
        newItem.innerHTML = `
            <input type="hidden" name="thing_ids[]" value="">
            <img src="" alt="Item logo" class="thing-logo">
            <input type="text" name="things_to_bring[]" placeholder="Item description">
            <input type="file" name="things_to_bring_logo[]" onchange="previewThingLogo(this)" accept="image/*">
            <button type="button" class="remove-thing-btn" onclick="removeThingToBring(this)">&times;</button>
        `;
        container.appendChild(newItem);
    }

        function removeThingToBring(button) {
            button.parentElement.remove();
        }

        function previewImage(input) {
            var preview = document.getElementById('image-preview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewThingLogo(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = input.parentElement.querySelector('img');
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>