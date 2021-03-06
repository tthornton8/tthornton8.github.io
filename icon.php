<?php
    require_once('config.php');

    $id = $_GET['id'];

    $sql = "SELECT icon FROM icon WHERE ID = '".$conn->real_escape_string($id)."'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $img = realpath(__DIR__ . DIRECTORY_SEPARATOR . '../icon') .'/'.$row['icon'];

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
    echo file_get_contents($img);
?>