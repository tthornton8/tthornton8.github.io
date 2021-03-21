<?php 
require_once('get_profile.php');
require_once('config.php');

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

function return_forum_post($ID, $user_ID, $content, $date, $conn) {
    list($name, $email, $degree, $uni, $about, $photo, $skills, $projects, $qual, $icons, $usercompanies) = get_profile_vars($conn, $user_ID);
    $d=strtotime($date);
    $dstr=date('l jS \of F Y h:i A', $d);
    echo <<<EOT
    <div class="_prof_section _forum_post">
        <div class = "_forum_post_header">
            <img src="img.php?id=$user_ID" alt="Profile Picture" class = "pp">
            <h5> $name </h5>
            <h6> $degree </h6>
            <h6> $uni </h6>
        </div>
        <div class = "_forum_post_content">
        <h6> $dstr </h6>
        <hr/>
        <p> $content </p>
        </div>
        <div class = "_forum_post_buttons">
            <a href = "#0" onclick = "replyPost($ID)"> <i class="fa fa-reply"></i> Reply </a>
            <a href = "#0"> <i class="fa fa-quote-right"></i> Quote </a>
        </div>
    </div>
    EOT;
}    

$name = $_GET['name'];
$post = $_GET['post'];
$thread = $_GET['thread'];

// echo "name = $name";
// echo "post = $post";
// echo "thread = $thread";

$latest_post = [];
$sql = <<<EOT
SELECT tt.*
FROM forum_post tt
INNER JOIN
    (SELECT name_ID, MAX(Date) AS MaxDateTime
    FROM forum_post
    GROUP BY name_ID) groupedtt 
ON tt.name_ID = groupedtt.name_ID 
AND tt.Date = groupedtt.MaxDateTime
EOT;
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $latest_post[] = $row;
    }  
}

$forum_names = [];
$sql = "SELECT * from forum_name;";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $forum_names[] = $row;
    }  
}

if ($thread and ! $name) {
    $sql = "SELECT name_ID from forum_post WHERE thread_ID = $thread;";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $name = $row['name_ID'];
}

$forum_threads = [];
if ($name) {
    $name_link_title = $forum_names[$name-1]['title'];
    if (! $thread) {
        echo "<h4 style=\"margin-left:2.5%;\"><a href = \"./discussion.html\"> Discussion </a> > $name_link_title</h4>";
    }
    $sql = "SELECT * from forum_thread WHERE name_ID = $name;";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $forum_threads[] = $row;
        }  
    }
}

if (isset($_POST['btnsubmit'])) {  
    if (isset($_SESSION['id'])) {
        $logged_in = 'true';
        $id = $_SESSION['id'];
        list($name, $email, $degree, $uni, $about, $photo, $skills, $projects, $qual, $icons, $usercompanies) = get_profile_vars($conn, $id);
    
    } else {
        $logged_in = 'false';
        header('Location: login_student.php');
    }

    extract($_POST);
    $d = date("Y-m-d");
    $sql = "INSERT INTO forum_post (user_ID, thread_ID, name_ID, content, date) VALUES ($id, ".htmlspecialchars($conn->real_escape_string($thread)).", ".htmlspecialchars($conn->real_escape_string($name)).", ".htmlspecialchars($conn->real_escape_string($reply_text)).", $d);";
    echo $sql;
    $result = $conn->query($sql);
}

// print_r($forum_threads);

$forum_posts = [];
if ($thread) {
    $sql = "SELECT title FROM forum_thread WHERE ID - $thread;";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $thread_link_title = $row['title'];

    echo "<h4 style=\"margin-left:2.5%;\"><a href = \"./discussion.html\"> Discussion </a> > <a href = \"./discussion.html?name=$name\">$name_link_title</a> > $thread_link_title</h4>";
    $sql = "UPDATE forum_thread SET views = views + 1 WHERE ID = $thread;";
    $result = $conn->query($sql);
    $sql = "SELECT * from forum_post WHERE thread_ID = $thread;";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $forum_posts[] = $row;
        }  
    }
}
?>

<div class = "_prof_section">
    <?php 
        if ($post) {
            echo "post placeholder";
        } elseif ($thread) {
            foreach ($forum_posts as &$row) {
                return_forum_post($row['ID'], $row['user_ID'], $row['content'], $row['date'], $conn);
            }
            $action = htmlspecialchars($_SERVER["PHP_SELF"]);
            echo <<<EOT
            <div class="_prof_section _forum_new_post hidden">
                <form method="post" id = "reply_form" action="$action?thread=$thread" enctype="multipart/form-data">
                    <div class = "reply_title">
                        <h5> Reply to this thread </h6>
                    </div>
                    <div class = "reply_buttons">
                        <button type="submit" name="btnsubmit" style = "margin-bottom: 1em; margin: 0 auto; margin-right: -43%;" class="w3-btn w3-flat-emerald">Send</button>
                    </div>
                    <div class = "reply_text">
                        <textarea id = "reply_text" name = "reply_text" class="w3-input w3-border w3-light-grey" placeholder = "Reply here..."></textarea>
                    </div>
                </form>
            </div>
            EOT;
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
            for ($i = 0; $i <= count($forum_names)-1; $i+=1) {
                $row = $forum_names[$i];
                $row_last = $latest_post[$i];
                return_forum_name($row['title'], $row['ID'], $row['threads'], $row['posts'], $row_last['ID'], $row_last['user_ID'], $row_last['date']);
            }
        }
    ?>
</div>

<script>
function replyPost(ID) {
    box = document.getElementsByClassName('_forum_new_post')[0];
    box.classList.remove("hidden");
}
</script>