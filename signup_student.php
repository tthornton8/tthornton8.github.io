<?php
require_once('config.php');
 
$arr_message = [];
if (isset($_POST['submit'])) {
 
    $_POST = array_map('trim', $_POST);
    extract($_POST);
 
    if (!empty($username) && !empty($email) && !empty($password)) {
         
        if ($password == $cpassword) {
            $sql = "SELECT ID FROM user WHERE email = '".$conn->real_escape_string($email)."'";
            $result = $conn->query($sql);
 
            if ($result->num_rows > 0) {
                $arr_message = [
                    'class' => 'alert-danger',
                    'msg' => 'Email already exist.',
                ];
            } else {
                $email = $conn->real_escape_string($email);
                $activation_key = sha1(mt_rand(10000,99999).time().$email);
 
                $sql = "INSERT INTO user(username, email, pwd, activation_key) VALUES('".$conn->real_escape_string($username)."', '".$email."', '".md5($conn->real_escape_string($password))."', '".$activation_key."')";
                $conn->query($sql);
                
                // echo $sql;
 
                //send activation link in an email
                $subject = 'Activate Your Account';
                $message = 'Hello '.ucwords($username).',<br> 
                            <p>Click the below link to activate your account.</p>
                            <a href="gradcherry.com/activate.php?key='.$activation_key.'">Activate Account</a><br><br>
                            Thanks,<br>Admin';
 
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=iso-8859-1';
                mail($email, $subject, $message, implode("\r\n", $headers));
 
                $arr_message = [
                    'class' => 'alert-success',
                    'msg' => 'We are currently in BETA testing, please wait for a confirmation email for your account to be confirmed.',
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
        <title>Sign up | GradCherry</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
        <link href="style.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
        <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-2021.css">
        <link href="responsive.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="script.js"></script>
    </head>

    <body>
        <script type="text/javascript"> var logged_in = "<?php echo $logged_in; ?>";</script>
        <script type="text/javascript" src="header.js"></script>
        <script type="text/javascript" src="script.js"></script>

        <div class="_content _top_first_el" style = "margin: 0 auto; width: 50%; margin-top: 3rem;">
            <div class="w3-card-4">
                <div class="w3-container _dark_cherry">
                    <h2>Sign Up</h2>
                </div>

                <div class="w3-container">

                    <?php if(!empty($arr_message['msg'])) { ?>
                        <div class="alert <?php echo $arr_message['class']; ?>" style = "margin-top: 2em;"><?php echo $arr_message['msg']; ?></div>
                    <?php } ?>

                    <form method="post"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <br>
                        <label class="w3-text" style = "color: var(--darkCherry);" for="exampleInputusername">Username</label>
                        <input type="text" class="w3-input w3-border w3-light-grey" id="exampleInputusername" name="username" placeholder="Full Name" value="<?php if(isset($username)) echo $username; ?>" required>

                        <label class="w3-text" style = "color: var(--darkCherry);" for="exampleInputEmail1">Email address</label>
                        <input type="email" class="w3-input w3-border w3-light-grey" id="exampleInputEmail1" name="email" placeholder="Email" value="<?php if(isset($email)) echo $email; ?>" required>

                        <label class="w3-text" style = "color: var(--darkCherry);" for="exampleInputPassword1">Password</label>
                        <input type="password" class="w3-input w3-border w3-light-grey" id="exampleInputPassword1" name="password" placeholder="Password" required>

                        <label class="w3-text" style = "color: var(--darkCherry);" for="exampleInputPassword2">Confirm Password</label>
                        <input type="password" class="w3-input w3-border w3-light-grey" id="exampleInputPassword2" name="cpassword" placeholder="Confirm Password" required>
                        <br>
                        <button type="submit" name="submit" class="w3-btn w3-blue-grey" style = "margin-bottom: 1em;">Register</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    </body>
</html>