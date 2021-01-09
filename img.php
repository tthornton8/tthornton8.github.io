<?php
    $img = $_GET['img'];
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