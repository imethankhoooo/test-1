<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>

    </style>
</head>
<body>
    <!-- ... (keep existing body content) ... -->
    <div id="loadingOverlay">
        <div class="spinner"></div>
    </div>
    <div class="Page adminPage5">
        <h1>Create Special Event</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <div class="image-upload">
                <label for="eventImgInput">Event Image:</label>
                <div class="image-preview" id="eventImagePreview">
                    <img src="../Img/Screenshot 2024-06-08 133929.png" alt="Add Image" id="eventPreviewImg">
                </div>
                <input type="file" id="eventImgInput" name="eventImgInput" accept="image/*" style="display: none;">
            </div>

            <div class="form-group">
                <label for="title">Event Title:</label>
                <input type="text" id="title" name="title" required placeholder="Enter event title">
            </div>

            <div class="form-group">
                <label for="content">Event Description:</label>
                <textarea id="content" name="content" rows="4" required placeholder="Describe your event"></textarea>
            </div>

            <div class="form-group">
                <label for="Date">Event Date:</label>
                <input type="date" id="Date" name="Date" required>
            </div>

            <div class="form-group">
                <label for="StartTime">Start Time:</label>
                <input type="time" id="StartTime" name="StartTime" required>
            </div>

            <div class="form-group">
                <label for="EndTime">End Time:</label>
                <input type="time" id="EndTime" name="EndTime" required>
            </div>

            <div class="form-group">
                <label for="Fee">Event Fee:</label>
                <input type="number" id="Fee" name="Fee" step="0.01" required placeholder="Enter fee amount">
            </div>

            <div class="form-group">
                <label for="Host">Event Host:</label>
                <input type="text" id="Host" name="Host" required placeholder="Enter host name">
            </div>

            <div class="form-group">
                <label for="Phone">Host Phone Number:</label>
                <input type="tel" id="Phone" name="Phone" required placeholder="Enter phone number">
            </div>

            <div class="form-group">
                <label for="Venue">Event Venue:</label>
                <textarea id="Venue" name="Venue" rows="3" required placeholder="Enter venue details"></textarea>
            </div>

            <div class="form-group">
                <label for="Seat">Available Seats:</label>
                <input type="number" id="Seat" name="Seat" required placeholder="Enter number of seats">
            </div>

            <div class="form-group">
                <label for="Precautions">Event Precautions:</label>
                <textarea id="Precautions" name="Precautions" rows="3" required placeholder="Enter any precautions or guidelines"></textarea>
            </div>

            <h2>Things to Bring</h2>
            <div class="things-to-bring" id="thingsContainer">
                <div class="thing-item">
                    <div class="thing-image" onclick="triggerFileInput('file-input0')">
                        <img src="../Img/Screenshot 2024-06-08 133929.png" alt="Add Image" id="previewImg0">
                    </div>
                    <input type="file" id="file-input0" name="file-input[]" accept="image/*" style="display: none;">
                    <input type="text" name="thingContent[]" placeholder="Item name">
                </div>
            </div>
            <button type="button" onclick="addThingItem()">Add Another Item</button>

            <div class="button-container">
                <button type="submit" name="submit" class="submit-btn">Create Event</button>
                <button type="reset" name="reset" class="reset-btn">Reset Form</button>
            </div>
        </form>
    </div>

    <script>
        // ... (keep existing JavaScript) ...

        function addThingItem() {
            const container = document.getElementById('thingsContainer');
            const newItem = document.createElement('div');
            newItem.className = 'thing-item';
            const itemIndex = container.children.length;
            newItem.innerHTML = `
                <div class="thing-image" onclick="triggerFileInput('file-input${itemIndex}')">
                    <img src="../Img/Screenshot 2024-06-08 133929.png" alt="Add Image" id="previewImg${itemIndex}">
                </div>
                <input type="file" id="file-input${itemIndex}" name="file-input[]" accept="image/*" style="display: none;">
                <input type="text" name="thingContent[]" placeholder="Item name">
            `;
            container.appendChild(newItem);
        }

        document.getElementById('eventImagePreview').addEventListener('click', function() {
            document.getElementById('eventImgInput').click();
        });

        document.getElementById('eventImgInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('eventPreviewImg').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        function triggerFileInput(inputId) {
            document.getElementById(inputId).click();
        }

        document.addEventListener('change', function(event) {
            if (event.target.type === 'file' && event.target.name === 'file-input[]') {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgId = event.target.id.replace('file-input', 'previewImg');
                        document.getElementById(imgId).src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            }
        });
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('visible');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('visible');
        }

        function validateForm() {
            let isValid = true;
            const fields = ['title', 'content', 'Date', 'StartTime', 'EndTime', 'Fee', 'Host', 'Phone', 'Venue', 'Seat', 'Precautions', 'EventType'];
            
            fields.forEach(field => {
                const input = document.getElementById(field);
                const errorElement = document.getElementById(`${field.toLowerCase()}Error`);
                if (!input.value) {
                    isValid = false;
                    errorElement.textContent = `${field.replace(/([A-Z])/g, ' $1').trim()} is required`;
                } else {
                    errorElement.textContent = '';
                }
            });

            return isValid;
        }

        document.getElementById('eventForm').addEventListener('submit', function(event) {
            event.preventDefault();
            if (validateForm()) {
                showLoading();
                // 在这里可以添加 AJAX 请求来提交表单
                setTimeout(() => {
                    hideLoading();
                    alert('Event created successfully!');
                    this.reset();
                }, 2000); // 模拟 2 秒的提交过程
            }
        });

        // 添加滚动时的渐入效果
        function handleScroll() {
            const formGroups = document.querySelectorAll('.form-group');
            formGroups.forEach(group => {
                const rect = group.getBoundingClientRect();
                if (rect.top <= window.innerHeight * 0.75) {
                    group.classList.add('visible');
                }
            });
        }

        window.addEventListener('scroll', handleScroll);
        handleScroll(); 
    </script>
</body>
</html>