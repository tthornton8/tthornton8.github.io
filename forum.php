<?php 
function return_forum_name($section_name) {
    echo <<<EOT
    <div class="_prof_section _forum_name">
        <h2 class = "_name center_section _nomargin"> <i class="fa fa-comments"></i> $section_name </h2>
        <h3 class = "_threads center_section _nomargin"> 8 </h3>
        <h3 class = "_post center_section _nomargin"> 12 </h3>
        <h4 class = "_title _nomargin"> title </h4>
        <h4 class = "_user _nomargin"> user </h4>
        <h4 class = "_time _nomargin"> time </h4>
    </div>
    EOT;
}

$sections = array (
    'New Ideas',
    'Projects Discussion',
    'Skills Discussion',
    'Mentoring',
);
?>



<div class = "_prof_section">
    <div class = "_forum_title_block">
        <h2 class = "_nomargin" style = "grid-area: _name">Name</h2>
        <h2 class = "_nomargin" style = "grid-area: _threads">Threads</h2>
        <h2 class = "_nomargin" style = "grid-area: _posts">Posts</h2>
        <h2 class = "_nomargin" style = "grid-area: _last; justify-self: left;">Last Post</h2>
    </div>
    <?php 
        foreach ($sections as &$row) {
            return_forum_name($row);
        }
    ?>
</div>