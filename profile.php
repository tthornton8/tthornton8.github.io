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

    $skills = [];
    $sql = "SELECT * FROM skill WHERE user_ID = ".$conn->real_escape_string($id);
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $skills[] = array (
                "name" => $row["name"],
                "icon" => $row["icon"],
            );
        }
    }

    $projects = [];
    $sql = "SELECT * FROM project WHERE user_ID = ".$conn->real_escape_string($id);
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $projects[] = array (
                "ID"      => $row["ID"],
                "name"    => $row["name"],
                "company" => $row["company"],
                "details" => $row["details"],
                "icon"    => $row["icon"],
                "summary" => $row["summary"],
                "gradz"   => $row["gradz"],
            );
        }
        // echo print_r($projects);
    }

    $icons = [];
    $sql = "SELECT * FROM icon";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $icons[] = $row;
        }
    }

} else {
    $logged_in = 'false';
    header('Location: login_student.php');
}

if (isset($_POST['submit'])) {
 
    extract($_POST);
    switch ($action) {
        case "edit_main":
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
        
            // echo '<pre>'; print_r($_POST); echo '</pre>';
            $sql = "INSERT INTO skill (user_ID, name, icon) VALUES ";
            foreach ($skills as &$row) {
                if ($row["name"]) {
                    $sql .= "\n(".$conn->real_escape_string($id).", '".htmlspecialchars($conn->real_escape_string($row["name"]))."', '".htmlspecialchars($conn->real_escape_string($row["icon"]))."'),";
                }
            }
            $sql = substr($sql, 0, -1);
            $sql .= ";";
        
            // echo $sql;
            $result = $conn->query($sql);
            break;

        case "edit_project":
            $allowedTags='<p><strong><em><u><h1><h2><h3><h4><h5><h6><img>';
            $allowedTags.='<li><ol><ul><span><div><br><ins><del>'; 
            $text = strip_tags(stripslashes($detail),$allowedTags);
            $proj_file_id = md5($project.$id);

            $f = fopen("../uploads/$proj_file_id.html", 'w');
            fwrite($f, $text);
            fclose($f);

            $sql = "UPDATE project SET\n";
            $sql .= "details = '".$conn->real_escape_string($proj_file_id)."'\n";
            $sql .= ", summary = '".htmlspecialchars($conn->real_escape_string($summary))."'\n";
            if ($icondropdown) {
                $sql .= ", icon = '".$conn->real_escape_string($icondropdown)."'\n";
            }
            $sql .= "WHERE ID = $project;";

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
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-2021.css">
    <script src="https://cdn.tiny.cloud/1/184b9akoev1y38p25nmv4os4h082uhrc9copbqe6hxbwl72t/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" 
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" 
        crossorigin="anonymous"> -->
    <?php phpAlert(   $fileAlert  );  ?>

</head>

<body>
    <script type="text/javascript"> var logged_in = "<?php echo $logged_in; ?>";</script>
    <script type="text/javascript" src="header.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <script src="svg-inject.js"></script>

    <div class = "_content">

        <div class = "_prof_section _head">
            <img src="<?php echo "img.php?id={$id}"; ?>" alt="Profile Picture" class = "pp">
            <h1><?php echo $name; ?> <div onclick="document.getElementById('editWindow').style.display = 'block';" class = "_edit_pencil" id = "_edit_pencil">&#x1f589;</div> </h1>
            <hr>
            <div class = "_head_desc">
                <h2><?php echo $degree; ?></h2>
                <h2><?php echo $uni; ?></h2>
            </div>
            <img alt = "University Logo" src = "img/bath.png" style = "height: 80px;" class = "_uni">
        </div>

        <div class = "_prof_section _about">
            <h2>About</h2>
            <p><?php echo $about; ?></p>
        </div>
        <div class = "_prof_section _skills">
            <h2 style = "grid-row: 1">Top Skills</h2>
            <?php 
                foreach ($skills as &$row) {
                    echo "<div class = \"_bubble _gr1\">".$row["name"]."<img src = icon.php?id=".$row["icon"]." alt = \"icon\" onload=\"SVGInject(this)\"></div>";
                }
            ?>
        </div>
        <div class = "_prof_section _skills">
            <!-- <hr style = "grid-column: 1/5; grid-row: 3"> -->
            <h2 style = "grid-row: 4">Top Projects</h2>
            <?php
                foreach ($projects as &$row) {
                    echo "<div class = \"_bubble _gr2\" onclick=\"clickBox('".$row['details']."')\">".$row['name'];
                    // echo print_r($row);
                    if ($row['gradz']) {
                        echo "\n<span class = \"_gradz_project\">with the gradz</span>";
                    }
                    echo "\n<img src = icon.php?id=".$row["icon"]." alt = \"icon\">";
                    echo "\n<p>".$row['summary']."</p></div>";
                }
            ?>

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
                <input type="hidden" name="action" value="edit_main">
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
                <textarea class="w3-input w3-border w3-light-grey" id="inputabout" name="about" placeholder="About"><?php echo $about; ?></textarea>
                <br>
                
                <div id = "skills_section">
                    <p class="w3-text" style = "color: #0072B5; margin-top: 10px; font-size:150%">Skills</p>
                    <?php 
                    $i = 1;
                    $j = 0;
                    foreach ($skills as &$row) {
                        if ($row["name"]) {
                            echo "<label class=\"w3-text\" style = \"color: #0072B5; margin-top: 10px;\" for=\"skills[$j][name]\">Skill $i</label>\n";
                            echo "<input type=\"text\" class=\"w3-input w3-border w3-light-grey\" id=\"skills[$j][name]\" name=\"skills[$j][name]\" placeholder=\"Skill \"$i\" value = \"".htmlspecialchars($row["name"])."\"/>\n";
                            echo "<input type=\"hidden\" class=\"w3-input w3-border w3-light-grey\" id=\"skills[$j][icon]\" name=\"skills[$j][icon]\" value = \"".htmlspecialchars($row["icon"])."\"/>\n\n";
                            
                            echo "<button onclick = \"toggleVis('dropdown-content_skills_$j');\" class=\"dropbtn\" type=\"button\" id = \"dropbtn_$j\"><img src = icon.php?id=".$row['icon']." width = '25px', height = '25px'></button>";
                            echo "<div class=\"dropdown-content\" id = \"dropdown-content_skills_$j\">";
                            foreach ($icons as &$irow) {
                                $img_tag = "<img src = icon.php?id=".$irow['ID']." width = '20px', height = '20px' style = 'margin-right: 16px;'>";
                                $img_tag_large = "<img src = icon.php?id=".$irow['ID']." width = '25px', height = '25px'>";
                                $onclick = "\" document.getElementById('skills[$j][icon]').value = '".$irow['ID']."'; document.getElementById('dropbtn_$j').innerHTML = `$img_tag_large`; toggleVis('dropdown-content_skills_$j');\"";
                                echo "\t\t\t\t\t\t\t\t<a onclick = $onclick id = \"icon_".$j."_".$irow['ID']."\">".$img_tag.$irow['descrip']."</a>\n";
                            }
                            echo "</div>";

                            $i += 1;
                            $j += 1;
                        }
                    }
                    ?>

                </div>
                <button type="button" name="add_skill" style = "margin-bottom: 1em; margin-top: 0.3em;" class="w3-btn w3-blue-grey" onclick = "addSkill();">+</button>
                <br>

                <p class="w3-text" style = "color: #0072B5; margin-top: 10px; font-size:150%">Projects</p>

                <div class = "_projects_section" id = "_projects_section">
                    <?php
                    foreach ($projects as &$row) {
                        echo "<div class = \"_bubble\">".$row['name']."&nbsp;</div><div onclick = \"editProject(".$row['ID'].",`".$row['details']."`,".$row['icon'].");\" class = \"_edit_pencil\" id = \"_edit_pencil\">&#x1f589;</div><br>\n";
                    }
                    ?>
                </div>
                <br>
                <button type="button" name="add_project" style = "margin-bottom: 1em; margin-top: 0.3em;" class="w3-btn w3-blue-grey" onclick = "addProject();">+</button>
                <br>
                <button type="submit" name="submit" style = "margin-bottom: 1em;" class="w3-btn w3-blue-grey">Save</button>
                <br>

                <script type="text/javascript" defer>
                function addSkill() {
                    var i = document.querySelectorAll('[id^="skills\["]').length/2;
                    skills = document.getElementById("skills_section");
                    var els = createElementFromHTML(`
                        <label class="w3-text" style = "color: #0072B5; margin-top: 10px;" for="skills[${i}][name]">Skill ${i+1}</label>
                        <input type="text" class="w3-input w3-border w3-light-grey" id="skills[${i}][name]" name="skills[${i}][name]" placeholder="Skill ${i+1}" value = ""/>
                        <input type="hidden" class="w3-input w3-border w3-light-grey" id="skills[${i}][icon]" name="skills[${i}][icon]" placeholder="Skill ${i+1}" value = ""/>
                    `);
                    for (let item of els) {
                        skills.appendChild(item);
                    }
                };

                function createElementFromHTML(htmlString) {
                    var div = document.createElement('div');
                    div.innerHTML = htmlString.trim();

                    // Change this to div.childNodes to support multiple top-level nodes
                    return div.childNodes; 
                };
                </script>
            </form>
          </div>
          <div class="modal-footer">
            <h3>&nbsp;</h3>
          </div>
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
                <label class="w3-text" style = "color: #0072B5;" for="icon">Choose Icon <br></label>
                <br>
                <div class="dropdown">
                    <button class="dropbtn" type="button" id = "dropbtn">Dropdown</button>
                    <input type="hidden" name="icondropdown" value="" id = "icondropdown">
                    <div class="dropdown-content">
                        <?php
                            while ($row = $icons->fetch_assoc()) {
                                $img_tag = "<img src = icon.php?id=".$row['ID']." width = '20px', height = '20px' style = 'margin-right: 16px;'>";
                                $img_tag_large = "<img src = icon.php?id=".$row['ID']." width = '25px', height = '25px'>";
                                $onclick = "\" document.getElementById('icondropdown').value = '".$row['ID']."'; document.getElementById('dropbtn').innerHTML = `$img_tag_large`;\"";
                                echo "\t\t\t\t\t\t\t\t<a onclick = $onclick id = \"icon_".$row['ID']."\">".$img_tag.$row['descrip']."</a>\n";
                            }
                        ?>
                    </div>
                </div>
                <br>
                <br>
                <label class="w3-text" style = "color: #0072B5;" for="summary">Project Summary</label>
                <textarea id = "summary" name = "summary" class="w3-input w3-border w3-light-grey">
                    Project Summary goes here...
                </textarea>
                <br>
                <label class="w3-text" style = "color: #0072B5;" for="tinymce">Project Detail</label>
                <textarea id = "tinymce" name = "detail">
                    Project detail goes here...
                </textarea>
                <br>
                <button type="submit" name="submit" style = "margin-bottom: 1em;" class="w3-btn w3-blue-grey">Save</button>
                <br>
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

        function editProject(id, detail, img) {
            modal2.style.display = "block";
            var box = document.getElementById("projectID");
            var mce = document.getElementById("tinymce");
            box.value = id;

            document.getElementById('dropbtn').innerHTML = "<img src = icon.php?id=" + img + " width = '25px', height = '25px'>";

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
                        plugins: 'a11ychecker advcode casechange formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
                        toolbar: 'a11ycheck addcomment showcomments casechange checklist code formatpainter pageembed permanentpen table',
                        toolbar_mode: 'floating',
                        tinycomments_mode: 'embedded',
                        tinycomments_author: 'Author name',
                });
            }
        };

        window.toggleVis = function(elid) {
            var el = document.getElementById(elid);
            var dd = document.getElementsByClassName("dropdown-content");
            if (el.style.display == 'none') {
                on = true;
            } else {
                on = false;
            };
            for (var i = 0; i < dd.length; i++) {
                dd[i].style.display = 'none';
            };
            if (on) {
                el.style.display = 'inline-block';
            }

        };
        </script>

</body>
</html>