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

function return_forum_thread($section_name, $ID, $replies, $views, $last_user, $last_time) {
    echo <<<EOT
    <div class="_prof_section _forum_name">
        <a href = "./discussion.html?thread=$ID" class="_name center_section _nomargin">
            <h2 class = "_name center_section _nomargin">
            <i class="fas fa-comment-dots"></i> $section_name </h2>
        </a>
        <h3 class = "_threads center_section _nomargin"> $replies </h3>
        <h3 class = "_post center_section _nomargin"> $views </h3>
        <h4 class = "_title _nomargin"> $last_user </h4>
        <h4 class = "_time _nomargin"> $last_time </h4>
    </div>
    EOT;
}

require_once('config.php');

$name = $_GET['name'];
$post = $_GET['post'];
$thread = $_GET['thread'];

// echo "name = $name";
// echo "post = $post";
// echo "thread = $thread";

$forum_names = [];
$sql = "SELECT * from forum_name;";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $forum_names[] = $row;
    }  
}

if ($thread and ! $name) {
    $sql = "SELECT name_ID from forum_post WHERE ID = $thread;";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $name = $row['name_ID'];
}

$forum_threads = [];
if ($name) {
    $name_link_title = $forum_names[$name-1]['title'];
    echo "<h4 style=\"margin-left:2.5%;\"><a href = \"./discussion.html\"> Discussion </a> > $name_link_title</h4>";
    $sql = "SELECT * from forum_thread WHERE name_ID = $name;";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $forum_threads[] = $row;
        }  
    }
}

$forum_posts = [];
if ($thread) {
    $name_link_title = $forum_names[$name-1]['title'];
    $thread_link_title = $forum_threads[$threads-1]['title'];
    echo "<h4 style=\"margin-left:2.5%;\"><a href = \"./discussion.html\"> Discussion </a> > <a href = \"./discussion.html?name=$name\">$name_link_title</a> > $thread_link_title</h4>";
    $sql = "UPDATE forum_thread SET views = views + 1 WHERE ID = $thread;";
    $result = $conn->query($sql);
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
            foreach ($forum_threads as &$row) {
                return_forum_thread($row['title'], $row['ID'], $row['replies'], $row['views'], 'user', 'time');
            }
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