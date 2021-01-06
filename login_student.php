<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

button {
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  opacity: 0.8;
}

.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
}

img.avatar {
  width: 40%;
  border-radius: 50%;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>

<?php
$servername = "localhost";
$username = "root";
$password = "x3ydNbLgiRZduK";

try {
  $conn = new PDO("mysql:host=$servername;dbname=mysql", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>

<?php
// define variables and set to empty values
$usn = $pwd = "";
$usnErr = $pwdErr = "";

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["usn"])) {
    $usnErr = "Username required";
  } else {
    $usn = test_input($_POST["usn"]);
  }

  if (empty($_POST["pwd"])) {
    $pwdErr = "Password required";
  } else {
    $pwd = test_input($_POST["pwd"]);
  }

  if ($usn != "" && $pwd != "") {
    $sql = "SELECT * FROM test_user";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetc_assoc()) {
        echo "<br> Name: " . $row["pwd"]. "<br>";
      }
    } else {
      echo "<br> username not found";
    }
    $conn->close();
  }
}
?>

</head>
<body>

<h2>Login Form</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <div class="imgcontainer">
    <img src="img_avatar2.png" alt="Avatar" class="avatar">
  </div>

  <div class="container">
    <label for="usn"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="usn" value="<?php echo $usn;?>" required>
    <span class="error">* <?php echo $usnErr;?></span>

    <label for="pwd"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="pwd" value="<?php echo $pwd;?>" required>
    <span class="error">* <?php echo $pwdErr;?></span>
        
    <button type="submit">Login</button>
    <label>
      <input type="checkbox" checked="checked" name="remember"> Remember me
    </label>
  </div>

  <div class="container" style="background-color:#f1f1f1">
    <button type="button" class="cancelbtn">Cancel</button>
    <span class="psw">Forgot <a href="#">password?</a></span>
  </div>
</form>

</body>
</html>
