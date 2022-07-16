<header class="header">
    <nav class="navbar navbar-expand-lg fixed-top py-3">
        <div class="container"><a href="#" class="navbar-brand text-uppercase font-weight-bold">UVA Course Requirement</a>
            <button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler navbar-toggler-right"><i class="fa fa-bars"></i></button>

            <div id="navbarSupportedContent" class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active"><a href="/CS4750-Project/index.php" class="nav-link text-uppercase font-weight-bold">Home</a></li>
                    <li class="nav-item active"><a href="/CS4750-Project/user_profile.php" class="nav-link text-uppercase font-weight-bold">Profile</a></li>
                    <li class="nav-item"><a href="/CS4750-Project/majors.php" class="nav-link text-uppercase font-weight-bold">Majors</a></li>
                    <li class="nav-item"><a href="/CS4750-Project/course_management.php" class="nav-link text-uppercase font-weight-bold">Course Search</a></li>
                    <li class="nav-item"><a href="/CS4750-Project/prereqs.php" class="nav-link text-uppercase font-weight-bold">Prerequisites</a></li>
                    <?php if (isset($_SESSION['computing_id'])) { ?>
                        <li class="nav-item"><a href="/CS4750-Project/Logout.php" class="nav-link text-uppercase font-weight-bold">Logout</a></li>
                    <?php } else { ?>
                        <li class="nav-item"><a href="/CS4750-Project/Login.php" class="nav-link text-uppercase font-weight-bold">Login</a></li>
                        <li class="nav-item"><a href="/CS4750-Project/Register.php" class="nav-link text-uppercase font-weight-bold">Register</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
<br><br>