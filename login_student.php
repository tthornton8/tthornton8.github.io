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
        $sql = "SELECT ID, status FROM user WHERE username = '".$conn->real_escape_string($username)."' AND pwd = '".md5($conn->real_escape_string($password))."'";
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
            $error_message = "Incorrect username or password.";
        }
    } else {
        $error_message = 'Please enter username and password.';
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login | GradCherry</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
        <link href="style.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-2021.css">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
        <script type="text/javascript" src="script.js"></script>
        <link href="responsive.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <script type="text/javascript"> var logged_in = "<?php echo $logged_in; ?>";</script>
        <script type="text/javascript" src="header.js"></script>
        <script type="text/javascript" src="script.js"></script>

        <div class="_content _top_first_el" style = "margin: 0 auto; width: 50%; margin-top: 3rem;">
            <div class="w3-card-4" style = "background-color: white">
                <div class="w3-container _dark_cherry">
                    <h2>Please Login</h2>
                </div>

                <div class="w3-container">

                    <?php if(!empty($error_message)) { ?>
                        <div class="alert alert-danger"  style = "margin-top: 2em;"><?php echo $error_message; ?></div>
                    <?php } ?>

                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <br>
                        <label class="w3-text" style = "color: var(--darkCherry);" for="inputusername">Username</label>
                        <input type="text" class="w3-input w3-border w3-light-grey" id="inputusername" name="username" placeholder="Username" required />

                        <label class="w3-text" style = "color: var(--darkCherry);" for="inputpassword">Password</label>
                        <input type="password" class="w3-input w3-border w3-light-grey" id="inputpassword" name="password" placeholder="Password" required />
                        <br>
                        <button type="submit" name="submit" style = "margin-bottom: 1em;" class="w3-btn w3-blue-grey">Login</button>
                        <br>
                    </form>
                </div>
            </div>
        </div>


    </body>
</html>
