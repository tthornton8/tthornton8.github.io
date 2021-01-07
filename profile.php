<?php
session_start();

require_once('config.php');
 
if (isset($_GET['action']) && ('logout' == $_GET['action'])) {
    unset($_SESSION['id']);
}
 
if (isset($_SESSION['id'])) {
    $logged_in = 'true';
    $id = $_SESSION['id'];
    
    $sql = "SELECT * FROM test_user WHERE username = '"$id"'";
    // $result = $conn->query($sql);
    // $row = $result->fetch_assoc();
    $name = "";//$row['name'];
    $degree = "";//$row['degree'];
    $uni = "";//$row['uni'];

} else {
    $logged_in = 'false';
    header('Location: login_student.php');
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
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" 
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" 
        crossorigin="anonymous"> -->
    </script>
</head>

<body>
    <script type="text/javascript"> var logged_in = "<?php echo $logged_in; ?>";</script>
    <script type="text/javascript" src="header.js"></script>

    <div class = "_content">
        <div class = "_prof_section _head">
            <img src="img/dp.jpg" alt="Profile Picture" class = "pp">
            <h1><?php echo $name; ?></h1>
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
        <div class = "_prof_section _skills">
            <h2 style = "grid-row: 1">Top Skills</h2>
            <div class = "_bubble _gr1">Machine Learning <img src = "https://www.flaticon.com/svg/static/icons/svg/566/566082.svg" alt = "icon"></div>
            <div class = "_bubble _gr1">Design<img src = "https://www.flaticon.com/svg/static/icons/svg/681/681662.svg" alt = "icon"></div>
            <div class = "_bubble _gr1">Python<img src = "https://www.flaticon.com/svg/static/icons/svg/1336/1336494.svg" alt = "icon"></div>
            <div class = "_bubble _gr1">CFD<img src = "https://www.flaticon.com/svg/static/icons/svg/1055/1055113.svg" alt = "icon"></div>
            <hr style = "grid-column: 1/5; grid-row: 3">
            <h2 style = "grid-row: 4">Top Projects</h2>
            <div class = "_bubble _gr2" onclick="clickBox('machine design')">Machine Design <img src = "https://www.flaticon.com/svg/static/icons/svg/2099/2099058.svg" alt = "icon">
                <p>
                    Project Summary....
                </p>
            </div>
            <div class = "_bubble _gr2" onclick="clickBox('aerocapture')">Aerocapture<img src = "https://www.flaticon.com/svg/static/icons/svg/2285/2285485.svg" alt = "icon">
                <p>
                    Project Summary....
                </p>
            </div>
            <div class = "_bubble _gr2" onclick="clickBox('feasibility study')">Feasibility Study<img src = "https://www.flaticon.com/svg/static/icons/svg/752/752241.svg" alt = "icon">
                <p>
                    Project Summary....
                </p>
            </div>
            <div class = "_bubble _gr2" onclick="clickBox('neural net')">Neural Net<img src = "https://www.flaticon.com/svg/static/icons/svg/566/566082.svg" alt = "icon">
                <p>
                    Project Summary....
                </p>
            </div>
        </div>
        <div class = "_prof_section _about">
            <h2>About</h2>
            <p>A passionate, comitted, and highly motivated 2nd year mechanical engineering student at the University of Bath with hands-on experience in testing and analysing concept designs. Passionate about innovation in engineering, I am excited about applying my skills to new projects and seeing the results of my efforts put to use in a professional environment. Using my firm grasp of tools such as MATLAB and Excel, and product design know-how, I have excelled in delivering results throughout summer internships and my time at university.</p>
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

</body>
</html>