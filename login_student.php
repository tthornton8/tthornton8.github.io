<?php
session_start();
  
require_once('config.php');
  
//if user is logged in redirect to myaccount page
if (isset($_SESSION['id'])) {
    header('Location: profile.php');
}
  
$error_message = '';
if (isset($_POST['submit'])) {
 
    extract($_POST);
 
    if (!empty($username) && !empty($password)) {
        $sql = "SELECT ID, status FROM test_user WHERE username = '".$conn->real_escape_string($username)."' AND pwd = '".md5($conn->real_escape_string($password))."'";
        $result = $conn->query($sql);
  
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if($row['status']) {
                $_SESSION['id'] = $row['ID'];
                header('Location: profile.php');
            } else {
                $error_message = 'Your account is not active yet.';
            }
        } else {
            $error_message = "Incorrect email or password.";
        }
    } else {
        $error_message = 'Please enter email and password.';
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login | GradZ</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
        <link href="style.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="script.js"></script>
    </head>

    <script type="text/javascript"> var logged_in = "<?php echo $logged_in; ?>";</script>
    <script type="text/javascript" src="header.js"></script>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <?php if(!empty($error_message)) { ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php } ?>
                    <form method="post">
                        <div class="form-group">
                            <label for="inputusername">Username</label>
                            <input type="text" class="form-control" id="inputusername" name="username" placeholder="Username" required />
                        </div>
                        <div class="form-group">
                            <label for="inputpassword">Password</label>
                            <input type="password" class="form-control" id="inputpassword" name="password" placeholder="Password" required />
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
