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

$projects = [];
$sql = "SELECT * FROM project WHERE user_ID = ".$conn->real_escape_string($id);
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
    
}

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