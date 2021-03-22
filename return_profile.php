<?php 
function return_profile($name, $email, $degree, $uni, $about, $photo, $skills, $projects, $qual, $icons, $usercompanies, $id, $tolearn, $interested, $edit = True) {
    echo <<<EOT
    <div class = "_profile">

    <div class = "_prof_section _head">
        <img src="img.php?id=$id" alt="Profile Picture" class = "pp">
        <h1>$name 
        </h1>
        <hr>
        <div class = "_head_desc">
            <h2>$degree</h2>
            <h2>$uni</h2>
        </div>
    EOT;
        if ($uni == 'University of Bath') {
            echo '<img alt = "University Logo" src = "img/bath.png" style = "height: 80px;" class = "_uni">';
        }
    echo <<<EOT
    </div>

    <div class = "_prof_section _about">
        <h2>About</h2>
        <p>$about</p>
    </div>
    <div class = "_prof_section _skills">
        <h2 style = "grid-row: 1; grid-column: 1/3">Top Skills</h2>
    EOT;
        foreach ($skills as &$row) {
            echo "<div class = \"_bubble _gr1\">".$row["name"]."<img src = icon.php?id=".$row["icon"]." alt = \"icon\" onload=\"SVGInject(this)\"></div>";
        }
    echo <<<EOT
    </div>
    <div class = "_prof_section _skills _projects">
        <!-- <hr style = "grid-column: 1/5; grid-row: 3"> -->
        <h2 style = "grid-row: 1; grid-column: 1/3">Top Projects</h2>
    EOT;
        foreach ($projects as &$prow) {
            echo "<div class = \"_bubble _gr2\" onclick=\"clickBox('".$prow['details']."')\">".$prow['name'];
            if ($prow['gradz']) {
                echo "\n<span class = \"_gradz_project\">GradCherry</span>";
            }
            echo "\n<img src = icon.php?id=".$prow["icon"]." alt = \"icon\">";
            echo "\n<p>".$prow['summary']."</p></div>";
        }
    echo <<<EOT
    </div>
    <div class = "_prof_section _skills" style = "grid-area: tolearn">
        <h2 style = "grid-row: 1; grid-column: 1/3">Want to Learn</h2>
    EOT;
        foreach ($tolearn as &$row) {
            echo "<div class = \"_bubble _gr1\">".$row["title"]."<img src = icon.php?id=".$row["icon"]." alt = \"icon\" onload=\"SVGInject(this)\"></div>";
        }
    echo <<<EOT
    </div>
    <div class = "_prof_section _skills _projects" style = "grid-area: interested">
        <h2 style = "grid-row: 1; grid-column: 1/3">Projects Interested In</h2>
    EOT;
        foreach ($interested as &$row) {
            echo "<div class = \"_bubble _gr1\">".$row["title"]."<img src = icon.php?id=".$row["icon"]." alt = \"icon\" onload=\"SVGInject(this)\"></div>";
        }
    echo <<<EOT
    </div>
    <div class = "_prof_section _quals">
    <h2>Qualifications</h2>
    EOT;
        $html = '';
        $out = array();
        foreach ($qual as &$row) {
            if (array_key_exists($row["type"], $out)) {
                $out[$row["type"]][] = $row["value"];
            } else {
                $out[$row["type"]] = [$row["value"]];
            }
        }
        foreach ($out as $key => $quals) {
            $html .= "<h5>$key</h5> \n <ul>";
            foreach ($quals as &$value) {
                $html .= "\n<li>$value</li>";
            }
            $html .= "</ul>";
        }
    echo $html;
    echo <<<EOT
    </div>

    <div class = "_prof_section _companies">
        <h2>Worked With</h2>
        <table>
    EOT;
        $html = '';
        foreach ($usercompanies as &$row) {
            $html .= "<td style = \"width: 25%; text-align:center\"><img alt = \"Company Logo\" src = \"".$row["logourl"]."\" style =\"max-height: 100px\"></td>";
        }
        echo $html;
    echo <<<EOT
        </table>    
    </div>
    </div>
    EOT;
}
?>