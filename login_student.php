<?php
session_start();
  
require_once('config.php');
  
//if user is logged in redirect to myaccount page
if (isset($_SESSION['id'])) {
    header('Location: profile.html');
}
  
$error_message = '';
if (isset($_POST['submit'])) {
 
    extract($_POST);
 
    if (!empty($username) && !empty($password)) {
        $sql = "SELECT ID, status FROM users WHERE username = '".$conn->real_escape_string($username)."';
        $result = $conn->query($sql);
  
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if($row['status']) {
                $_SESSION['id'] = $row['ID'];
                header('Location: profile.html');
            } else {
                $error_message = 'Your account is not active yet.';
            }
        } else {
            $error_message = 'Incorrect email or password.';
        }
    } else {
        $error_message = 'Please enter email and password.';
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login Form</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h3>Login Form</h3>
                    <?php if(!empty($error_message)) { ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php } ?>
                    <form method="post">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Username</label>
                            <input type="text" class="form-control" id="exampleInputEmail1" name="username" placeholder="Username" required />
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password" required />
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
