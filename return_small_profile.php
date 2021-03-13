<div>
    <h2><?php echo $name; ?></h2>
    <img src = "<?php echo "img.php?id={$id}"; ?>">
    <h3 style = "grid-area: r1"> Top Skills: </h3>
    <h4 style = "grid-area: r2"> 
    <?php 
        foreach ($skills as &$row) {
            echo $row['name']."<br/>";
        }
    ?>
    </h4>
    <h3 style = "grid-area: r3"> Top Projects: </h3>
    <h4 style = "grid-area: r4">
    <?php 
        foreach ($projects as &$row) {
            echo $row['name']."<br/>";
        }
    ?>
    </h4>
</div>