<?php
session_start();

require_once('config.php');
 
if (isset($_GET['action']) && ('logout' == $_GET['action'])) {
    unset($_SESSION['id']);
}
 
if (isset($_SESSION['id'])) {
    $logged_in = 'true';
    $id = $_SESSION['id'];
    
    $sql = "SELECT * FROM user WHERE ID = '".$conn->real_escape_string($id)."'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $email = $row['email'];
    $degree = $row['degree'];
    $uni = $row['uni'];
    $about = $row['about'];
    $photo = $row['photo'];

} else {
    $logged_in = 'false';
    header('Location: login_student.php');
}

if (isset($_POST['submit'])) {
 
    extract($_POST);
    require_once('uploads.php');
 
    $sql =  "UPDATE user\n";
    $sql .= "SET\n";
    $sql .= "name = '".$conn->real_escape_string($name)."',\n";
    $sql .= "email = '".$conn->real_escape_string($email)."',\n";
    $sql .= "degree = '".$conn->real_escape_string($degree)."',\n";
    $sql .= "uni = '".$conn->real_escape_string($uni)."',\n";
    if ($uploadOk) {
        $sql .= "photo = '".$conn->real_escape_string($target_file)."',\n";
    }
    $sql .= "about = '".$conn->real_escape_string($about)."'\n";
    $sql .= "WHERE ID = '".$conn->real_escape_string($id)."'";

    $result = $conn->query($sql);
}
?>
<?php
function phpAlert($msg) {
    if ($msg) {
        echo '<script type="text/javascript">alert("' . htmlspecialchars($msg) . '")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<title>My Profile | GradZ</title>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="style.css" rel="stylesheet" type="text/css">
    <link href="profile_style.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="script.js"></script>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-2021.css">
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" 
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" 
        crossorigin="anonymous"> -->
    <?php phpAlert(   $fileAlert  );  ?>

</head>

<body>
    <script type="text/javascript"> var logged_in = "<?php echo $logged_in; ?>";</script>
    <script type="text/javascript" src="header.js"></script>

    <div class = "_content">
        <div class = "_prof_section _head">
            <img src="<?php echo "img.php?id={$id}"; ?>" alt="Profile Picture" class = "pp">
            <h1><?php echo $name; ?> <div onclick="editWindow();" class = "_edit_pencil" id = "_edit_pencil">&#x1f589;</div> </h1>
            <hr>
            <table>
                <td>
                    <h2><?php echo $degree; ?></h2>
                    <h2><?php echo $uni; ?></h2>
                </td>
                <td>
                    <img alt = "University Logo" src = "img/bath.png" style = "height: 80px;">
                </td>
            </table>
        </div>
        <div class = "_prof_section _about">
            <h2>About</h2>
            <p><?php echo $about; ?></p>
        </div>
        <div class = "_prof_section _skills">
            <h2 style = "grid-row: 1">Top Skills</h2>
            <div class = "_bubble _gr1">Machine Learning    <img src = "https://www.flaticon.com/svg/static/icons/svg/566/566082.svg" alt = "icon"></div>
            <div class = "_bubble _gr1">Design              <img src = "https://www.flaticon.com/svg/static/icons/svg/681/681662.svg" alt = "icon"></div>
            <div class = "_bubble _gr1">Python              <img src = "https://www.flaticon.com/svg/static/icons/svg/1336/1336494.svg" alt = "icon"></div>
            <div class = "_bubble _gr1">CFD                 <img src = "https://www.flaticon.com/svg/static/icons/svg/1055/1055113.svg" alt = "icon"></div>
            <hr style = "grid-column: 1/5; grid-row: 3">
            <h2 style = "grid-row: 4">Top Projects</h2>
            <div class = "_bubble _gr2" onclick="clickBox('machine design')">Machine Design         <img src = "https://www.flaticon.com/svg/static/icons/svg/2099/2099058.svg" alt = "icon">
                <p>
                    Project Summary....
                </p>
            </div>
            <div class = "_bubble _gr2" onclick="clickBox('aerocapture')">Aerocapture<span class = "_gradz_project">with the gradz</span><img src = "https://www.flaticon.com/svg/static/icons/svg/2285/2285485.svg" alt = "icon">
                <p>
                    Project Summary....
                </p>
            </div>
            <div class = "_bubble _gr2" onclick="clickBox('feasibility study')">Feasibility Study<span class = "_gradz_project">with the gradz</span><img src = "https://www.flaticon.com/svg/static/icons/svg/752/752241.svg" alt = "icon">
                <p>
                    Project Summary....
                </p>
            </div>
            <div class = "_bubble _gr2" onclick="clickBox('neural net')">Neural Net                 <img src = "https://www.flaticon.com/svg/static/icons/svg/566/566082.svg" alt = "icon">
                <p>
                    Project Summary....
                </p>
            </div>
        </div>
        <div class = "_prof_section _quals">
            <h2>Qualifications</h2>
            <h5> A-Levels </h5>
            <ul>
                <li>Maths: A*</li>
                <li>Further Maths: A*</li>
                <li>Physics: A</li>
                <li>Spanish: A</li>
            </ul>
            <h5> GCSEs </h5>
            <p>11 GCSEs A*-B including Further Maths, Spanish, History and Computer Science</p>
        </div>
        <div class = "_prof_section _companies">
            <h2>Worked With</h2>
            <table>
                <td style = "width: 25%; text-align:center"><img alt = "University Logo" src = "https://www.militarysystems-tech.com/sites/militarysystems/files/styles/supplier_logo_large/public/supplier_logos/Allan_Webb_Logo_2019%20Silver%20Font.png?itok=YdYSedgt" style = "max-height: 100px"></td>
                <td style = "width: 25%; text-align:center"><img alt = "University Logo" src = "https://teignbridge.co.uk/wp-content/uploads/tp-logo-447x93.png" style = "max-height: 100px"></td>
                <td style = "width: 25%; text-align:center"><img alt = "University Logo" src = "https://media.cylex-uk.co.uk/companies/1966/1300/logo/logo.jpg" style = "max-height: 100px"></td>
                <td style = "width: 25%; text-align:center"><img alt = "University Logo" src = "http://www.spenco.co.uk/images/img_logo.jpg" style = "max-height: 100px"></td>
            </table>
    
    </div>

    <div class = "_bg" id = "bg" onclick="closeBox()"></div>
    <div class = "_project_box_unclick animate" id = "project_box"></div>

    <div id="editWindow" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
          <div class="modal-header">
            <span class="close_modal">&times;</span>
            <h2>Edit Profile</h2>
          </div>
          <div class="modal-body">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                <br>
                <label class="w3-text" style = "color: #0072B5;" for="fileToUpload">Upload new profile photo</label>
                <input type="file" name="fileToUpload" id="fileToUpload">
                <br>

                <label class="w3-text" style = "color: #0072B5;" for="inputname">Name</label>
                <input type="text" class="w3-input w3-border w3-light-grey" id="inputname" name="name" placeholder="Name" value = "<?php echo $name; ?>"/>

                <label class="w3-text" style = "color: #0072B5;" for="inputemail">Email</label>
                <input type="email" class="w3-input w3-border w3-light-grey" id="inputemail" name="email" placeholder="Email" value = "<?php echo $email; ?>"/>

                <label class="w3-text" style = "color: #0072B5; margin-top: 10px;" for="inputdegree">Degree</label>
                <input type="text" class="w3-input w3-border w3-light-grey" id="inputdegree" name="degree" placeholder="Degree" value = "<?php echo $degree; ?>"/>

                <label class="w3-text" style = "color: #0072B5; margin-top: 10px;" for="inputuni">University</label>
                <input type="text" class="w3-input w3-border w3-light-grey" id="inputuni" name="uni" placeholder="University" value = "<?php echo $uni; ?>"/>

                <label class="w3-text" style = "color: #0072B5; margin-top: 10px;" for="inputabout">About</label>
                <textarea class="w3-input w3-border w3-light-grey" id="inputabout" name="about" placeholder="About" value = "<?php echo $about; ?>"></textarea>
                <br>
                <button type="submit" name="submit" style = "margin-bottom: 1em;" class="w3-btn w3-blue-grey">Save</button>
                <br>
            </form>
          </div>
          <div class="modal-footer">
            <h3>&nbsp;</h3>
          </div>
        </div>
      
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("editWindow");
        
        // Get the button that opens the modal
        var btn = document.getElementById("_edit_pencil");
        
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close_modal")[0];
        
        // When the user clicks the button, open the modal 
        btn.onclick = function() {
          modal.style.display = "block";
        }
        
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
          modal.style.display = "none";
        }
        
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        }
        </script>

</body>
</html>