<?php 
function return_forum_name($section_name, $threads, $posts, $last_title, $last_user, $last_time) {
    echo <<<EOT
    <div class="_prof_section _forum_name">
        <h2 class = "_name center_section _nomargin"> <i class="fa fa-comments"></i> $section_name </h2>
        <h3 class = "_threads center_section _nomargin"> $threads </h3>
        <h3 class = "_post center_section _nomargin"> $posts </h3>
        <h4 class = "_title _nomargin"> $last_title </h4>
        <h4 class = "_user _nomargin"> $last_user </h4>
        <h4 class = "_time _nomargin"> $last_time </h4>
    </div>
    EOT;
}

$name = $_GET['name'];
$post = $_GET['post'];
$thread = $_GET['thread'];

echo "name = $name";
echo "post = $post";
echo "thread = $thread";

// $sections = array (
//     'New Ideas',
//     'Projects Discussion',
//     'Skills Discussion',
//     'Mentoring',
// );

$forum_names = [];
$sql = "SELECT * from forum_name;";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $forum_names[] = $row;
    }
    
}
?>

<div class = "_prof_section">
    <form method="post" id = "cvForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
        <div class = "_forum_title_block">
            <h2 class = "_nomargin" style = "grid-area: _name">Name</h2>
            <h2 class = "_nomargin" style = "grid-area: _threads">Threads</h2>
            <h2 class = "_nomargin" style = "grid-area: _posts">Posts</h2>
            <h2 class = "_nomargin" style = "grid-area: _last; justify-self: left;">Last Post</h2>
        </div>
        <?php 
            if ($post) {
                echo "post placeholder";
            } elseif ($thread) {
                echo "thread placeholder";
            } elseif ($name) {
                echo "name placeholder";
            } else {
                foreach ($forum_names as &$row) {
                    return_forum_name($row['title'], '8', '12', 'title', 'user', 'time');
                }
            }
        ?>
    </form>
</div>