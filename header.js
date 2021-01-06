document.write(`
    <div class = "_top" id = "top">
        <div class = "_heading">
            <h1 >GradZ</h1>
            <p>Supporting students to develop into experts and showcasing potential. <br>
            Providing companies with the platform to engage with emerging talent and fresh ideas.</p>
        </div>

        <div class = "_login" id = "login">
            <ul id = "login_list">
                <li><a href = "profile.php">Log in</a></li>
                <li><a href = "#">Register</a></li>
            </ul>
        </div>

        <div class = "_nav" id = "_navBar">
            <ul>
                <li><a href="index.html"     id = "home"      >Home     </a></li>
                <li><a href="about.html"     id = "about"     >About Us </a></li>
                <li><a href="students.html"  id = "students"  >Students </a></li>
                <li><a href="companies.html" id = "companies" >Companies</a></li>
                <li><a href="projects.html"  id = "projects"  >Projects </a></li>
                <li><a href="news.html"      id = "news"      >News     </a></li>
                <li><a href="javascript:void(0);" class="icon" onclick="expandBar()"><i class="fa fa-bars"></i></a></li>
            </ul>
        </div>
    </div>
`);

if (typeof logged_in !== 'undefined') {
    if (logged_in == 'true') {
        x = document.getElementById("login_list")
        x.innerHTML = `
            <li><a href="?action=logout">Logout</a></li>
            <li><a href="profile.php">My Account</a></li>
        `
    }
}