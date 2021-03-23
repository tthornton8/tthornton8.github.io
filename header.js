var els = createElementFromHTML(`
    <div class = "_top" id = "top">
        <div class = "_heading ">
        <h1><a id = "home_link" href="http://gradcherry.com"><img src="logo_grey.png">Grad<span style = "color:var(--lightCherry)">Cherry</span></a></h1>
        </div>

        <div class = "_login" id = "login">
            <ul id = "login_list">
                <li><a href = "login_student.php">Log in</a></li>
                <li><a href = "signup_student.php">Register</a></li>
            </ul>
        </div>

        <div class = "_nav" id = "_navBar">
            <ul>
                <li><a href="index.html"     id = "home"      >Home     </a></li>
                <li><a href="about.html"     id = "about"     >About Us </a></li>
                <li><a href="students.html"  id = "students"  >Students </a></li>
                <li><a href="mentoring.html" id = "mentoring" >Mentoring</a></li>
                <li><a href="companies.html" id = "companies" >Companies</a></li>
                <li><a href="discussion.html"id = "discussion" >Discussion</a></li>
                <li><a href="projects.html"  id = "projects"  >Projects </a></li>
                <li><a href="news.html"      id = "news"      >News     </a></li>
                <li><a href="javascript:void(0);" class="icon" onclick="expandBar()"><i class="fa fa-bars"></i></a></li>
            </ul>
        </div>
    </div>
`);

for (let item of els) {
    document.body.appendChild(item);
  }

if (typeof logged_in !== 'undefined') {
    if (logged_in == 'true') {
        x = document.getElementById("login_list")
        x.innerHTML = `
            <li><a href="?action=logout">Logout</a></li>
            <li><a href="profile.php">My Account</a></li>
        `
    }
};

function createElementFromHTML(htmlString) {
    var div = document.createElement('div');
    div.innerHTML = htmlString.trim();
  
    // Change this to div.childNodes to support multiple top-level nodes
    return div.childNodes; 
  };