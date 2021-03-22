<div id = "miniCV_<?php echo $id; ?>" onclick="showCV(<?php echo $id; ?>);">
    <h2><?php echo $name; ?></h2>
    <img src = "<?php echo "img.php?id={$id}"; ?>">
    <hr style = "grid-area: r0">
    <h3 style = "grid-area: r1"> Top Skills: </h3>
    <h4 style = "grid-area: r2"> 
    <?php 
        foreach ($skills as &$row) {
            echo $row['name']."<br/>";
        }
    ?>
    </h4>
    <hr style = "grid-area: r3">
    <h3 style = "grid-area: r4"> Top Projects: </h3>
    <h4 style = "grid-area: r5">
    <?php 
        foreach ($projects as &$row) {
            echo $row['name']."<br/>";
        }
    ?>
    </h4>
</div>