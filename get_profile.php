<?php

function get_from_table ($table, $conn, $user_id="") {
    $out = [];
    if ($user_id) {
        $sql = "SELECT * FROM $table WHERE user_ID = ".$conn->real_escape_string($user_id);
    } else {
        $sql = "SELECT * FROM $table";
    }
    
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $out[] = $row;
        }
    }
    return $out;
}

$sql = "SELECT * FROM user WHERE ID = '".$conn->real_escape_string($id)."'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$name = $row['name'];
$email = $row['email'];
$degree = $row['degree'];
$uni = $row['uni'];
$about = $row['about'];
$photo = $row['photo'];

$skills = [];
$sql = "SELECT * FROM skill WHERE user_ID = ".$conn->real_escape_string($id);
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $skills[] = array (
            "name" => $row["name"],
            "icon" => $row["icon"],
        );
    }
}

// $projects = [];
// $sql = "SELECT * FROM project WHERE user_ID = ".$conn->real_escape_string($id);
// $result = $conn->query($sql);
// if ($result) {
//     while ($row = $result->fetch_assoc()) {
//         $projects[] = $row;
//     }
// }
$projects = get_from_table("project", $conn, $id);

$qual = [];
$sql = "SELECT * FROM qual WHERE user_ID = ".$conn->real_escape_string($id);
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $qual[] = $row;
    }
}

$icons = [];
$sql = "SELECT * FROM icon";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $icons[] = $row;
    }
}

$usercompanies = [];
$sql = "SELECT * FROM usercompanies";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $usercompanies[] = $row;
    }
}
?>