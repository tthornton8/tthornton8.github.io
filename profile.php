<?php
session_start();

	
echo php_ini_loaded_file();

require_once('config.php');
require_once('get_profile.php');
require_once('return_profile.php');
 
if (isset($_GET['action']) && ('logout' == $_GET['action'])) {
    unset($_SESSION['id']);
}
 
if (isset($_SESSION['id'])) {
    $logged_in = 'true';
    $id = $_SESSION['id'];
    list($name, $email, $degree, $uni, $about, $photo, $skills, $projects, $qual, $icons, $usercompanies, $tolearn, $interested) = get_profile_vars($conn, $id);

} else {
    $logged_in = 'false';
    header('Location: login_student.php');
}

if (isset($_POST['submit'])) {
    extract($_POST);
    switch ($action) {
        case "edit_main":
            //echo "edit main";
            require_once('upload_img.php');
 
            $sql =  "UPDATE user\n";
            $sql .= "SET\n";
            $sql .= "name =   '".htmlspecialchars($conn->real_escape_string($name))."',\n";
            $sql .= "email =  '".htmlspecialchars($conn->real_escape_string($email))."',\n";
            $sql .= "degree = '".htmlspecialchars($conn->real_escape_string($degree))."',\n";
            $sql .= "uni =    '".htmlspecialchars($conn->real_escape_string($uni))."',\n";
            if ($uploadOk) {
                $sql .= "photo = '".$conn->real_escape_string($target_file)."',\n";
            }
            $sql .= "about = '".htmlspecialchars($conn->real_escape_string($about))."'\n";
            $sql .= "WHERE ID = '".$conn->real_escape_string($id)."'";
        
            $result = $conn->query($sql);
        
            $sql = "DELETE FROM skill WHERE user_ID = '".$conn->real_escape_string($id)."'";
            $result = $conn->query($sql);
        
            $sql = "INSERT INTO skill (user_ID, name, icon) VALUES ";
            foreach ($skills as &$row) {
                if ($row["name"]) {
                    $sql .= "\n(".$conn->real_escape_string($id).", '".htmlspecialchars($conn->real_escape_string($row["name"]))."', '".htmlspecialchars($conn->real_escape_string($row["icon"]))."'),";
                }
            }
            $sql = substr($sql, 0, -1);
            $sql .= ";";
        
            $result = $conn->query($sql);

            foreach ($projects as &$row) {
                if ($row["name"]) {
                    if ($row['ID'] == 'NEW') {
                        $sql = "INSERT INTO project (user_id, name, icon) VALUES (".$conn->real_escape_string($id).", '".htmlspecialchars($conn->real_escape_string($row["name"]))."', '".htmlspecialchars($conn->real_escape_string($row["icon"]))."')";
                    } else {
                        $sql = "UPDATE project SET name = '".htmlspecialchars($conn->real_escape_string($row["name"]))."', icon = '".htmlspecialchars($conn->real_escape_string($row["icon"]))."' WHERE ID = ".$conn->real_escape_string($row['ID']).";";
                    }
                    $result = $conn->query($sql);
                }
            }

            $projects = [];
            $sql = "SELECT * FROM project WHERE user_ID = ".$conn->real_escape_string($id);
            $result = $conn->query($sql);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $projects[] = $row;
                }
                
            }

            $qual = [];
            foreach ($qualvalue as $key => $value){
                foreach ($value as &$detail) {
                    if ($detail) {
                        $qual[] = array (
                            "type" => $qualtype[$key],
                            "value" => $detail,
                        );
                    }
                }
            }

            $sql = "DELETE FROM qual WHERE user_ID = '".$conn->real_escape_string($id)."'";
            $result = $conn->query($sql);

            $sql = "INSERT INTO qual (user_ID, type, value) VALUES ";
            foreach ($qual as &$row) {
                $sql .= "\n(".$conn->real_escape_string($id).", '".htmlspecialchars($conn->real_escape_string($row["type"]))."', '".htmlspecialchars($conn->real_escape_string($row["value"]))."'),";
            }
            $sql = substr($sql, 0, -1);
            $sql .= ";";
            $result = $conn->query($sql);

            // print_r($usercompanies);
            $sql = "DELETE FROM usercompanies WHERE user_ID = '".$conn->real_escape_string($id)."'";
            $result = $conn->query($sql);

            $sql = "INSERT INTO usercompanies (user_ID, logourl) VALUES ";
            foreach ($usercompanies_new as &$row) {
                if ($row) {
                    $sql .= "\n(".$conn->real_escape_string($id).", '".htmlspecialchars($conn->real_escape_string($row))."'),";
                }
            }
            $sql = substr($sql, 0, -1);
            $sql .= ";";
            $result = $conn->query($sql);

            $usercompanies = [];
            $sql = "SELECT * FROM usercompanies";
            $result = $conn->query($sql);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $usercompanies[] = $row;
                }
            }
            break;

        case "edit_project":
            //echo 'Edit Project';
            $allowedTags='<p><strong><em><u><h1><h2><h3><h4><h5><h6><img>';
            $allowedTags.='<li><ol><ul><span><div><br/><ins><del>'; 
            $text = strip_tags(stripslashes($detail),$allowedTags);
            $proj_file_id = md5($project.$id);

            $f = fopen("../uploads/$proj_file_id.html", 'w');
            fwrite($f, $text);
            fclose($f);

            if ($project == 'NEW') {
                $sql = "INSERT INTO project (user_id, name, details, summary, icon) VALUES (".$conn->real_escape_string($id).", '".htmlspecialchars($conn->real_escape_string($proj_name))."', '".$conn->real_escape_string($proj_file_id)."', '".htmlspecialchars($conn->real_escape_string($summary))."', ".htmlspecialchars($conn->real_escape_string($proj_icon)).");";
            } else {
                $sql = "UPDATE project SET\n";
                $sql .= "details = '".$conn->real_escape_string($proj_file_id)."'\n";
                $sql .= ", summary = '".htmlspecialchars($conn->real_escape_string($summary))."'\n";
                $sql .= "WHERE ID = $project;";
            }

            // echo $sql;
            $result = $conn->query($sql);
            for ($i = 0; $i <= count($projects); $i++) {
                // echo $projects[$i]["ID"];
                // echo $project;
                if ($projects[$i]["ID"] == $project) {
                    $projects[$i]["details"] = htmlspecialchars($conn->real_escape_string($proj_file_id));
                    $projects[$i]["summary"] = htmlspecialchars($conn->real_escape_string($summary));
                    if ($icondropdown) {
                        $projects[$i]["icon"] = htmlspecialchars($conn->real_escape_string($icondropdown));
                    }
                } 
            }
            break;
        default:
            echo "";
    }   
}
?>
<?php
function phpAlert($msg) {
    //echo "msg: $msg";
    if ($msg != "") {
        echo '<script type="text/javascript">alert("' . htmlspecialchars($msg) . '")</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<title>My Profile | GradCherry</title>

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
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-2021.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-flat.css">
    <script src="https://cdn.tiny.cloud/1/184b9akoev1y38p25nmv4os4h082uhrc9copbqe6hxbwl72t/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://kit.fontawesome.com/dfef441bb4.js" crossorigin="anonymous"></script>
    <?php phpAlert(   $fileAlert  );  ?>

</head>

<body>
    <script type="text/javascript"> var logged_in = "<?php echo $logged_in; ?>";</script>
    <script type="text/javascript" src="header.js"></script>
    <script type="text/javascript" src="script.js"></script>

    <div class = "_profile_main">
        <div class ="_right">
            <ul>
                <li class = "tabLink" onclick = "openLink(event, 'Export');">Export CV</li>
                <li class = "tabLink tab-selected" onclick = "openLink(event, 'GCcv');">GradCherry CV</li>
                <li class = "tabLink" onclick = "openLink(event, 'editWindow');">Edit CV</li>
                <li class = "tabLink" onclick = "openLink(event, 'Edit');">Edit Profile</li>
                <li class = "tabLink" onclick = "openLink(event, 'FavStudents');">My Favorite Students</li>
                <li class = "tabLink" onclick = "openLink(event, 'FavCompanies');">My Favorite Companies</li>
                <li class = "tabLink" onclick = "openLink(event, 'Proj');">My Projects</li>
                <li class = "tabLink" onclick = "openLink(event, 'Feedback');">My Feedback</li>
                <li class = "tabLink" onclick = "openLink(event, 'Ideas');">My Ideas / Blog Posts</li>
            </ul>
        </div>
        <div class = "tabEl w3-animate-opacity" style = "display:none;" id = "Export">
            Export
        </div>
        <div class = "tabEl w3-animate-opacity ignore_tabEl" style = "" id = "GCcv">
            <?php
                return_profile($name, $email, $degree, $uni, $about, $photo, $skills, $projects, $qual, $icons, $usercompanies, $id, $tolearn, $interested);
            ?>
        </div>
        <div class = "tabEl w3-animate-opacity" style = "display:none;" id = "Edit">
            Edit
        </div>
        <div class = "tabEl w3-animate-opacity" style = "display:none;" id = "FavStudents">
            Favorite Students
        </div>
        <div class = "tabEl w3-animate-opacity" style = "display:none;" id = "FavCompanies">
            Favorite Companies
        </div>
        <div class = "tabEl w3-animate-opacity" style = "display:none;" id = "Proj">
            My Projects
        </div>
        <div class = "tabEl w3-animate-opacity" style = "display:none;" id = "Feedback">
            Feedback
        </div>
        <div class = "tabEl w3-animate-opacity" style = "display:none;" id = "Ideas">
        <?php
                require('forum.php');
            ?>
        </div>
        <div class = "tabEl w3-animate-opacity" style = "display:none;" id="editWindow">
                <form method="post" id = "cvForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                    <div class="_profile">
                        <input type="hidden" name="action" value="edit_main">
                        <div class="_prof_section _head">
                            <input type="file" name="fileToUpload" id="fileToUpload" >
                            <label class="w3-text _file_upload" for="fileToUpload" style = "grid-area:pp;">
                                <img src="img.php?id=<?php echo $id; ?>" alt="Profile Picture" class = "pp">
                            </label>
                            <h1><input type="text" class="w3-input w3-border w3-light-grey" id="inputname" name="name" placeholder="Name" value = "<?php echo $name; ?>"/></h1>
                            <hr/>
                            <div class="_head_desc">
                                <h2><input type="text" class="w3-input w3-border w3-light-grey" id="inputdegree" name="degree" placeholder="Degree" value = "<?php echo $degree; ?>"/></h2>
                                <h2><input type="text" class="w3-input w3-border w3-light-grey" id="inputuni" name="uni" placeholder="University" value = "<?php echo $uni; ?>"/></h2>
                            </div>
                        </div>

                        <!-- <label class="w3-text" style = "color: var(--darkCherry);" for="inputemail">Email</label>
                        <input type="email" class="w3-input w3-border w3-light-grey" id="inputemail" name="email" placeholder="Email" value = "<?php echo $email; ?>"/> -->
                        <div class="_prof_section _about">
                            <h2>About</h2>
                            <textarea class="w3-input w3-border w3-light-grey" id="inputabout" name="about" placeholder="About"><?php echo $about; ?></textarea>
                        </div>
                        
                        <div id = "skills_section" class="_prof_section _skills">
                            <p class="w3-text" style = "color: var(--darkCherry); margin-top: 10px; font-size:150%; grid-row: 1; grid-column: 1/3">Skills</p>
                            <?php 
                            $i = 1;
                            $j = 0;
                            foreach ($skills as &$row) {
                                if ($row["name"]) {
                                    echo "<div class = \"_gr1\">";
                                    echo "<input style = \"display: inline;\" type=\"text\" class=\"w3-input w3-border w3-light-grey\" id=\"skills[$j][name]\" name=\"skills[$j][name]\" placeholder=\"Skill \"$i\" value = \"".htmlspecialchars($row["name"])."\"/>\n";
                                    echo "<input type=\"hidden\" class=\"w3-input w3-border w3-light-grey\" id=\"skills[$j][icon]\" name=\"skills[$j][icon]\" value = \"".htmlspecialchars($row["icon"])."\"/>\n\n";
                                    
                                    echo "<button onclick = \"toggleVis('dropdown-content_skills_$j');\" class=\"dropbtn\" type=\"button\" id = \"dropbtn_skills$j\"><img src = icon.php?id=".$row['icon']." width = '25px', height = '25px'></button>";
                                    echo "<div class=\"dropdown-content\" id = \"dropdown-content_skills_$j\">";
                                    foreach ($icons as &$irow) {
                                        $img_tag = "<img src = icon.php?id=".$irow['ID']." width = '20px', height = '20px' style = 'margin-right: 16px;'>";
                                        $img_tag_large = "<img src = icon.php?id=".$irow['ID']." width = '25px', height = '25px'>";
                                        $onclick = "\" document.getElementById('skills[$j][icon]').value = '".$irow['ID']."'; document.getElementById('dropbtn_skills$j').innerHTML = `$img_tag_large`; toggleVis('dropdown-content_skills_$j');\"";
                                        echo "\t\t\t\t\t\t\t\t<a onclick = $onclick id = \"icon_".$j."_".$irow['ID']."\">".$img_tag.$irow['descrip']."</a>\n";
                                    }
                                    echo "</div></div>";

                                    $i += 1;
                                    $j += 1;
                                }
                            }
                            ?>

                            <button type="button" name="add_skill" style = "margin-bottom: 1em; margin-top: 0.3em;" class="w3-btn w3-blue-grey _addbtn" onclick = "addSection('skills');">+</button>

                        </div>
                            
                        <div class = "_prof_section _skills _projects" id = "_projects_section">
                            <p class="w3-text" style = "color: var(--darkCherry); margin-top: 10px; font-size:150%; grid-row: 1; grid-column: 1/3">Projects</p>
                            <?php
                            // echo print_r($projects);
                            $i = 1;
                            $j = 0;
                            foreach ($projects as &$row) {
                                // echo "<div class = \"_bubble\">".$row['name']."&nbsp;</div>";
                                echo "<div class = \"_gr1\">";
                                echo "<input style = \"display: inline;\" type=\"text\" class=\"w3-input w3-border w3-light-grey\" id=\"projects[$j][name]\" name=\"projects[$j][name]\" placeholder=\"Project \"$i\" value = \"".htmlspecialchars($row["name"])."\"/>\n";
                                echo "<input type=\"hidden\" id=\"projects[$j][icon]\" name=\"projects[$j][icon]\" value = \"".htmlspecialchars($row["icon"])."\"/>\n\n";
                                echo "<input type=\"hidden\" id=\"projects[$j][ID]\" name=\"projects[$j][ID]\" value = \"".htmlspecialchars($row["ID"])."\"/>\n\n";

                                echo "<button onclick = \"toggleVis('dropdown-content_projects_$j');\" class=\"dropbtn\" type=\"button\" id = \"dropbtn_projects$j\"><img src = icon.php?id=".$row['icon']." width = '25px', height = '25px'></button>";
                                echo "<div class=\"dropdown-content\" id = \"dropdown-content_projects_$j\">";
                                foreach ($icons as &$irow) {
                                    $img_tag = "<img src = icon.php?id=".$irow['ID']." width = '20px', height = '20px' style = 'margin-right: 16px;'>";
                                    $img_tag_large = "<img src = icon.php?id=".$irow['ID']." width = '25px', height = '25px'>";
                                    $onclick = "\" document.getElementById('projects[$j][icon]').value = '".$irow['ID']."'; document.getElementById('dropbtn_projects$j').innerHTML = `$img_tag_large`; toggleVis('dropdown-content_projects_$j');\"";
                                    echo "\t\t\t\t\t\t\t\t<a onclick = $onclick id = \"icon_".$j."_".$irow['ID']."\">".$img_tag.$irow['descrip']."</a>\n";
                                }
                                echo "</div>";
                                echo "<div onclick = \"editProject(".$row['ID'].",`".$row['details']."`,".$row['icon'].");\" class = \"_edit_pencil\" id = \"_edit_pencil\">&#x1f589;</div></div>\n";

                                $i += 1;
                                $j += 1;
                            }
                            ?>

                            <button type="button" name="add_project" style = "margin-bottom: 1em; margin-top: 0.3em;" class="w3-btn w3-blue-grey _addbtn" onclick = "addProject();">+</button>
                        </div>

                        <div id = "tolearn_section" class="_prof_section _skills" style = "grid-area:tolearn">
                            <p class="w3-text" style = "color: var(--darkCherry); margin-top: 10px; font-size:150%; grid-row: 1; grid-column: 1/3">Want to Learn</p>
                            <?php 
                            $i = 1;
                            $j = 0;
                            foreach ($tolearn as &$row) {
                                if ($row["name"]) {
                                    echo "<div class = \"_gr1\">";
                                    echo "<input style = \"display: inline;\" type=\"text\" class=\"w3-input w3-border w3-light-grey\" id=\"tolearn[$j][name]\" name=\"tolearn[$j][name]\" placeholder=\"Skill \"$i\" value = \"".htmlspecialchars($row["name"])."\"/>\n";
                                    echo "<input type=\"hidden\" class=\"w3-input w3-border w3-light-grey\" id=\"tolearn[$j][icon]\" name=\"tolearn[$j][icon]\" value = \"".htmlspecialchars($row["icon"])."\"/>\n\n";
                                    
                                    echo "<button onclick = \"toggleVis('dropdown-content_tolearn_$j');\" class=\"dropbtn\" type=\"button\" id = \"dropbtn_tolearn$j\"><img src = icon.php?id=".$row['icon']." width = '25px', height = '25px'></button>";
                                    echo "<div class=\"dropdown-content\" id = \"dropdown-content_tolearn_$j\">";
                                    foreach ($icons as &$irow) {
                                        $img_tag = "<img src = icon.php?id=".$irow['ID']." width = '20px', height = '20px' style = 'margin-right: 16px;'>";
                                        $img_tag_large = "<img src = icon.php?id=".$irow['ID']." width = '25px', height = '25px'>";
                                        $onclick = "\" document.getElementById('tolearn[$j][icon]').value = '".$irow['ID']."'; document.getElementById('dropbtn_tolearn$j').innerHTML = `$img_tag_large`; toggleVis('dropdown-content_tolearn_$j');\"";
                                        echo "\t\t\t\t\t\t\t\t<a onclick = $onclick id = \"icon_".$j."_".$irow['ID']."\">".$img_tag.$irow['descrip']."</a>\n";
                                    }
                                    echo "</div></div>";

                                    $i += 1;
                                    $j += 1;
                                }
                            }
                            ?>

                            <button type="button" name="add_tolearn" style = "margin-bottom: 1em; margin-top: 0.3em;" class="w3-btn w3-blue-grey _addbtn" onclick = "addSection('tolearn');">+</button>

                        </div>
                        <div id = "interested_section" class="_prof_section _skills _projects" style = "grid-area:interested">
                            <p class="w3-text" style = "color: var(--darkCherry); margin-top: 10px; font-size:150%; grid-row: 1; grid-column: 1/3">Projects Interested in</p>
                            <?php 
                            $i = 1;
                            $j = 0;
                            foreach ($interested as &$row) {
                                if ($row["name"]) {
                                    echo "<div class = \"_gr1\">";
                                    echo "<input style = \"display: inline;\" type=\"text\" class=\"w3-input w3-border w3-light-grey\" id=\"interested[$j][name]\" name=\"interested[$j][name]\" placeholder=\"Skill \"$i\" value = \"".htmlspecialchars($row["name"])."\"/>\n";
                                    echo "<input type=\"hidden\" class=\"w3-input w3-border w3-light-grey\" id=\"interested[$j][icon]\" name=\"interested[$j][icon]\" value = \"".htmlspecialchars($row["icon"])."\"/>\n\n";
                                    
                                    echo "<button onclick = \"toggleVis('dropdown-content_interested_$j');\" class=\"dropbtn\" type=\"button\" id = \"dropbtn_interested$j\"><img src = icon.php?id=".$row['icon']." width = '25px', height = '25px'></button>";
                                    echo "<div class=\"dropdown-content\" id = \"dropdown-content_interested_$j\">";
                                    foreach ($icons as &$irow) {
                                        $img_tag = "<img src = icon.php?id=".$irow['ID']." width = '20px', height = '20px' style = 'margin-right: 16px;'>";
                                        $img_tag_large = "<img src = icon.php?id=".$irow['ID']." width = '25px', height = '25px'>";
                                        $onclick = "\" document.getElementById('interested[$j][icon]').value = '".$irow['ID']."'; document.getElementById('dropbtn_interested$j').innerHTML = `$img_tag_large`; toggleVis('dropdown-content_interested_$j');\"";
                                        echo "\t\t\t\t\t\t\t\t<a onclick = $onclick id = \"icon_".$j."_".$irow['ID']."\">".$img_tag.$irow['descrip']."</a>\n";
                                    }
                                    echo "</div></div>";

                                    $i += 1;
                                    $j += 1;
                                }
                            }
                            ?>

                            <button type="button" name="add_interested" style = "margin-bottom: 1em; margin-top: 0.3em;" class="w3-btn w3-blue-grey _addbtn" onclick = "addSection('interested');">+</button>

                        </div>

                        <div class="_prof_section _quals">
                            <div  id = "qualifications_section">
                                <p class="w3-text" style = "color: var(--darkCherry); margin-top: 10px;" >Qualifications</p>
                                <?php 
                                    $html = '';
                                    $out = array();
                                    foreach ($qual as &$row) {
                                        if (array_key_exists($row["type"], $out)) {
                                            $out[$row["type"]][] = $row["value"];
                                        } else {
                                            $out[$row["type"]] = [$row["value"]];
                                        }
                                    }
                                    $i = 0;
                                    $j = 0;
                                    foreach ($out as $key => $quals) {
                                        $html .= "<input type=\"text\" class=\"w3-input w3-border w3-light-grey _qual_type\" id=\"qualtype[$i]\" name=\"qualtype[$i]\" placeholder=\"Type\" value = \"$key\"/>";
                                        $html .= "\n<ul id = \"qual_$i\">";
                                        foreach ($quals as &$value) {
                                            $html .= "\n<li><input type=\"text\" class=\"w3-input w3-border w3-light-grey _qualvalue\" id=\"qualvalue[$i][$j]\" name=\"qualvalue[$i][$j]\" placeholder=\"Detail\" value = \"$value\"/></li>";
                                            $j += 1;
                                        }
                                        $html .= "\n</ul>";
                                        $html .= "\n<ul><li><button type=\"button\" name=\"add_qual_detail\" style = \"margin-bottom: 1em; margin-top: 0.3em;\" class=\"w3-btn w3-blue-grey\" onclick = \"addQualDetail($i);\">+</button></li></ul>";
                                        $i += 1;
                                    };
                                    echo $html;
                                ?>    
                            </div>
                            <button type="button" name="add_qual" style = "margin-bottom: 1em; margin-top: 0.3em;" class="w3-btn w3-blue-grey" onclick = "addQual();">+</button>
                        </div>
                                            
                        <div class="_prof_section _companies">
                            <p class="w3-text" style = "color: var(--darkCherry); margin-top: 10px; font-size:150%">Companies Worked With</p>
                            <div class = "_logo_section" id = "logo_section">
                                <?php
                                    $html_c = "";
                                    $i = 0;
                                    foreach ($usercompanies as &$row) {
                                        $html_c .= "<input type=\"text\" class=\"w3-input w3-border w3-light-grey\" id=\"usercompanies_new[$i]\" name=\"usercompanies_new[$i]\" placeholder=\"URL of company logo\" value = \"".$row["logourl"]."\"/>";
                                        $i += 1;
                                    }
                                    echo $html_c;
                                ?>
                            </div>
                            <br/>
                            <button type="button" name="add_company" style = "margin-bottom: 1em; margin-top: 0.3em;" class="w3-btn w3-blue-grey" onclick = "addCompany();">+</button>
                            <br/>
                        </div>
                        
                        <div class = "_save_bar">
                            <button type="submit" name="btnsubmit" style = "margin-bottom: 1em; margin: 0 auto; margin-right: -43%;" class="w3-btn w3-flat-emerald">Save</button>
                            <button type="button" name="cancel" style = "margin-bottom: 1em; margin: 0 auto;" onclick = "document.getElementById('cvForm').reset(); document.getElementById('cvForm').submit();" class="w3-btn w3-flat-alizarin">Cancel</button>
                        </div>

                        <script type="text/javascript" defer>
                        function addCompany() {
                            var i = document.querySelectorAll('[id^="usercompanies_new').length;
                            var section = document.getElementById('logo_section')
                            var els = createElementFromHTML(`
                                <input type="text" class="w3-input w3-border w3-light-grey" id="usercompanies_new[${i}]" name="usercompanies_new[${i}]" placeholder="URL of company logo" value = ""/>
                            `)
                            for (let item of els) {
                                section.appendChild(item);
                            }
                        }
                        function addQualDetail(i) {
                            var ul_i = document.getElementById(`qual_${i}`)
                            var j = ul_i.getElementsByTagName('li').length;
                            var els = createElementFromHTML(`
                            <li><input type="text" class="w3-input w3-border w3-light-grey _qual_value" id="qualvalue[${i}][${j}]" name="qualvalue[${i}][${j}]" placeholder="Detail" value = ""/></li>
                            `);
                            for (let item of els) {
                                ul_i.appendChild(item);
                            }
                        }
                        function addQual() {
                            var i = document.querySelectorAll('[id^="qual_"]').length;
                            var quals = document.getElementById("qualifications_section");
                            var els = createElementFromHTML(`
                                <input type="text" class="w3-input w3-border w3-light-grey _qual_type" id="qualtype[${i}]" name=\"qualtype[${i}]" placeholder="Type" value = ""/>
                                <ul id = qual_${i}></ul>
                                <ul><li><button type="button" name="add_qual_detail" style = "margin-bottom: 1em; margin-top: 0.3em;" class="w3-btn w3-blue-grey" onclick = "addQualDetail(${i});">+</button></li></ul>
                            `);
                            for (let item of els) {
                                quals.appendChild(item);
                            }

                        }
                        function addSection(section_name) {
                            var i = document.querySelectorAll(`[id^="{section_name}\["]`).length/2;
                            skills = document.getElementById(`${section_name}_section`);
                            var els = createElementFromHTML(`
                                <div class = "_gr1">
                                    <input type="text" style = "display: inline" class="w3-input w3-border w3-light-grey" id="${section_name}[${i}][name]" name="${section_name}[${i}][name]" placeholder="Skill ${i+1}" value = ""/>\n
                                    <input type="hidden" class="w3-input w3-border w3-light-grey" id="${section_name}[${i}][icon]" name="${section_name}[${i}][icon]" placeholder="Skill ${i+1}" value = ""/>

                                    <button onclick = "toggleVis('dropdown-content_${section_name}_${i}');" class="dropbtn" type="button" id = "dropbtn_${section_name}${i}"><img src = icon.php?id=0 width = '25px', height = '25px'></button>
                                    <div class="dropdown-content" id = "dropdown-content_${section_name}_${i}">
                                        <?php
                                        foreach ($icons as &$irow) {
                                            $img_tag = "<img src = icon.php?id=".$irow['ID']." width = '20px', height = '20px' style = 'margin-right: 16px;'>";
                                            $img_tag_large = "<img src = icon.php?id=".$irow['ID']." width = '25px', height = '25px'>";
                                            $onclick = "\" document.getElementById('\${section_name}[\${i}][icon]').value = '".$irow['ID']."'; document.getElementById('dropbtn_\${section_name}\${i}').innerHTML = \`$img_tag_large\`; toggleVis('dropdown-content_\${section_name}_\${i}');\"";
                                            echo "\t\t\t\t\t\t\t<a onclick = $onclick id = \"icon_\${i}_".$irow['ID']."\">".$img_tag.$irow['descrip']."</a>\n";
                                        }
                                        ?>
                                    </div>
                                </div>
                            `);
                            for (let item of els) {
                                skills.appendChild(item);
                            }
                        };
                        function addProject() {
                            var i = document.querySelectorAll('[id^="projects\["]').length/3;
                            projects = document.getElementById("_projects_section");
                            var els = createElementFromHTML(`
                                <div class = "_gr1">
                                <input type="text" style = "display: inline" class="w3-input w3-border w3-light-grey" id="projects[${i}][name]" name="projects[${i}][name]" placeholder="Project ${i+1}" value = ""/>\n
                                <input type="hidden" class="w3-input w3-border w3-light-grey" id="projects[${i}][icon]" name="projects[${i}][icon]" placeholder="Project ${i+1}" value = ""/>
                                <input type="hidden" id="projects[${i}][ID]" name="projects[${i}][ID]" value="NEW">

                                <button onclick = "toggleVis('dropdown-content_projects_${i}');" class="dropbtn" type="button" id = "dropbtn_projects${i}"><img src = icon.php?id=0 width = '25px', height = '25px'></button>
                                <div onclick = "editProject('NEW','',0);" class = "_edit_pencil" id = "_edit_pencil">&#x1f589;</div>
                                <br/>
                                <div class="dropdown-content" id = "dropdown-content_projects_${i}">
                                    <?php
                                    foreach ($icons as &$irow) {
                                        $img_tag = "<img src = icon.php?id=".$irow['ID']." width = '20px', height = '20px' style = 'margin-right: 16px;'>";
                                        $img_tag_large = "<img src = icon.php?id=".$irow['ID']." width = '25px', height = '25px'>";
                                        $onclick = "\" document.getElementById('projects[\${i}][icon]').value = '".$irow['ID']."'; document.getElementById('dropbtn_projects\${i}').innerHTML = \`$img_tag_large\`; toggleVis('dropdown-content_projects_\${i}');\"";
                                        echo "\t\t\t\t\t\t\t<a onclick = $onclick id = \"icon_\${i}_".$irow['ID']."\">".$img_tag.$irow['descrip']."</a>\n";
                                    }
                                    ?>
                                </div>
                                </div>
                            `);
                            for (let item of els) {
                                console.log(item);
                                projects.appendChild(item);
                            }
                        };

                        function createElementFromHTML(htmlString) {
                            var div = document.createElement('div');
                            div.innerHTML = htmlString.trim();

                            // Change this to div.childNodes to support multiple top-level nodes
                            return div.childNodes; 
                        };
                        </script>
                    </div>
                </form>
        </div>
    </div>

    <div id="projectWindow" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close_modal">&times;</span>
                <h2>Edit Project</h2>
            </div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" id = "projectEdit" style = 'padding:1em;'>
                <input type="hidden" name="action" value="edit_project">
                <input type="hidden" name="project" value="" id = "projectID">
                <input type="hidden" name="proj_name" value="" id = "proj_name">
                <input type="hidden" name="proj_icon" value="" id = "proj_icon">
                <br/>
                <label class="w3-text" style = "color: var(--darkCherry);" for="summary">Project Summary</label>
                <textarea id = "summary" name = "summary" class="w3-input w3-border w3-light-grey">
                    Project Summary goes here...
                </textarea>
                <br/>
                <label class="w3-text" style = "color: var(--darkCherry);" for="tinymce">Project Detail</label>
                <textarea id = "tinymce" name = "detail">
                    Project detail goes here...
                </textarea>
                <br/>
                <button type="submit" name="btnsubmit" style = "margin-bottom: 1em;" class="w3-btn w3-blue-grey">Save</button>
                <br/>
            </form>
            <div class="modal-footer">
                <h3>&nbsp;</h3>
            </div>
        </div>
    </div>

    <script >
        // Get the modal
        var modal = document.getElementById("editWindow");
        var modal2 = document.getElementById("projectWindow");
        
        // Get the button that opens the modal
        var btn = document.getElementById("_edit_pencil");
        
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close_modal")[0];
        var span2 = document.getElementsByClassName("close_modal")[1];
        
        // When the user clicks the button, open the modal 
        btn.onclick = function() {
          modal.style.display = "block";
        }
        
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
          modal.style.display = "none";
        }

        // When the user clicks on <span> (x), close the modal
        span2.onclick = function() {
          modal2.style.display = "none";
        }
        
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
            modal2.style.display = "none";
          }
        }

        function getProjContent(detail) {
            xmlhttp=new XMLHttpRequest();
            xmlhttp.open("GET", "project.php?id=" + detail, false);
            xmlhttp.send();
            var html = xmlhttp.responseText;
            html = `<span onclick="closeBox()" class="close" title="Close">&times;</span>` + html;
            return html
        };

        function editProject(id, detail, name, icon) {
            name = name || '';
            icon = icon || '';

            modal2.style.display = "block";
            var box = document.getElementById("projectID");
            var mce = document.getElementById("tinymce");
            box.value = id;

            //document.getElementById('dropbtn').innerHTML = "<img src = icon.php?id=" + img + " width = '25px', height = '25px'>";
            document.getElementById('proj_name').value = name;
            document.getElementById('proj_icon').value = icon;

            xmlhttp=new XMLHttpRequest();
            xmlhttp.open("GET", "project.php?id=" + detail, false);
            xmlhttp.send();
            var text = xmlhttp.responseText;
            mce.innerHTML = text;

            try {
                tinymce.get("tinymce").setContent(text);
            } catch (error) {
                tinymce.init({
                        selector: 'textarea#tinymce',
                        elements : "detail",
                        plugins: 'autolink lists media table',
                        toolbar: 'addcomment showcomments  code formatpainter table',
                        toolbar_mode: 'floating',
                        tinycomments_mode: 'embedded',
                        tinycomments_author: 'Author name',
                });
            }
        };

        window.toggleVis = function(elid) {
            var el = document.getElementById(elid);
            var dd = document.getElementsByClassName("dropdown-content");
            if (el.style.display == "none" || el.style.display == "") {
                on = true;
            } else {
                on = false;
            };
            for (var i = 0; i < dd.length; i++) {
                dd[i].style.display = "none";
            };
            if (on) {
                el.style.display = "inline-block";
            }

        };

        function openLink(evt, animName) {
            var i, x, tablinks;
            x = document.getElementsByClassName("tabEl");
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tabLink");
            for (i = 0; i < x.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" tab-selected", "");
            }
            document.getElementById(animName).style.display = "block";
            evt.currentTarget.className += " tab-selected";
        };
        </script>

    <p style = "text-align: center;">
        <img src="img/fb.png" alt="Students" width= 22px> &nbsp;
        <img src="img/in.png" alt="Students" width= 22px> &nbsp;
        <img src="img/tw.png" alt="Students" width= 22px> &nbsp; <br>
        ©2021 by G-CHERRY SOLUTIONS LTD.
    </p>

</body>
</html>