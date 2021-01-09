<?php
    require_once('config.php');

    $id = $_GET['id'];

    $sql = "SELECT photo FROM user WHERE ID = '".$conn->real_escape_string($id)."'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $img = $row['photo'];
    
    $mimes = array
    (
        'jpg' => 'image/jpg',
        'jpeg' => 'image/jpg',
        'gif' => 'image/gif',
        'png' => 'image/png'
    );

    $ext = strtolower(end(explode('.', $img)));

    header('content-type: '. $mimes[$ext]);
    header('content-disposition: inline; filename="'.$img.'";');
    readfile($file);
?>