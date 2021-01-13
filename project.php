<?php
    require_once('config.php');

    $id = $_GET['id'];

    include( "../uploads/$id.html" );
?>