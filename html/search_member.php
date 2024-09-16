<?php

$conn = new mysqli('localhost', 'root', '', 'php-assignment');


if ($conn->connect_error) {
    die("connect error: " . $conn->connect_error);
}


$search = isset($_GET['search']) ? $_GET['search'] : '';

$member = array();

if ($search !== "") {

    $sql = "SELECT member_id, name, email, avatar FROM member WHERE name LIKE ? OR email LIKE ?";
    $stmt = $conn->prepare($sql);

 
    $searchParam = '%' . $search . '%';
    $stmt->bind_param("ss", $searchParam, $searchParam);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $member[] = $row;
        }
    }


    $stmt->close();
} else {

    $sql = "SELECT member_id, name, email, avatar FROM member";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $member[] = $row;
        }
    }
}


echo json_encode($member);


$conn->close();
?>
