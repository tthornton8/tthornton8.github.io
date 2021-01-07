<?php
require_once('config.php');
 
if (!isset($_GET['key']) || empty($_GET['key'])) {
    header('Location:index.php');
}
 
$sql = "SELECT ID, status FROM test_user WHERE activation_key = '".$_GET['key']."'";
$result = $conn->query($sql);
 
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['status']) {
        $arr_message = [
            'class' => 'alert-success',
            'msg' => 'Your account is already activated.',
        ];
    } else {
        $sql = "UPDATE test_user SET status = '1' WHERE ID = ".$row['id']." AND activation_key = '".$_GET['key']."'";
        $conn->query($sql);
 
        $arr_message = [
            'class' => 'alert-success',
            'msg' => 'Your account is activated. You can <a href="login_student.php">login now</a>.',
        ];
    }
} else {
    $arr_message = [
        'class' => 'alert-danger',
        'msg' => 'Invalid URL.',
    ];
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <?php if(!empty($arr_message['msg'])) { ?>
                <div class="alert <?php echo $arr_message['class']; ?>">
                    <?php echo $arr_message['msg']; ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>