<?php
require_once('config.php');
 
$arr_message = [];
if (isset($_POST['submit'])) {
 
    $_POST = array_map('trim', $_POST);
    extract($_POST);
 
    if (!empty($username) && !empty($email) && !empty($password)) {
         
        if ($password == $cpassword) {
            $sql = "SELECT ID FROM test_user WHERE email = '".$conn->real_escape_string($email)."'";
            $result = $conn->query($sql);
 
            if ($result->num_rows > 0) {
                $arr_message = [
                    'class' => 'alert-danger',
                    'msg' => 'Email already exist.',
                ];
            } else {
                $email = $conn->real_escape_string($email);
                $activation_key = sha1(mt_rand(10000,99999).time().$email);
 
                $sql = "INSERT INTO test_user(username, email, pwd, activation_key) VALUES('".$conn->real_escape_string($username)."', '".$email."', '".md5($conn->real_escape_string($password))."', '".$activation_key."')";
                $conn->query($sql);
                // echo $sql;
 
                //send activation link in an email
                $subject = 'Activate Your Account';
                $message = 'Hello '.ucwords($username).',<br> 
                            <p>Click the below link to activate your account.</p>
                            <a href="thegradz.com/activate.php?key='.$activation_key.'">Activate Account</a><br><br>
                            Thanks,<br>Admin';
 
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=iso-8859-1';
                mail($email, $subject, $message, implode("\r\n", $headers));
 
                $arr_message = [
                    'class' => 'alert-success',
                    'msg' => 'We have sent an activation link to your email. Please activate your account.',
                ];
                $username = $email = '';
            }
        } else {
            $arr_message = [
                'class' => 'alert-danger',
                'msg' => 'Password mismatch.',
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Gradz | Sign Up</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
        <link href="style.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="script.js"></script>
    </head>

    <body>
        <script type="text/javascript"> var logged_in = "<?php echo $logged_in; ?>";</script>
        <script type="text/javascript" src="header.js"></script>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h3>Sign Up</h3>
                    <?php if(!empty($arr_message['msg'])) { ?>
                        <div class="alert <?php echo $arr_message['class']; ?>"><?php echo $arr_message['msg']; ?></div>
                    <?php } ?>
                    <form method="post">
                        <div class="form-group">
                            <label for="exampleInputusername">Full Name</label>
                            <input type="text" class="form-control" id="exampleInputusername" name="username" placeholder="Full Name" value="<?php if(isset($username)) echo $username; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Email" value="<?php if(isset($email)) echo $email; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword2">Confirm Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword2" name="cpassword" placeholder="Confirm Password" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </body>
</html>