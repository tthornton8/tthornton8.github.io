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
        foreach ($projects as &$prow) {
            //echo print_r($projects);
            echo "<div class = \"_bubble _gr2\" onclick=\"clickBox('".$prow['details']."')\">".$prow['name'];
            // echo print_r($prow);
            if ($prow['gradz']) {
                echo "\n<span class = \"_gradz_project\">GradCherry</span>";
            }
            echo "\n<img src = icon.php?id=".$prow["icon"]." alt = \"icon\">";
            echo "\n<p>".$prow['summary']."</p></div>";
        }
    ?>

</div>
<div class = "_prof_section _quals">
<h2>Qualifications</h2>
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
        foreach ($out as $key => $quals) {
            $html .= "<h5>$key</h5> \n <ul>";
            foreach ($quals as &$value) {
                $html .= "\n<li>$value</li>";
            }
            $html .= "</ul>";
        }
        echo $html;
    ?>
</div>

<div class = "_prof_section _companies">
    <h2>Worked With</h2>
    <table>
        <?php
            $html = '';
            foreach ($usercompanies as &$row) {
                $html .= "<td style = \"width: 25%; text-align:center\"><img alt = \"Company Logo\" src = \"".$row["logourl"]."\" style =\"max-height: 100px\"></td>";
            }
            echo $html;
        ?>
    </table>    
</div>