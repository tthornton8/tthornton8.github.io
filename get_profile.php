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

function get_profile_vars($conn, $id) {
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


    $projects = get_from_table("project", $conn, $id);
    $qual = get_from_table("qual", $conn, $id);
    $icons = get_from_table("icon", $conn);
    $usercompanies = get_from_table("usercompanies", $conn, $id);
    $tolearn = get_from_table("user_to_learn", $conn, $id);
    $interested = get_from_table("user_interested", $conn, $id);

    return array($name, $email, $degree, $uni, $about, $photo, $skills, $projects, $qual, $icons, $usercompanies, $tolearn, $interested);
}
?>