<?php 
function return_forum_name($section_name, $ID, $threads, $posts, $last_title, $last_user, $last_time) {
    echo <<<EOT
    <div class="_prof_section _forum_name">
        <a href = "./discussion.html?name=$ID" class="_name center_section _nomargin">
            <h2 class = "_name center_section _nomargin">
            <i class="fa fa-comments"></i> $section_name </h2>
        </a>
        <h3 class = "_threads center_section _nomargin"> $threads </h3>
        <h3 class = "_post center_section _nomargin"> $posts </h3>
        <h4 class = "_title _nomargin"> $last_title </h4>
        <h4 class = "_user _nomargin"> $last_user </h4>
        <h4 class = "_time _nomargin"> $last_time </h4>
    </div>
    EOT;
}

require_once('config.php');

$name = $_GET['name'];
$post = $_GET['post'];
$thread = $_GET['thread'];

echo "name = $name";
echo "post = $post";
echo "thread = $thread";

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
    <?php 
        if ($post) {
            echo "post placeholder";
        } elseif ($thread) {
            echo "thread placeholder";
        } elseif ($name) {
            echo <<<EOT
            <div class = "_forum_title_block">
                <h2 class = "_nomargin" style = "grid-area: _name">Thread/Author</h2>
                <h2 class = "_nomargin" style = "grid-area: _threads">Replies</h2>
                <h2 class = "_nomargin" style = "grid-area: _posts">Views</h2>
                <h2 class = "_nomargin" style = "grid-area: _last; justify-self: left;">Last Post</h2>
            </div>
            EOT;
        } else {
            echo <<<EOT
            <div class = "_forum_title_block">
                <h2 class = "_nomargin" style = "grid-area: _name">Name</h2>
                <h2 class = "_nomargin" style = "grid-area: _threads">Threads</h2>
                <h2 class = "_nomargin" style = "grid-area: _posts">Posts</h2>
                <h2 class = "_nomargin" style = "grid-area: _last; justify-self: left;">Last Post</h2>
            </div>
            EOT;
            foreach ($forum_names as &$row) {
                return_forum_name($row['title'], $row['ID'], '8', '12', 'title', 'user', 'time');
            }
        }
    ?>
</div>